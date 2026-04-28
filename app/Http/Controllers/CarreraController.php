<?php

namespace App\Http\Controllers;

use App\Models\{Carrera, Estado, Nivel};
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class CarreraController extends Controller
{
    /**
     * Lista las carreras con su relación de jerarquía.
     */
    public function index(Request $request)
    {
        // Cargamos carreraBase para saber de qué tronco común dependen
        $query = Carrera::with(['nivel', 'estado', 'carreraBase']);

        if ($request->has('papelera')) {
            $carreras = $query->onlyTrashed()->get();
        } else {
            $carreras = $query->get();
        }

        return view('admin.carreras.index', compact('carreras'));
    }

    /**
     * Muestra el formulario de creación integrando la selección de Carrera Base.
     */
    public function create()
    {
        $niveles = Nivel::whereHas('estado', function ($q) {
            $q->where('slug', 'activo');
        })->get();

        $estados = Estado::where('tipo', 'GLOBAL')->get();
        $estadoActivo = Estado::active()->first();

        // Solo enviamos las carreras que pueden ser padres (Tronco Común)
        $carrerasBase = Carrera::where('es_tronco_comun', true)->get();

        return view('admin.carreras.create', compact('niveles', 'estados', 'estadoActivo', 'carrerasBase'));
    }

    /**
     * Almacena la carrera validando la lógica de herencia.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'            => 'required|string|max:255',
            'sigla'             => 'required|string|max:20|unique:carreras,sigla',
            'titulo'            => 'required|string|max:255',
            'duracion'          => 'required|integer|min:1',
            'resolucion'        => 'nullable|string|max:100',
            'nivel_id'          => 'required|exists:nivels,id',
            'estado_id'         => 'required|exists:estados,id',
            'carrera_base_id'   => 'nullable|exists:carreras,id',
            'es_tronco_comun'   => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            // Si se marca como tronco común, no puede tener una carrera base asignada
            $esTronco = $request->has('es_tronco_comun');
            $carreraBaseId = $esTronco ? null : $request->carrera_base_id;

            Carrera::create([
                'nombre'          => strtoupper($request->nombre),
                'sigla'           => strtoupper($request->sigla),
                'titulo'          => strtoupper($request->titulo),
                'duracion'        => $request->duracion,
                'resolucion'      => strtoupper($request->resolucion),
                'nivel_id'        => $request->nivel_id,
                'estado_id'       => $request->estado_id,
                'carrera_base_id' => $carreraBaseId,
                'es_tronco_comun' => $esTronco,
            ]);

            DB::commit();
            return redirect()->route('admin.carreras.index')
                ->with('success', 'La carrera se registró correctamente en SIG@.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el formulario de edición filtrando la propia carrera de la lista de bases.
     */
    public function edit(Carrera $carrera)
    {
        $niveles = Nivel::whereHas('estado', function ($q) {
            $q->where('slug', 'activo');
        })->get();

        $estados = Estado::where('tipo', 'GLOBAL')->get();

        // Evitamos que una carrera sea padre de sí misma
        $carrerasBase = Carrera::where('es_tronco_comun', true)
            ->where('id', '!=', $carrera->id)
            ->get();

        return view('admin.carreras.edit', compact('carrera', 'niveles', 'estados', 'carrerasBase'));
    }

    /**
     * Actualiza el registro manteniendo la integridad referencial.
     */
    public function update(Request $request, Carrera $carrera)
    {
        $request->validate([
            'nombre'            => 'required|string|max:255',
            'sigla'             => 'required|string|max:20|unique:carreras,sigla,' . $carrera->id,
            'titulo'            => 'required|string|max:255',
            'duracion'          => 'required|integer|min:1',
            'nivel_id'          => 'required|exists:nivels,id',
            'estado_id'         => 'required|exists:estados,id',
            'carrera_base_id'   => 'nullable|exists:carreras,id',
            'es_tronco_comun'   => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $esTronco = $request->has('es_tronco_comun');
            $carreraBaseId = $esTronco ? null : $request->carrera_base_id;

            $carrera->update([
                'nombre'          => strtoupper($request->nombre),
                'sigla'           => strtoupper($request->sigla),
                'titulo'          => strtoupper($request->titulo),
                'duracion'        => $request->duracion,
                'resolucion'      => strtoupper($request->resolucion),
                'nivel_id'        => $request->nivel_id,
                'estado_id'       => $request->estado_id,
                'carrera_base_id' => $carreraBaseId,
                'es_tronco_comun' => $esTronco,
            ]);

            DB::commit();
            return redirect()->route('admin.carreras.index')
                ->with('success', 'La carrera se actualizó correctamente.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Mueve el registro a la papelera (Soft Delete).
     */
    public function destroy(Carrera $carrera)
    {
        try {
            // Validación de integridad: No eliminar si tiene carreras hijas
            if ($carrera->especialidades()->count() > 0) {
                return back()->with('error', "No se puede eliminar: Esta carrera es base de otras especialidades.");
            }

            // Validación futura: materias, inscritos, etc.

            $carrera->delete();
            return redirect()->route('admin.carreras.index')
                ->with('info', "La carrera {$carrera->sigla} fue enviada a la papelera.");
        } catch (Exception $e) {
            return back()->with('error', 'Error al intentar eliminar.');
        }
    }

    /**
     * Restaura una carrera desde la papelera.
     */
    public function restore($id)
    {
        try {
            $carrera = Carrera::withTrashed()->findOrFail($id);
            $carrera->restore();

            return redirect()->route('admin.carreras.index')
                ->with('success', "Carrera {$carrera->sigla} restaurada con éxito.");
        } catch (Exception $e) {
            return back()->with('error', 'No se pudo restaurar el registro.');
        }
    }
}
