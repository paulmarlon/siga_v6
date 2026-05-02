<?php

namespace App\Http\Controllers;

use App\Models\{Pensum, Carrera, Materia, Grado, Estado}; // Importamos los modelos necesarios
use Illuminate\Http\Request;

class PensumController extends Controller
{

    public function index(Request $request)
    {
        // 1. Carga de Carreras para el Selector
        $carreras = Carrera::with('carreraBase')->get();

        // 2. Identificar Carrera Seleccionada
        $carrera_id = $request->input('carrera_id') ?? ($carreras->first()->id ?? null);
        $carrera = Carrera::with('carreraBase')->find($carrera_id);

        if (!$carrera) {
            return redirect()->back()->with('error', 'Carrera no encontrada');
        }

        // 3. DETERMINAR EL RANGO DE GRADOS (Adaptado V6.2)
        // Filtramos por nivel y ordenamos
        $queryGrados = Grado::where('nivel_id', $carrera->nivel_id)
            ->orderBy('orden', 'asc');

        /**
         * REGLA DE NEGOCIO:
         * Si es una carrera definida como "Tronco Común", solo mostramos el Ciclo 1 (1er y 2do sem).
         */
        if ($carrera->es_tronco_comun) {
            $queryGrados->where('ciclo', 1);
        }

        $grados = $queryGrados->get();

        // Identificar grados bloqueados (Lectura) para Especialidades
        // Si la carrera tiene una base, los grados de Ciclo 1 son solo lectura.
        $gradosBloqueadosIds = [];
        if ($carrera->carrera_base_id) {
            $gradosBloqueadosIds = $grados->where('ciclo', 1)->pluck('id')->toArray();
        }

        // 4. QUERY DE HERENCIA
        $idsParaMalla = [$carrera->id];
        if ($carrera->carrera_base_id) {
            $idsParaMalla[] = $carrera->carrera_base_id;
        }

        $pensums = Pensum::with(['materia', 'grado', 'estado'])
            ->whereIn('carrera_id', $idsParaMalla)
            ->get()
            ->groupBy('grado_id');

        // 5. Catálogo Espejo
        $materiasAsignadasIds = Pensum::whereIn('carrera_id', $idsParaMalla)
            ->pluck('materia_id');

        $materias_disponibles = Materia::whereNotIn('id', $materiasAsignadasIds)
            ->where('estado_id', 1)
            ->get();

        $estados = Estado::whereIn('slug', ['activo', 'inactivo'])->get();

        // Retornamos la vista con la nueva variable de control
        return view('admin.pensums.index', compact(
            'pensums',
            'carreras',
            'carrera',
            'materias_disponibles',
            'grados',
            'gradosBloqueadosIds', // <--- IMPORTANTE: Usar en el Blade para el atributo data-puedo-editar
            'estados'
        ));
    }

    public function updateGrado(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:pensums,id',
                'grado_id' => 'required|exists:grados,id',
            ]);

            $pensum = Pensum::findOrFail($request->id);
            $nuevoGrado = Grado::findOrFail($request->grado_id);

            // RESTRICCIÓN V6.2: Bloqueo de Ciclo por Herencia
            // Si el registro pertenece a la Carrera Base, NO se puede mover desde la vista de la Hija
            $carreraActualId = $request->input('carrera_contexto_id'); // ID de la carrera que el usuario está viendo

            if ($pensum->carrera_id != $carreraActualId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No puedes mover materias del Tronco Común desde esta pantalla.'
                ], 403);
            }

            $pensum->grado_id = $request->grado_id;
            $pensum->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Malla actualizada: ' . $pensum->materia->nombre . ' movida a ' . $nuevoGrado->nombre
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error en la operación: ' . $e->getMessage()
            ], 500);
        }
    }
    public function create() {}
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'carrera_id' => 'required|exists:carreras,id',
                'materia_id' => 'required|exists:materias,id',
                'grado_id'   => 'required|exists:grados,id',
            ]);

            // La integridad la maneja el índice único en la DB (V6.2)
            $pensum = Pensum::create($validated + ['estado_id' => 1]);

            return response()->json([
                'status' => 'success',
                'id' => $pensum->id,
                'message' => 'Materia vinculada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'La materia ya existe en esta malla o hay un error de integridad.'
            ], 422);
        }
    }
    public function show(Pensum $pensum) {}
    public function edit(Pensum $pensum) {}
    // En PensumController.php

    public function destroy(Request $request, Pensum $pensum)
    {
        try {
            $carreraContextoId = $request->input('carrera_contexto_id');

            // Si no se envía el contexto o no coincide con la carrera de la materia
            if (!$carreraContextoId || $pensum->carrera_id != $carreraContextoId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se puede eliminar una materia heredada o sin contexto válido.'
                ], 403);
            }

            $pensum->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Materia desvinculada de la malla'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
