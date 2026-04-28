<?php

namespace App\Http\Controllers;

use App\Models\Paralelo;
use App\Models\Grado;
use App\Models\Estado;
use Illuminate\Http\Request;

class ParaleloController extends Controller
{
    public function index()
    {
        $paralelos = Paralelo::orderBy('nombre', 'asc')->get();
        return view('admin.paralelos.index', compact('paralelos'));
    }
    public function create()
    {
        // Simplemente retornamos la vista del formulario
        return view('admin.paralelos.create');
    }

    // Guardar una nueva etiqueta si la universidad crea una nueva
    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|unique:paralelos,nombre|max:10']);

        Paralelo::create(['nombre' => strtoupper($request->nombre)]);

        return redirect()->route('admin.paralelos.index')->with('info', 'El paralelo se registró correctamente.');
    }

    // Para cargar el modal de edición (solo el nombre)
    public function edit($id)
    {
        $paralelo = Paralelo::findOrFail($id);
        return view('admin.paralelos.edit', compact('paralelo'));
    }

    // Actualizar el nombre (Ej: corregir "A" por "Único")
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:20|unique:paralelos,nombre,' . $id,
        ]);

        try {
            $paralelo = Paralelo::findOrFail($id);
            $paralelo->update([
                'nombre' => strtoupper($request->nombre)
            ]);

            // Redirección clásica
            return redirect()->route('admin.paralelos.index')
                ->with('info', 'Catálogo actualizado con éxito.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // Eliminar la etiqueta (Solo si no está siendo usada en una Oferta)

}
