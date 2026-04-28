<?php

namespace App\Http\Controllers;

use App\Models\Direccion;
use Illuminate\Http\Request;

class DireccionController extends Controller
{
    public function index(Request $request)
    {
        $estado = $request->query('estado', 'activos'); // Por defecto muestra activos

        // Usamos query() para empezar a construir la consulta
        $query = Direccion::query();

        if ($estado == 'eliminados') {
            // Solo registros con deleted_at != null
            $direcciones = $query->onlyTrashed()->get();
        } else {
            // Registros activos (por defecto SoftDelete filtra los eliminados)
            $direcciones = $query->get();
        }

        return view('admin.direcciones.index', compact('direcciones'));
    }
    public function create()
    {
        // Obtenemos solo los nombres únicos de países y departamentos
        $paises = Direccion::distinct()->pluck('pais')->filter()->toArray();
        $departamentos = Direccion::distinct()->pluck('departamento')->filter()->toArray();

        // El resto de tu lógica para cargar direcciones existentes
        $direcciones = Direccion::all();

        return view('admin.personas.create', compact('direcciones', 'paises', 'departamentos'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pais'         => 'nullable|string|max:50',
            'departamento' => 'required|string|max:30',
            'provincia'    => 'nullable|string|max:100',
            'ciudad'       => 'nullable|string|max:100',
            'zona'         => 'nullable|string|max:100',
            'detalle'      => 'nullable|string',
        ]);

        try {
            $direccion = Direccion::create($validated);

            // ESTA ES LA CLAVE PARA EL AJAX
            if ($request->ajax()) {
                return response()->json([
                    'id'           => $direccion->id,
                    'departamento' => $direccion->departamento,
                    'zona'         => $direccion->zona,
                ]);
            }

            // Flujo normal si no es AJAX
            return redirect()->route('admin.direcciones.index')->with('mensaje', 'Registrado');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }
    public function edit(Direccion $direccion)
    {
        return view('admin.direcciones.edit', compact('direccion'));
    }
    public function update(Request $request, Direccion $direccion)
    {
        $validated = $request->validate([
            'pais'         => 'nullable|string|max:50',
            'departamento' => 'nullable|string|max:30',
            'provincia'    => 'nullable|string|max:50',
            'ciudad'       => 'nullable|string|max:100',
            'zona'         => 'nullable|string|max:100',
            'detalle'      => 'nullable|string',
        ]);
        $direccion->update($validated);
        return redirect()->route('admin.direcciones.index')
            ->with('success', 'Dirección actualizada con éxito.');
    }
    // En DireccionController.php
    public function destroy(Direccion $direccion)
    {
        try {
            $direccion->delete(); // SoftDelete

            return redirect()->route('admin.direcciones.index')->with([
                'mensaje' => 'El registro ha sido eliminado (lógicamente) del sistema.',
                'icono'   => 'success' // Esto activará el Toast verde
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.direcciones.index')->with([
                'mensaje' => 'Error al intentar eliminar el registro.',
                'icono'   => 'error' // Esto activará el Toast rojo
            ]);
        }
    }
    public function restore($id)
    {
        $direccion = Direccion::withTrashed()->findOrFail($id);
        $direccion->restore();

        return redirect()->route('admin.direcciones.index')
            ->with(['mensaje' => 'Registro restaurado correctamente', 'icono' => 'success']);
    }
}
