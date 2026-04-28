<?php

namespace App\Http\Controllers;

use App\Models\{Nivel, Estado};
use Illuminate\Http\Request;
use Exception;

class NivelController extends Controller
{

    public function index(Request $request)
    {
        // Optimizamos la carga de relaciones para evitar el problema N+1
        $query = Nivel::with('estado');

        if ($request->has('papelera')) {
            $niveles = $query->onlyTrashed()->get();
        } else {
            $niveles = $query->get();
        }

        return view('admin.niveles.index', compact('niveles'));
    }

    public function create()
    {
        $estados = Estado::where('tipo', 'GLOBAL')->get();
        return view('admin.niveles.create', compact('estados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:nivels,nombre|max:50', // Corregido: niveles
            'estado_id' => 'required|exists:estados,id',
        ], [
            'nombre.unique' => 'Este nivel académico ya se encuentra registrado.',
            'nombre.required' => 'El nombre del nivel es obligatorio.',
            'estado_id.exists' => 'El estado seleccionado no es válido.'
        ]);

        try {
            Nivel::create([
                'nombre' => mb_convert_case(trim($request->nombre), MB_CASE_UPPER, "UTF-8"),
                'estado_id' => $request->estado_id,
            ]);

            return redirect()->route('admin.niveles.index')
                ->with('success', '¡Nivel académico creado con éxito!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()
                ->with('swal-error', 'Error al registrar: ' . $e->getMessage());
        }
    }

    public function edit(Nivel $nivel)
    {
        $estados = Estado::where('tipo', 'GLOBAL')->get();
        return view('admin.niveles.edit', compact('nivel', 'estados'));
    }

    public function update(Request $request, Nivel $nivel)
    {
        $request->validate([
            'nombre' => 'required|max:50|unique:nivels,nombre,' . $nivel->id, // Corregido: niveles
            'estado_id' => 'required|exists:estados,id',
        ], [
            'nombre.unique' => 'Este nivel académico ya pertenece a otro registro.',
            'nombre.required' => 'El nombre del nivel es obligatorio.'
        ]);

        try {
            $nivel->update([
                'nombre' => mb_convert_case(trim($request->nombre), MB_CASE_UPPER, "UTF-8"),
                'estado_id' => $request->estado_id,
            ]);

            return redirect()->route('admin.niveles.index')
                ->with('success', '¡El nivel se actualizó correctamente!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()
                ->with('swal-error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function destroy(Nivel $nivel)
    {
        try {
            // Aquí podrías añadir la validación de dependencias antes de borrar
            $nivel->delete();

            return redirect()->route('admin.niveles.index')
                ->with('swal-success', 'El nivel académico ha sido movido a la papelera.');
        } catch (Exception $e) {
            return redirect()->route('admin.niveles.index')
                ->with('swal-error', 'Error al eliminar el registro.');
        }
    }

    public function restore($id)
    {
        try {
            $nivel = Nivel::withTrashed()->findOrFail($id);
            $nivel->restore();

            return redirect()->route('admin.niveles.index')
                ->with('swal-success', 'Nivel académico restaurado con éxito.');
        } catch (Exception $e) {
            return redirect()->route('admin.niveles.index')
                ->with('swal-error', 'No se pudo restaurar el registro.');
        }
    }
}
