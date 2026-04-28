<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use Illuminate\Http\Request;

class TurnoController extends Controller
{
    public function index(Request $request)
    {
        $turnos = $request->has('papelera')
            ? Turno::onlyTrashed()->get()
            : Turno::all();
        return view('admin.turnos.index', compact('turnos'));
    }

    public function create()
    {
        return view('admin.turnos.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|unique:turnos,nombre|max:50']);
        Turno::create($request->all());
        return redirect()->route('admin.turnos.index')->with('swal-success', 'Turno creado.');
    }

    public function edit(Turno $turno)
    {
        return view('admin.turnos.edit', compact('turno'));
    }

    public function update(Request $request, Turno $turno)
    {
        $request->validate(['nombre' => 'required|max:50|unique:turnos,nombre,' . $turno->id]);
        $turno->update($request->all());
        return redirect()->route('admin.turnos.index')->with('swal-success', 'Turno actualizado.');
    }

    public function destroy(Turno $turno)
    {
        $turno->delete();
        return redirect()->route('admin.turnos.index')->with('swal-success', 'Movido a papelera.');
    }

    public function restore($id)
    {
        Turno::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.turnos.index')->with('swal-success', 'Turno restaurado.');
    }
}
