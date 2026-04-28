<?php

namespace App\Http\Controllers;

use App\Models\{Pensum, Carrera, Materia, Grado, Estado}; // Importamos los modelos necesarios
use Illuminate\Http\Request;

class PensumController extends Controller
{

    public function index(Request $request)
    {
        // 1. Carga de Carreras para el Selector (Solo especialidades para armar mallas)
        $carreras = Carrera::with('carreraBase')->get();

        // 2. Identificar Carrera Seleccionada
        $carrera_id = $request->input('carrera_id') ?? ($carreras->first()->id ?? null);
        $carrera = Carrera::with('carreraBase')->find($carrera_id);

        if (!$carrera) {
            return redirect()->back()->with('error', 'Carrera no encontrada');
        }

        // 3. DETERMINAR EL RANGO DE GRADOS (Sincronizado con Nivel Académico)
        // Solo mostramos grados que pertenezcan al mismo Nivel de la carrera (Lic, Tec, etc)
        $grados = Grado::where('nivel_id', $carrera->nivel_id)
            ->orderBy('orden', 'asc')
            ->get();

        // 4. EL SECRETO DEL SIG@: QUERY DE HERENCIA
        // Buscamos materias de la carrera actual O de su carrera base (Tronco Común)
        $idsParaMalla = [$carrera->id];
        if ($carrera->carrera_base_id) {
            $idsParaMalla[] = $carrera->carrera_base_id;
        }

        $pensums = Pensum::with(['materia', 'grado', 'estado'])
            ->whereIn('carrera_id', $idsParaMalla)
            ->get()
            ->groupBy('grado_id'); // Agrupamos para facilitar el renderizado de columnas

        // 5. Catálogo Espejo (Materias que aún no están en esta malla)
        $materiasAsignadasIds = Pensum::whereIn('carrera_id', $idsParaMalla)
            ->pluck('materia_id');

        $materias_disponibles = Materia::whereNotIn('id', $materiasAsignadasIds)
            ->where('estado_id', 1)
            ->get();

        $estados = Estado::whereIn('slug', ['activo', 'inactivo'])->get();

        return view('admin.pensums.index', compact(
            'pensums',
            'carreras',
            'carrera',
            'materias_disponibles',
            'grados',
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
    public function store(Request $request) {}
    public function show(Pensum $pensum) {}
    public function edit(Pensum $pensum) {}
    public function update(Request $request, Pensum $pensum) {}
    public function destroy(Pensum $pensum) {}
}
