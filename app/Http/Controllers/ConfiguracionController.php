<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Models\Persona;
use App\Models\Direccion;
use App\Models\Gestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ConfiguracionController extends Controller
{
    /**
     * Muestra el formulario de edición de la configuración institucional.
     */
    public function edit()
    {
        // 1. Buscamos la configuración única (ID 1)
        $config = Configuracion::firstOrCreate(['id' => 1], [
            'nombre_institucion' => 'Mi Institución',
            'divisa' => 'BOB'
        ]);

        // 2. Cargamos las relaciones y desglosamos la dirección si existe
        $config->load(['direccion', 'gestionActual']);

        $calle = '';
        $nro = '';
        $ref = '';

        if ($config->direccion && $config->direccion->detalle) {
            $partes = explode(' | ', $config->direccion->detalle);
            $calle = $partes[0] ?? '';
            $nro   = $partes[1] ?? '';
            $ref   = $partes[2] ?? '';
        }

        // 3. Adecuamos las "listas" para los Select2 (Igual que en Personas)
        $campos = ['pais', 'departamento', 'provincia', 'ciudad', 'zona'];
        $listas = [];

        foreach ($campos as $campo) {
            $listas[$campo] = Direccion::select($campo)
                ->whereNotNull($campo)
                ->where($campo, '!=', '')
                ->distinct()
                ->orderBy($campo, 'asc')
                ->pluck($campo);
        }

        // 4. Cargamos las gestiones para el select de gestión académica
        $gestiones = Gestion::orderBy('nombre', 'desc')->get();

        // Enviamos todo a la vista
        return view('admin.configuracion.edit', compact(
            'config',
            'calle',
            'nro',
            'ref',
            'listas',
            'gestiones'
        ));
    }

    /**
     * Actualiza los ajustes globales del sistema.
     */
    public function update(Request $request)
    {
        $request->validate([
            'nombre_institucion' => 'required|string|max:255',
            'departamento'       => 'required|string',
            'zona'               => 'required|string',
            'gestion_actual_id'  => 'required',
        ]);

        try {
            DB::beginTransaction();

            // 1. Unificamos los campos del HTML para el campo 'detalle'
            $detalle_completo = "CALLE/AV: " . ($request->direccion_calle ?? 'S/N') .
                " | NRO: " . ($request->direccion_nro ?? 'S/N') .
                " | REF: " . ($request->direccion_referencia ?? 'SIN REF.');

            // 2. Crear la dirección (o usar firstOrCreate si prefieres evitar duplicados)
            $direccion = Direccion::create([
                'pais'         => mb_strtoupper($request->pais ?? 'BOLIVIA'),
                'departamento' => mb_strtoupper($request->departamento),
                'provincia'    => mb_strtoupper($request->provincia),
                'ciudad'       => mb_strtoupper($request->ciudad),
                'zona'         => mb_strtoupper($request->zona),
                'detalle'      => mb_strtoupper($detalle_completo),
            ]);

            $config = Configuracion::first();

            // Excluimos los campos que no pertenecen a la tabla 'configuracions'
            $data = $request->except([
                'logo_path',
                'pais',
                'departamento',
                'provincia',
                'ciudad',
                'zona',
                'direccion_calle',
                'direccion_nro',
                'direccion_referencia'
            ]);

            $data['direccion_id'] = $direccion->id;

            if ($request->hasFile('logo_path')) {
                if ($config->logo_path) {
                    Storage::disk('public')->delete($config->logo_path);
                }
                $data['logo_path'] = $request->file('logo_path')->store('logos', 'public');
            }

            $config->update($data);

            // 3. Limpieza de direcciones huérfanas
            Direccion::whereDoesntHave('personas')
                ->whereDoesntHave('configuracion')
                ->forceDelete();

            DB::commit();
            return back()->with('success', 'Configuración institucional actualizada.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
