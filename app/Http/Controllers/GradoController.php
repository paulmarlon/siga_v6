<?php

namespace App\Http\Controllers;

use App\Models\Grado;
use App\Models\Nivel;
use App\Models\Estado;
use Illuminate\Http\Request;

class GradoController extends Controller
{
    public function index(Request $request)
    {
        $query = Grado::with(['nivel', 'estado']);

        // Filtro para la papelera de reciclaje
        if ($request->has('papelera')) {
            $query->onlyTrashed();
        }

        // Filtro opcional por ciclo (útil para la gestión administrativa)
        if ($request->filled('ciclo')) {
            $query->where('ciclo', $request->ciclo);
        }

        $grados = $query->get();
        return view('admin.grados.index', compact('grados'));
    }

    public function create()
    {
        $niveles = Nivel::whereHas('estado', function ($q) {
            $q->where('slug', 'activo');
        })->orderBy('nombre', 'asc')->get();
        $estados = Estado::whereIn('slug', ['activo', 'inactivo'])->get();
        $siguienteOrden = Grado::max('orden') + 1;

        return view('admin.grados.create', compact('niveles', 'siguienteOrden'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:100|unique:grados,nombre',
            'orden'    => 'required|integer|min:0',
            'ciclo'    => 'required|in:1,2', // Validamos que sea 1 o 2
            'nivel_id' => 'required|exists:nivels,id',
        ]);

        try {
            $estadoActivo = Estado::where('slug', 'activo')->first();

            Grado::create([
                'nombre'    => strtoupper($request->nombre),
                'orden'     => $request->orden,
                'ciclo'     => $request->ciclo,
                'nivel_id'  => $request->nivel_id,
                'estado_id' => $estadoActivo->id ?? 1,
            ]);

            return redirect()->route('admin.grados.index')
                ->with('success', 'Grado académico registrado exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function edit(Grado $grado)
    {
        $niveles = Nivel::whereHas('estado', function ($q) {
            $q->where('slug', 'activo');
        })->get();

        // Solo Activo e Inactivo para la edición también
        $estados = Estado::whereIn('slug', ['activo', 'inactivo'])->get();

        return view('admin.grados.edit', compact('grado', 'niveles', 'estados'));
    }

    public function update(Request $request, $id)
    {
        $grado = Grado::findOrFail($id);

        $request->validate([
            'nombre'    => 'required|string|max:100|unique:grados,nombre,' . $id,
            'orden'     => 'required|integer|min:0',
            'ciclo'     => 'required|in:1,2',
            'nivel_id'  => 'required|exists:nivels,id',
            'estado_id' => 'required|exists:estados,id',
        ]);

        $grado->update([
            'nombre'    => strtoupper($request->nombre),
            'orden'     => $request->orden,
            'ciclo'     => $request->ciclo,
            'nivel_id'  => $request->nivel_id,
            'estado_id' => $request->estado_id,
        ]);

        return redirect()->route('admin.grados.index')
            ->with('info', 'El grado se actualizó correctamente.');
    }

    public function destroy(Grado $grado)
    {
        $grado->delete();
        return back()->with('success', 'Grado movido a la papelera.');
    }

    public function restore($id)
    {
        $grado = Grado::withTrashed()->findOrFail($id);
        $grado->restore();

        return redirect()->route('admin.grados.index')
            ->with('success', 'Grado restaurado correctamente.');
    }
}
