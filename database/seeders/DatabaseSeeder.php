<?php

namespace Database\Seeders;

use App\Models\{User, Persona, Estado, Nivel, Carrera, Grado, Paralelo, Pensum, Materia};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();


        // Primero creamos la persona "Raíz"
        $persona = Persona::create([
            'ci' => '7539518520',
            'nombres' => 'PAUL',
            'ap_paterno' => 'QUISPE',
            'email_personal' => 'paul@adhara.tech',
            'sexo' => 'M',
            'estado_id' => 1,
        ]);

        // Luego creamos el usuario vinculado
        User::create([
            'persona_id' => $persona->id, // <--- ESTO ES LO QUE FALTA
            'name'       => 'Paul Quispe',
            'email'      => 'paul@adhara.tech',
            'password'   => Hash::make('7539518520'),
            'estado_id'  => 1,
        ]);
        $nivelLic = Nivel::updateOrCreate(
            ['nombre' => 'LICENCIATURA'], // Búsqueda
            [
                'nombre' => 'LICENCIATURA',
                'slug'   => Str::slug('LICENCIATURA') // Esto insertará "licenciatura"
            ]
        );
        $licenciatura = $nivelLic->id;

        // 1. Obtener IDs básicos
        $estadoActivo = Estado::where('slug', 'activo')->first()->id;
        $licenciatura = Nivel::where('nombre', 'LIKE', '%LICENCIATURA%')->first()->id;
        // 2. CREAR TRONCO COMÚN (Padre)
        // Por ejemplo: Un área común de Ingeniería
        $CarreraBase = Carrera::create([
            'nombre'           => 'BASE LICENCIATURA EN CIENCIAS POLICIALES',
            'sigla'            => 'BAS-POL',
            'resolucion'       => 'R.M. 001/2026',
            'duracion'         => 2, // 4 semestres básicos
            'titulo'           => 'CERTIFICADO',
            'nivel_id'         => $licenciatura,
            'es_tronco_comun'  => true,
            'estado_id'        => $estadoActivo,
        ]);

        Carrera::create([
            'carrera_base_id'  => $CarreraBase->id,
            'nombre'           => 'INGENIERIA DE TRANSITO Y VIALIDAD',
            'sigla'            => 'ITV-POL',
            'resolucion'       => 'R.M. 002/2026',
            'duracion'         => 6,
            'titulo'           => 'LICENCIATURA EN INGENIERIA DE TRANSITO Y VIALIDAD',
            'nivel_id'         => $licenciatura,
            'es_tronco_comun'  => false,
            'estado_id'        => $estadoActivo,
        ]);
        Carrera::create([
            'carrera_base_id'  => $CarreraBase->id,
            'nombre'           => 'INVESTIGACION CRIMINAL',
            'sigla'            => 'LIC-POL',
            'resolucion'       => 'R.M. 046/2026',
            'duracion'         => 6,
            'titulo'           => 'LICENCIATURA EN INVESTIGACION CRIMINAL',
            'nivel_id'         => $licenciatura,
            'es_tronco_comun'  => false,
            'estado_id'        => $estadoActivo,
        ]);
        $semestres = [
            1 => 'PRIMER SEMESTRE',
            2 => 'SEGUNDO SEMESTRE',
            3 => 'TERCER SEMESTRE',
            4 => 'CUARTO SEMESTRE',
            5 => 'QUINTO SEMESTRE',
            6 => 'SEXTO SEMESTRE',
            7 => 'SÉPTIMO SEMESTRE',
            8 => 'OCTAVO SEMESTRE',
        ];

        // 3. Insertar con lógica de Ciclos
        foreach ($semestres as $orden => $nombre) {
            /**
             * Lógica: 
             * Semestres 1 y 2 -> Ciclo 1 (Tronco Común)
             * Semestres 3 al 8 -> Ciclo 2 (Especialidad)
             */
            $ciclo = ($orden <= 2) ? 1 : 2;

            Grado::updateOrCreate(
                ['nombre' => $nombre, 'nivel_id' => $licenciatura], // Búsqueda para evitar duplicados
                [
                    'orden'     => $orden,
                    'ciclo'     => $ciclo,
                    'estado_id' => $estadoActivo,
                ]
            );
        }
    }
}
