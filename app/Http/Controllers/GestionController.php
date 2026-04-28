<?php

namespace App\Http\Controllers;

use App\Models\{Gestion, Estado};
use Illuminate\Http\Request;

class GestionController extends Controller
{
    /**
     * Muestra la lista de gestiones con su estado.
     */
    public function index()
    {
        // Traemos las normales Y las que tienen Soft Delete
        $gestiones = Gestion::withTrashed()->with('estado')->get();

        return view('admin.gestiones.index', compact('gestiones'));
    }

    /**
     * Formulario de creación con estados de tipo GLOBAL.
     */
    public function create()
    {
        $estados = Estado::where('tipo', 'GLOBAL')->get();
        return view('admin.gestiones.create', compact('estados'));
    }

    /**
     * Registra una nueva gestión anual.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|integer|digits:4|unique:gestions,nombre',
        ]);

        try {
            // 1. Contamos cuántas gestiones existen
            $hayGestiones = Gestion::count();

            // 2. Buscamos los IDs de los estados
            $estadoActivo = Estado::where('slug', 'activo')->first()->id;
            $estadoInactivo = Estado::where('slug', 'inactivo')->first()->id;

            // 3. Lógica: Si es la primera gestión, que sea ACTIVA. 
            //    Si ya hay otras, que nazca como INACTIVA para no chocar.
            $gestion = Gestion::create([
                'nombre'    => $request->nombre,
                'estado_id' => ($hayGestiones == 0) ? $estadoActivo : $estadoInactivo,
            ]);

            return redirect()->route('admin.gestiones.index')
                ->with('mensaje', 'Gestión ' . $gestion->nombre . ' registrada con éxito.')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            return redirect()->back()->with('mensaje', 'Error: ' . $e->getMessage())->with('icono', 'error');
        }
    }

    /**
     * Lógica de Activación Única:
     * Al activar una gestión, todas las demás se marcan como inactivas.
     * Esto permite "abrir" años pasados para ajustes administrativos fácilmente.
     */
    public function activar($id)
    {
        $activo = Estado::where('slug', 'activo')->first();
        $inactivo = Estado::where('slug', 'inactivo')->first();

        if (!$activo || !$inactivo) {
            return redirect()->back()->with(['mensaje' => 'Estados no configurados.', 'icono' => 'error']);
        }

        // 1. Cerramos todas las gestiones
        Gestion::query()->update(['estado_id' => $inactivo->id]);

        // 2. Activamos la gestión seleccionada
        $gestion = Gestion::findOrFail($id);
        $gestion->estado_id = $activo->id;
        $gestion->save();

        return redirect()->route('admin.gestiones.index')
            ->with('mensaje', "La gestión {$gestion->nombre} se ha establecido como ACTIVA.")
            ->with('icono', 'success');
    }

    public function edit(Gestion $gestion)
    {
        $estados = Estado::where('tipo', 'GLOBAL')->get();
        return view('admin.gestiones.edit', compact('gestion', 'estados'));
    }

    public function update(Request $request, Gestion $gestion)
    {
        $request->validate([
            // Validamos que el año sea único, ignorando el ID actual
            'nombre' => 'required|integer|digits:4|unique:gestions,nombre,' . $gestion->id,
        ]);

        // Solo actualizamos el nombre para no tocar el estado accidentalmente
        $gestion->update([
            'nombre' => $request->nombre
        ]);

        return redirect()->route('admin.gestiones.index')
            ->with('mensaje', 'Gestión actualizada correctamente.')
            ->with('icono', 'success');
    }

    public function destroy(Gestion $gestion)
    {
        // Solo protegemos que no borren la vigente
        if ($gestion->estado->slug == 'activo') {
            return redirect()->back()->with([
                'mensaje' => 'No es posible eliminar la gestión VIGENTE.',
                'icono' => 'warning'
            ]);
        }

        // Al tener SoftDeletes en el modelo, esto no borra el registro, 
        // solo llena la columna deleted_at.
        $gestion->delete();

        return redirect()->route('admin.gestiones.index')->with([
            'mensaje' => 'Gestión enviada a la papelera (Inactiva).',
            'icono' => 'success'
        ]);
    }
    public function restore($id)
    {
        // Buscamos solo en los registros que fueron eliminados (Soft Deleted)
        $gestion = Gestion::onlyTrashed()->findOrFail($id);

        // Restauramos el registro
        $gestion->restore();

        return redirect()->route('admin.gestiones.index')
            ->with('mensaje', 'Gestión restaurada correctamente.')
            ->with('icono', 'success');
    }
}
