<?php

namespace App\Http\Controllers;

use App\Models\{Periodo, Gestion, Estado};
use Illuminate\Http\Request;

class PeriodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Si la URL tiene ?papelera=1, mostramos SOLO los eliminados
        if ($request->has('papelera')) {
            $periodos = Periodo::onlyTrashed()
                ->with(['gestion', 'estado'])
                ->orderBy('id', 'desc')
                ->get();
        } else {
            // Por defecto, mostramos SOLO los activos (comportamiento normal de Eloquent)
            $periodos = Periodo::with(['gestion', 'estado'])
                ->orderBy('id', 'desc')
                ->get();
        }

        return view('admin.periodos.index', compact('periodos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gestiones = Gestion::orderBy('nombre', 'desc')->get();

        // Usamos el campo 'tipo' con el valor 'GLOBAL' según tu migración
        $estados = Estado::where('tipo', 'GLOBAL')
            ->orderBy('nombre', 'asc')
            ->get();

        return view('admin.periodos.create', compact('gestiones', 'estados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. VALIDACIÓN
        $request->validate([
            'nombre'       => 'required|string|max:100',
            'gestion_id'   => 'required|exists:gestions,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after:fecha_inicio', // Validación lógica de fechas
            'estado_id'    => 'required|exists:estados,id',
        ], [
            'fecha_fin.after' => 'La fecha de finalización debe ser posterior a la fecha de inicio.',
            'nombre.required' => 'El nombre del periodo es obligatorio.'
        ]);

        try {
            // 2. CREACIÓN DEL REGISTRO
            Periodo::create([
                'nombre'       => strtoupper($request->nombre), // Forzamos mayúsculas
                'gestion_id'   => $request->gestion_id,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin'    => $request->fecha_fin,
                'estado_id'    => $request->estado_id,
            ]);

            // 3. RESPUESTA AL USUARIO
            return redirect()->route('admin.periodos.index')
                ->with('swal-success', 'El periodo académico se registró correctamente.');
        } catch (\Exception $e) {
            // En caso de error (Auditoría Forense)
            return back()->withInput()
                ->with('swal-error', 'Ocurrió un error al intentar registrar el periodo.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Periodo $periodo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Periodo $periodo)
    {
        // 1. Cargamos las gestiones ordenadas por nombre (año)
        $gestiones = \App\Models\Gestion::orderBy('nombre', 'desc')->get();

        // 2. Cargamos los estados de tipo GLOBAL (Activo, Inactivo)
        $estados = \App\Models\Estado::where('tipo', 'GLOBAL')
            ->orderBy('nombre', 'asc')
            ->get();

        // 3. Retornamos la vista enviando el objeto $periodo y las colecciones
        return view('admin.periodos.edit', compact('periodo', 'gestiones', 'estados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // 1. VALIDACIÓN
        $request->validate([
            'nombre'       => 'required|string|max:100',
            'gestion_id'   => 'required|exists:gestions,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after:fecha_inicio',
            'estado_id'    => 'required|exists:estados,id',
        ], [
            'fecha_fin.after' => 'La fecha de finalización debe ser posterior a la fecha de inicio.',
        ]);

        try {
            // 2. BUSCAR Y ACTUALIZAR
            $periodo = Periodo::findOrFail($id);

            $periodo->update([
                'nombre'       => strtoupper($request->nombre),
                'gestion_id'   => $request->gestion_id,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin'    => $request->fecha_fin,
                'estado_id'    => $request->estado_id,
            ]);

            return redirect()->route('admin.periodos.index')
                ->with('swal-success', 'El periodo académico se actualizó correctamente.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('swal-error', 'Error al intentar actualizar el periodo.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Periodo $periodo)
    {
        try {
            // Ejecutamos el borrado lógico (Soft Delete)
            $periodo->delete();

            return redirect()->route('admin.periodos.index')
                ->with('swal-success', 'El periodo académico ha sido eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('admin.periodos.index')
                ->with('swal-error', 'No se pudo eliminar el periodo. Es posible que tenga registros relacionados.');
        }
    }
    public function restore($id)
    {
        try {
            $periodo = Periodo::withTrashed()->findOrFail($id);

            // Opcional: Validar si ya existe otro periodo ACTIVO con el mismo nombre
            $existeActivo = Periodo::where('nombre', $periodo->nombre)->exists();
            if ($existeActivo) {
                return redirect()->back()
                    ->with('swal-error', 'No se puede restaurar: Ya existe un periodo activo con el mismo nombre.');
            }

            $periodo->restore();

            return redirect()->route('admin.periodos.index')
                ->with('swal-success', 'El periodo académico ha sido restaurado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('admin.periodos.index')
                ->with('swal-error', 'Error al intentar restaurar el periodo.');
        }
    }
}
