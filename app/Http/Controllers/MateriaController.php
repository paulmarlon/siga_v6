<?php

namespace App\Http\Controllers;

use App\Models\{Materia, Estado};
use Illuminate\Http\Request;

class MateriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Iniciamos la consulta con la relación del estado (Global)
        // Usamos withTrashed() solo si se solicita la papelera
        $query = Materia::with('estado');

        // 2. Filtro: Papelera vs Activas
        if ($request->has('papelera')) {
            $query->onlyTrashed();
        }

        // 3. Filtro: Búsqueda avanzada por Tipo (Teorica, Tecnica, Laboratorio)
        if ($request->filled('tipo')) {
            $query->where('tipo_materia', $request->tipo);
        }

        // 4. Filtro: Búsqueda por Sigla (Para el buscador avanzado)
        if ($request->filled('sigla')) {
            $query->where('sigla', 'like', '%' . $request->sigla . '%');
        }

        // 5. Ejecutar la consulta
        $materias = $query->orderBy('sigla', 'asc')->get();

        // Necesitamos los estados para los modales o filtros rápidos en la vista
        $estados = Estado::all();

        return view('admin.materias.index', compact('materias', 'estados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Traemos los estados para el select del formulario
        $estados = Estado::where('tipo', 'GLOBAL')->get();

        // Definimos los tipos de materia para consistencia en la base de datos
        $tipos = ['Teorica', 'Practica', 'Teorica-Practica'];

        return view('admin.materias.create', compact('estados', 'tipos'));
    }

    public function store(Request $request)
    {
        // 1. Validación rigurosa
        $request->validate([
            'sigla' => 'required|unique:materias,sigla|max:20',
            'nombre' => 'required|string|max:100',
            'horas_academicas' => 'required|integer|min:1',
            'tipo_materia' => 'required|in:Teorica,Tecnica,Laboratorio',
            'estado_id' => 'required|exists:estados,id',
            'es_comun' => 'nullable|boolean'
        ]);

        try {
            // 2. Creación del registro
            Materia::create([
                'sigla' => strtoupper($request->sigla), // Forzamos mayúsculas en la sigla
                'nombre' => strtoupper($request->nombre), // Consistencia visual en el catálogo
                'horas_academicas' => $request->horas_academicas,
                'tipo_materia' => $request->tipo_materia,
                'es_comun' => $request->has('es_comun') ? true : false,
                'estado_id' => $request->estado_id,
            ]);

            return redirect()->route('admin.materias.index')
                ->with('success', 'La materia se registró correctamente en el catálogo.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Hubo un error al registrar la materia: ' . $e->getMessage())
                ->withInput(); // Mantiene los datos que el usuario ya escribió
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Materia $materia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Materia $materia)
    {
        // Traemos los estados globales para el select
        $estados = Estado::where('tipo', 'GLOBAL')->get();

        // Los mismos tipos definidos en el create
        $tipos = ['Teorica', 'Practica', 'Teorica-Practica'];

        return view('admin.materias.edit', compact('materia', 'estados', 'tipos'));
    }

    public function update(Request $request, Materia $materia)
    {
        // 1. Validación (Exceptuando el ID actual para la sigla única)
        $request->validate([
            'sigla' => "required|max:20|unique:materias,sigla,{$materia->id}",
            'nombre' => 'required|string|max:100',
            'horas_academicas' => 'required|integer|min:1',
            'tipo_materia' => 'required|in:Teorica,Practica,Teorica-Practica',
            'estado_id' => 'required|exists:estados,id',
            'es_comun' => 'nullable|boolean'
        ]);

        try {
            // 2. Actualización de datos
            $materia->update([
                'sigla' => strtoupper($request->sigla),
                'nombre' => strtoupper($request->nombre),
                'horas_academicas' => $request->horas_academicas,
                'tipo_materia' => $request->tipo_materia,
                'es_comun' => $request->has('es_comun') ? true : false,
                'estado_id' => $request->estado_id,
            ]);

            return redirect()->route('admin.materias.index')
                ->with('success', "La materia [{$materia->sigla}] fue actualizada con éxito.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    // 1. Borrado Lógico (Mover a papelera)
    public function destroy(Materia $materia)
    {
        try {
            $materia->delete();

            return redirect()->route('admin.materias.index')
                ->with('success', "La materia [{$materia->sigla}] ha sido movida a la papelera.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'No se pudo eliminar el registro: ' . $e->getMessage());
        }
    }

    // 2. Restaurar registro de la papelera
    public function restore($id)
    {
        try {
            // Buscamos solo en los registros eliminados (trashed)
            $materia = Materia::onlyTrashed()->findOrFail($id);
            $materia->restore();

            return redirect()->route('admin.materias.index')
                ->with('success', "La materia [{$materia->sigla}] ha sido restaurada con éxito.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al restaurar: ' . $e->getMessage());
        }
    }

    // 3. Eliminación Permanente (Opcional - para el icono de la flama)
    public function forceDelete($id)
    {
        try {
            $materia = Materia::onlyTrashed()->findOrFail($id);
            $materia->forceDelete();

            return redirect()->route('admin.materias.index')
                ->with('success', "La materia ha sido eliminada permanentemente del sistema.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'No se pudo eliminar permanentemente: ' . $e->getMessage());
        }
    }
}
