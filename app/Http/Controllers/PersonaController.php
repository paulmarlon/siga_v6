<?php

namespace App\Http\Controllers;

use App\Models\{Persona, User, Direccion};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use Exception;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $estado = $request->input('estado');

        $personas = Persona::with([
            'direccion' => fn($q) => $q->withTrashed(),
            'user',
            'estado' // <--- IMPORTANTE: Carga la relación para obtener el color y nombre
        ])
            ->when($estado == 'eliminados', fn($q) => $q->onlyTrashed())
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.personas.index', compact('personas', 'estado'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Definimos los campos que queremos recuperar de forma única
        $campos = ['pais', 'departamento', 'provincia', 'ciudad', 'zona'];
        $listas = [];

        foreach ($campos as $campo) {
            $listas[$campo] = Direccion::select($campo)
                ->whereNotNull($campo)
                ->where($campo, '!=', '') // Evitamos traer campos vacíos
                ->distinct()
                ->orderBy($campo, 'asc')
                ->pluck($campo);
        }

        // Pasamos el array de listas a la vista
        return view('admin.personas.create', compact('listas'));
    }
    // En PersonaController o un DireccionController
    public function buscarGeografia(Request $request)
    {
        $term = $request->term;
        $campo = $request->campo; // Recibe el 'name' del select

        // Lista de campos permitidos por seguridad
        $permitidos = ['pais', 'departamento', 'provincia', 'ciudad', 'zona'];

        if (!in_array($campo, $permitidos)) {
            return response()->json([]);
        }

        $resultados = Direccion::select($campo . ' as text')
            ->where($campo, 'LIKE', '%' . $term . '%')
            ->distinct()
            ->limit(10)
            ->get();

        return response()->json($resultados);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación robusta
        $request->validate([
            'ci' => ['required', 'unique:personas,ci', 'max:15', 'regex:/^[0-9]+[a-zA-Z0-9-]*$/'],
            'nombres' => 'required|string|max:100',
            'email_personal' => 'required|email|unique:personas,email_personal',
            'sexo' => 'required|in:M,F',
            'foto_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            return DB::transaction(function () use ($request) {

                // 1. CREAR DIRECCIÓN
                $direccion_id = null;

                // Verificamos si el switch está activo o si hay datos mínimos para crear la dirección
                if ($request->boolean('habilitar_direccion')) {

                    // Unificamos los campos del HTML en el campo 'detalle' de la DB
                    $detalle_completo = "CALLE/AV: " . ($request->direccion_calle ?? 'S/N') .
                        " | NRO: " . ($request->direccion_nro ?? 'S/N') .
                        " | REF: " . ($request->direccion_referencia ?? 'SIN REF.');

                    $direccion = Direccion::create([
                        'pais'         => mb_strtoupper($request->pais ?? 'BOLIVIA'),
                        'departamento' => mb_strtoupper($request->departamento ?? 'LA PAZ'),
                        'provincia'    => mb_strtoupper($request->provincia),
                        'ciudad'       => mb_strtoupper($request->ciudad),
                        'zona'         => mb_strtoupper($request->zona),
                        'detalle'      => mb_strtoupper($detalle_completo), // Guardamos todo aquí
                    ]);

                    $direccion_id = $direccion->id;
                }
                // 2. PREPARAR PERSONA
                $persona = new Persona($request->except(['foto_path', 'crear_cuenta']));
                $persona->direccion_id = $direccion_id;
                $persona->estado_id = $request->input('estado_id', 1); // <--- CORRECCIÓN ID

                // 3. FOTOGRAFÍA
                if ($request->hasFile('foto_path')) {
                    $file = $request->file('foto_path');
                    $nombreFoto = "{$request->ci}_" . time() . ".{$file->getClientOriginalExtension()}";
                    $persona->foto_path = $file->storeAs('personas/fotos', $nombreFoto, 'public');
                }

                $persona->save();

                // 4. CREAR USUARIO AUTOMÁTICO
                if ($request->boolean('crear_cuenta')) {
                    User::create([
                        'persona_id' => $persona->id,
                        'name'       => $persona->full_name, // Usamos el accesor que creamos
                        'email'      => $persona->email_personal,
                        'password'   => Hash::make($request->ci), // Más seguro que bcrypt() directo
                        'estado_id'  => 1,
                    ]);
                }

                return redirect()->route('admin.personas.index')
                    ->with('success', 'Registro maestro creado correctamente.');
            });
        } catch (\Exception $e) {
            // Si algo falla, Laravel hace Rollback automático de la DB
            return back()->withInput()->with('error', 'Fallo de sistema: ' . dd($e->getMessage()));
        }
    }
    public function activarUsuario(Persona $persona)
    {
        try {
            // 1. Verificación de seguridad: ¿Ya tiene usuario?
            if ($persona->user) {
                return back()->with('error', 'Esta persona ya tiene una cuenta de usuario activa.');
            }

            // 2. Transacción para asegurar que todo salga bien
            DB::transaction(function () use ($persona) {
                User::create([
                    'persona_id' => $persona->id,
                    'name'       => "{$persona->nombres} {$persona->ap_paterno}",
                    'email'      => $persona->email_personal,
                    'password'   => Hash::make($persona->ci), // Usamos su CI como clave inicial
                    'estado_id'  => 1, // Asumiendo que 1 es 'ACTIVO' en tu tabla estados
                ]);
            });

            return back()->with('success', "¡Acceso al sistema activado para {$persona->nombres} exitosamente!");
        } catch (QueryException $e) {
            // Error común: El email ya está siendo usado por otro usuario
            if ($e->errorInfo[1] == 1062) {
                return back()->with('error', 'Error: El correo electrónico ya está registrado en otra cuenta.');
            }
            return back()->with('error', 'Error de base de datos: ' . $e->getMessage());
        } catch (Exception $e) {
            return back()->with('error', 'Ocurrió un error inesperado: ' . $e->getMessage());
        }
    }
    public function show(Persona $persona)
    {
        // 1. Cargar relaciones necesarias (Dirección y Estado si fuera necesario)
        // Asumimos que Persona tiene una relación 'direccion' definida en el Modelo
        $persona->load('direccion');

        // 2. Desglosar el campo detalle (Calle | Nro | Referencia) para mostrarlo por separado
        $direccion_formateada = [
            'calle' => 'No registrada',
            'nro'   => 'S/N',
            'ref'   => 'Sin referencias'
        ];

        if ($persona->direccion && $persona->direccion->detalle) {
            $partes = explode(' | ', $persona->direccion->detalle);
            $direccion_formateada['calle'] = $partes[0] ?? 'No registrada';
            $direccion_formateada['nro']   = $partes[1] ?? 'S/N';
            $direccion_formateada['ref']   = $partes[2] ?? 'Sin referencias';
        }

        // 3. Retornar la vista con los datos procesados
        return view('admin.personas.show', compact('persona', 'direccion_formateada'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    // Fíjate que inyectamos el modelo "Persona" directamente
    public function edit(Persona $persona)
    {
        // 1. Desglosar el campo detalle (Calle | Nro | Referencia)
        $calle = '';
        $nro = '';
        $ref = ''; // <--- Cambiado de $referencia a $ref

        if ($persona->direccion && $persona->direccion->detalle) {
            $partes = explode(' | ', $persona->direccion->detalle);
            $calle = $partes[0] ?? '';
            $nro = $partes[1] ?? '';
            $ref = $partes[2] ?? ''; // <--- Cambiado aquí también
        }

        // 2. Preparar listas iniciales para Select2
        $campos = ['pais', 'departamento', 'provincia', 'ciudad', 'zona'];
        $listas = [];
        foreach ($campos as $campo) {
            $listas[$campo] = Direccion::select($campo)
                ->whereNotNull($campo)
                ->where($campo, '!=', '')
                ->distinct()
                ->pluck($campo);
        }

        // Ahora el compact lleva 'ref', que es lo que la vista busca
        return view('admin.personas.edit', compact('persona', 'calle', 'nro', 'ref', 'listas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $persona = Persona::findOrFail($id);

        // 1. Validación básica
        $request->validate([
            'ci' => "required|max:15|unique:personas,ci,{$persona->id}",
            'nombres' => 'required|string|max:100',
            'email_personal' => "required|email|unique:personas,email_personal,{$persona->id}",
            'celular' => 'required|max:9', // Ajustado para aceptar el guion de la máscara si es necesario
        ]);

        // 2. Lógica de Dirección Independiente
        if ($request->has('habilitar_direccion')) {
            $detalle = strtoupper("{$request->direccion_calle} | {$request->direccion_nro} | {$request->direccion_referencia}");

            $nuevaDireccion = Direccion::create([
                'pais'         => strtoupper($request->pais),
                'departamento' => strtoupper($request->departamento),
                'provincia'    => strtoupper($request->provincia),
                'ciudad'       => strtoupper($request->ciudad),
                'zona'         => strtoupper($request->zona),
                'detalle'      => $detalle,
            ]);

            $persona->direccion_id = $nuevaDireccion->id;
        } else {
            $persona->direccion_id = null;
        }

        // 3. Actualizar datos de Persona
        $persona->fill($request->except(['foto_path', 'direccion_id']));

        // 4. Gestión de Fotografía
        if ($request->hasFile('foto_path')) {
            if ($persona->foto_path) {
                Storage::disk('public')->delete($persona->foto_path);
            }
            $file = $request->file('foto_path');
            $nombreFoto = "{$request->ci}_" . time() . ".{$file->getClientOriginalExtension()}";
            $persona->foto_path = $file->storeAs('personas/fotos', $nombreFoto, 'public');
        }

        $persona->save();

        // --- 5. SINCRONIZACIÓN CON LA TABLA USERS ---
        // Buscamos el usuario vinculado a esta persona
        $usuario = \App\Models\User::where('persona_id', $persona->id)->first();

        if ($usuario) {
            // Concatenamos el nombre completo para el campo 'name' de la tabla users
            $nombreCompleto = trim("{$persona->nombres} {$persona->ap_paterno} {$persona->ap_materno}");

            $usuario->update([
                'name'  => strtoupper($nombreCompleto),
                'email' => $persona->email_personal, // Mantenemos el login sincronizado con su correo personal
            ]);
        }

        return redirect()->route('admin.personas.index')
            ->with('success', "¡Actualizado! Los datos de {$persona->nombres} y su cuenta de acceso se guardaron correctamente.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Persona $persona)
    {
        try {
            $persona->delete(); // Esto hará el Soft Delete automáticamente
            return redirect()->route('admin.personas.index')
                ->with('success', 'Registro enviado a la papelera correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo eliminar el registro.');
        }
    }
    public function restore($id)
    {
        // Buscamos el registro incluyendo eliminados
        $persona = Persona::withTrashed()->findOrFail($id);

        // Restauramos el registro (deleted_at vuelve a ser null)
        $persona->restore();

        // Redireccionamos con un mensaje de éxito institucional
        return redirect()->route('admin.personas.index')
            ->with('success', "El registro de {$persona->nombres} ha sido restaurado exitosamente.")
            ->with('icon', 'success');
    }
}
