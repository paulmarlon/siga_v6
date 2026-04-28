@extends('adminlte::page')

@section('title', 'Nuevo Periodo | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
        <h1 class="text-dark" style="font-weight: 400; font-size: 1.5rem;">
            <i class="fas fa-plus-circle text-primary mr-2"></i> Registrar Nuevo Periodo Académico
        </h1>
        <a href="{{ route('admin.periodos.index') }}" class="btn btn-default btn-sm btn-flat shadow-sm border">
            <i class="fas fa-arrow-left mr-1"></i> VOLVER AL LISTADO
        </a>
    </div>
@stop

@section('content')
    <div class="row justify-content-center pt-3">
        <div class="col-md-10"> {{-- Ampliado a 10 para acomodar mejor las fechas --}}
            <div class="card card-flat shadow-none border">
                <div class="card-header bg-light py-3">
                    <h3 class="card-title text-secondary" style="font-size: 0.9rem; font-weight: 700;">
                        <i class="fas fa-database mr-2"></i> CONFIGURACIÓN TEMPORAL DEL PERIODO
                    </h3>
                </div>

                <form action="{{ route('admin.periodos.store') }}" method="POST" id="form-create">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            {{-- Nombre del Periodo --}}
                            <div class="col-md-8 form-group">
                                <label for="nombre">Nombre del Periodo <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white"><i class="fas fa-calendar-tag"></i></span>
                                    </div>
                                    <input type="text" name="nombre" id="nombre"
                                        class="form-control @error('nombre') is-invalid @enderror"
                                        value="{{ old('nombre') }}"
                                        placeholder="Ej. PRIMER SEMESTRE, INVIERNO, CURSO DE VERANO..." required
                                        maxlength="100" autofocus>
                                    @error('nombre')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Gestión Relacionada --}}
                            <div class="col-md-4 form-group">
                                <label for="gestion_id">Gestión Académica <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <select name="gestion_id" id="gestion_id"
                                        class="form-control @error('gestion_id') is-invalid @enderror" required>
                                        <option value="" disabled selected>-- SELECCIONE GESTIÓN --</option>
                                        @foreach ($gestiones as $gestion)
                                            <option value="{{ $gestion->id }}"
                                                {{ old('gestion_id') == $gestion->id ? 'selected' : '' }}>
                                                {{-- CAMBIO AQUÍ: Usamos 'nombre' que es donde guardas el año --}}
                                                {{ $gestion->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('gestion_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            {{-- Fecha de Inicio --}}
                            <div class="col-md-4 form-group">
                                <label for="fecha_inicio">Fecha de Inicio <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white"><i
                                                class="fas fa-play-circle text-success"></i></span>
                                    </div>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio"
                                        class="form-control @error('fecha_inicio') is-invalid @enderror"
                                        value="{{ old('fecha_inicio') }}" required>
                                    @error('fecha_inicio')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Fecha de Fin --}}
                            <div class="col-md-4 form-group">
                                <label for="fecha_fin">Fecha de Finalización <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white"><i
                                                class="fas fa-stop-circle text-danger"></i></span>
                                    </div>
                                    <input type="date" name="fecha_fin" id="fecha_fin"
                                        class="form-control @error('fecha_fin') is-invalid @enderror"
                                        value="{{ old('fecha_fin') }}" required>
                                    @error('fecha_fin')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Estado (Solo Globales) --}}
                            <div class="col-md-4 form-group">
                                <label for="estado_id">Estado Inicial <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white"><i class="fas fa-toggle-on"></i></span>
                                    </div>
                                    <select name="estado_id" id="estado_id"
                                        class="form-control @error('estado_id') is-invalid @enderror" required>
                                        @foreach ($estados as $estado)
                                            <option value="{{ $estado->id }}"
                                                {{ old('estado_id', 1) == $estado->id ? 'selected' : '' }}>
                                                {{ strtoupper($estado->nombre) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('estado_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-4 border-0 shadow-sm"
                            style="border-radius: 0; background-color: #f0f7ff;">
                            <p class="mb-0" style="font-size: 0.85rem; color: #004085;">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Validación de Integridad:</strong> El sistema no permitirá que la fecha de
                                finalización sea anterior a la de inicio.
                                Los periodos registrados estarán disponibles para la programación de materias y procesos de
                                inscripción.
                            </p>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top py-3 text-right">
                        <button type="reset" class="btn btn-sm btn-flat btn-light border mr-2">
                            <i class="fas fa-eraser mr-1"></i> LIMPIAR
                        </button>
                        <button type="submit" class="btn btn-sm btn-flat btn-primary px-4"
                            style="background-color: #003366; border-color: #003366;">
                            <i class="fas fa-save mr-1"></i> GUARDAR PERIODO
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .card-flat {
            border-radius: 0;
        }

        .btn-flat {
            border-radius: 0 !important;
        }

        label {
            font-size: 0.72rem;
            color: #555;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .form-control {
            border-radius: 0;
            font-size: 0.9rem;
        }

        .input-group-text {
            border-radius: 0;
            border-right: none;
            color: #6c757d;
            font-size: 0.8rem;
        }

        .form-control:focus {
            border-color: #003366;
            box-shadow: none;
        }
    </style>
@stop
