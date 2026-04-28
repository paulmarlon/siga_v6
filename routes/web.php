<?php

use App\Http\Controllers\{
    DireccionController,
    PersonaController,
    GestionController,
    NivelController,
    HomeController,
    TurnoController,
    GradoController,
    CarreraController,
    ConfiguracionController,
    MateriaController,
    ParaleloController,
    PensumController,
    PeriodoController
};
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Ruta de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// Autenticación (Solo una vez)
Auth::routes();

// Home (Dashboard)
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Grupo de administración con Middleware Auth para evitar repetir código
Route::middleware('auth')->prefix('admin')->name('admin.')->group(
    function () {

        // --- RUTAS PARA DIRECCIONES ---
        Route::get('direcciones', [DireccionController::class, 'index'])->name('direcciones.index');
        Route::get('direcciones/create', [DireccionController::class, 'create'])->name('direcciones.create');
        Route::post('direcciones', [DireccionController::class, 'store'])->name('direcciones.store');
        Route::post('direcciones/{id}/restore', [DireccionController::class, 'restore'])->name('direcciones.restore'); // Restore antes que dinámicas
        Route::get('direcciones/{direccion}/edit', [DireccionController::class, 'edit'])->name('direcciones.edit');
        Route::put('direcciones/{direccion}', [DireccionController::class, 'update'])->name('direcciones.update');
        Route::delete('direcciones/{direccion}', [DireccionController::class, 'destroy'])->name('direcciones.destroy');

        // --- RUTAS PARA PERSONAS ---
        Route::get('personas/buscar-geografia', [PersonaController::class, 'buscarGeografia'])->name('personas.buscar_geografia');
        Route::post('personas/{persona}/activar-usuario', [PersonaController::class, 'activarUsuario'])->name('personas.activar-usuario');
        Route::get('personas', [PersonaController::class, 'index'])->name('personas.index');
        Route::get('personas/create', [PersonaController::class, 'create'])->name('personas.create');
        Route::post('personas', [PersonaController::class, 'store'])->name('personas.store');
        Route::get('personas/{persona}', [PersonaController::class, 'show'])->name('personas.show');
        Route::post('personas/{id}/restore', [PersonaController::class, 'restore'])->name('personas.restore'); // Restore antes que dinámicas
        Route::get('personas/{persona}/edit', [PersonaController::class, 'edit'])->name('personas.edit');
        Route::put('personas/{persona}', [PersonaController::class, 'update'])->name('personas.update');
        Route::delete('personas/{persona}', [PersonaController::class, 'destroy'])->name('personas.destroy');

        // --- RUTAS PARA GESTIONES ---
        Route::get('gestiones', [GestionController::class, 'index'])->name('gestiones.index');
        Route::get('gestiones/create', [GestionController::class, 'create'])->name('gestiones.create');
        Route::post('gestiones', [GestionController::class, 'store'])->name('gestiones.store');
        Route::post('gestiones/{id}/restore', [GestionController::class, 'restore'])->name('gestiones.restore'); // Restore antes que dinámicas
        Route::post('gestiones/{gestion}/activar', [GestionController::class, 'activar'])->name('gestiones.activar');
        Route::get('gestiones/{gestion}/edit', [GestionController::class, 'edit'])->name('gestiones.edit');
        Route::put('gestiones/{gestion}', [GestionController::class, 'update'])->name('gestiones.update');
        Route::delete('gestiones/{gestion}', [GestionController::class, 'destroy'])->name('gestiones.destroy');

        // --- RUTAS PARA NIVELES ---
        Route::get('niveles', [NivelController::class, 'index'])->name('niveles.index');
        Route::get('niveles/create', [NivelController::class, 'create'])->name('niveles.create');
        Route::post('niveles', [NivelController::class, 'store'])->name('niveles.store');
        Route::post('niveles/{id}/restore', [NivelController::class, 'restore'])->name('niveles.restore'); // Restore antes que dinámicas
        Route::get('niveles/{nivel}/edit', [NivelController::class, 'edit'])->name('niveles.edit');
        Route::put('niveles/{nivel}', [NivelController::class, 'update'])->name('niveles.update');
        Route::delete('niveles/{nivel}', [NivelController::class, 'destroy'])->name('niveles.destroy');
        // --- RUTAS PARA LOS TURNO ---
        Route::get('turnos', [TurnoController::class, 'index'])->name('turnos.index');
        Route::get('turnos/create', [TurnoController::class, 'create'])->name('turnos.create');
        Route::post('turnos', [TurnoController::class, 'store'])->name('turnos.store');
        Route::post('turnos/{id}/restore', [TurnoController::class, 'restore'])->name('turnos.restore'); // Restore antes que dinámicas
        Route::get('turnos/{turno}/edit', [TurnoController::class, 'edit'])->name('turnos.edit');
        Route::put('turnos/{turno}', [TurnoController::class, 'update'])->name('turnos.update');
        Route::delete('turnos/{turno}', [TurnoController::class, 'destroy'])->name('turnos.destroy');
        // --- RUTAS PARA LOS GRADOS ---
        Route::get('grados', [GradoController::class, 'index'])->name('grados.index');
        Route::get('grados/create', [GradoController::class, 'create'])->name('grados.create');
        Route::post('grados', [GradoController::class, 'store'])->name('grados.store');
        Route::post('grados/{id}/restore', [GradoController::class, 'restore'])->name('grados.restore');
        Route::get('grados/{grado}/edit', [GradoController::class, 'edit'])->name('grados.edit');
        Route::put('grados/{grado}', [GradoController::class, 'update'])->name('grados.update');
        Route::delete('grados/{grado}', [GradoController::class, 'destroy'])->name('grados.destroy');
        // --- RUTAS PARA CONFIGURACIÓN ---

        Route::get('configuracion', [ConfiguracionController::class, 'edit'])->name('configuracion.edit');
        Route::put('configuracion', [ConfiguracionController::class, 'update'])->name('configuracion.update');

        // --- RUTAS PARA LAS CARRERAS ---
        Route::get('carreras', [CarreraController::class, 'index'])->name('carreras.index');
        Route::get('carreras/create', [CarreraController::class, 'create'])->name('carreras.create');
        Route::post('carreras', [CarreraController::class, 'store'])->name('carreras.store');
        Route::post('carreras/{id}/restore', [CarreraController::class, 'restore'])->name('carreras.restore'); // Restore antes que dinámicas
        Route::get('carreras/{carrera}/edit', [CarreraController::class, 'edit'])->name('carreras.edit');
        Route::put('carreras/{carrera}', [CarreraController::class, 'update'])->name('carreras.update');
        Route::delete('carreras/{carrera}', [CarreraController::class, 'destroy'])->name('carreras.destroy');
        //rutas para las materias
        Route::post('materias/{id}/restore', [MateriaController::class, 'restore'])->name('admin.materias.restore');
        Route::delete('materias/{id}/force-delete', [MateriaController::class, 'forceDelete'])->name('admin.materias.force-delete');
        Route::get('materias', [MateriaController::class, 'index'])->name('materias.index');
        Route::get('materias/create', [MateriaController::class, 'create'])->name('materias.create');
        Route::post('materias', [MateriaController::class, 'store'])->name('materias.store');
        Route::post('materias/{id}/restore', [MateriaController::class, 'restore'])->name('materias.restore'); // Restore antes que dinámicas
        Route::get('materias/{materia}/edit', [MateriaController::class, 'edit'])->name('materias.edit');
        Route::put('materias/{materia}', [MateriaController::class, 'update'])->name('materias.update');
        Route::delete('materias/{materia}', [MateriaController::class, 'destroy'])->name('materias.destroy');
        // --- RUTAS PARA LOS PARALELOS ---
        Route::get('paralelos', [ParaleloController::class, 'index'])->name('paralelos.index');
        Route::get('paralelos/create', [ParaleloController::class, 'create'])->name('paralelos.create');
        Route::post('paralelos', [ParaleloController::class, 'store'])->name('paralelos.store');
        Route::post('paralelos/{id}/restore', [ParaleloController::class, 'restore'])->name('paralelos.restore'); // Restore antes que dinámicas
        Route::delete('paralelos/{id}/force-delete', [ParaleloController::class, 'forceDelete'])->name('paralelos.force-delete');
        Route::get('paralelos/{paralelo}/edit', [ParaleloController::class, 'edit'])->name('paralelos.edit');
        Route::put('paralelos/{paralelo}', [ParaleloController::class, 'update'])->name('paralelos.update');
        Route::delete('paralelos/{paralelo}', [ParaleloController::class, 'destroy'])->name('paralelos.destroy');
        // rutas para los pensums
        Route::post('pensums/update-grado', [PensumController::class, 'updateGrado'])->name('pensums.update-grado');
        Route::get('pensums', [PensumController::class, 'index'])->name('pensums.index');
        Route::get('pensums/create', [PensumController::class, 'create'])->name('pensums.create');
        Route::post('pensums', [PensumController::class, 'store'])->name('pensums.store');
        Route::post('pensums/{id}/restore', [PensumController::class, 'restore'])->name('pensums.restore'); // Restore antes que dinámicas
        Route::delete('pensums/{id}/force-delete', [PensumController::class, 'forceDelete'])->name('pensums.force-delete');
        Route::get('pensums/{pensum}/edit', [PensumController::class, 'edit'])->name('pensums.edit');
        Route::put('pensums/{pensum}', [PensumController::class, 'update'])->name('pensums.update');
        Route::delete('pensums/{pensum}', [PensumController::class, 'destroy'])->name('pensums.destroy');
        // rutas para los periodos
        Route::get('periodos', [PeriodoController::class, 'index'])->name('periodos.index');
        Route::get('periodos/create', [PeriodoController::class, 'create'])->name('periodos.create');
        Route::post('periodos', [PeriodoController::class, 'store'])->name('periodos.store');

        Route::post('periodos/{id}/restore', [PeriodoController::class, 'restore'])->name('periodos.restore'); // Restore antes que dinámicas

        Route::get('periodos/{periodo}/edit', [PeriodoController::class, 'edit'])->name('periodos.edit');
        Route::put('periodos/{periodo}', [PeriodoController::class, 'update'])->name('periodos.update');
        Route::delete('periodos/{periodo}', [PeriodoController::class, 'destroy'])->name('periodos.destroy');
    }
);
