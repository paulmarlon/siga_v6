@extends('adminlte::page')

@section('title', 'Editar Periodo | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
        <h1 class="text-dark" style="font-weight: 400; font-size: 1.5rem;">
            <i class="fas fa-edit text-success mr-2"></i> Actualizar Periodo Académico
        </h1>
        <a href="{{ route('admin.periodos.index') }}" class="btn btn-default btn-sm btn-flat shadow-sm border">
            <i class="fas fa-arrow-left mr-1"></i> VOLVER AL LISTADO
        </a>
    </div>
@stop

@section('content')
    <div class="row justify-content-center pt-3">
        <div class="col-md-10">
            <div class="card card-flat shadow-none border">
                <div class="card-header bg-light py-3">
                    <h3 class="card-title text-secondary" style="font-size: 0.9rem; font-weight: 700;">
                        <i class="fas fa-database mr-2"></i> MODIFICAR CONFIGURACIÓN DEL PERIODO: <span
                            class="text-success">{{ $periodo->nombre }}</span>
                    </h3>
                </div>

                <form action="{{ route('admin.periodos.update', $periodo->id) }}" method="POST" id="form-edit">
                    @csrf
                    @method('PUT') {{-- ¡Crucial para el update! --}}

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
                                        value="{{ old('nombre', $periodo->nombre) }}" required maxlength="100" autofocus>
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
                                        @foreach ($gestiones as $gestion)
                                            <option value="{{ $gestion->id }}"
                                                {{ old('gestion_id', $periodo->gestion_id) == $gestion->id ? 'selected' : '' }}>
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
                            {{-- Fecha de Inicio --}}
                            <div class="col-md-4 form-group">
                                <label for="fecha_inicio">Fecha de Inicio <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white">
                                            <i class="fas fa-play-circle text-success"></i>
                                        </span>
                                    </div>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio"
                                        class="form-control @error('fecha_inicio') is-invalid @enderror"
                                        {{-- Forzamos el formato año-mes-día --}}
                                        value="{{ old('fecha_inicio', $periodo->fecha_inicio ? $periodo->fecha_inicio->format('Y-m-d') : '') }}"
                                        required>
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
                                        <span class="input-group-text bg-white">
                                            <i class="fas fa-stop-circle text-danger"></i>
                                        </span>
                                    </div>
                                    <input type="date" name="fecha_fin" id="fecha_fin"
                                        class="form-control @error('fecha_fin') is-invalid @enderror" {{-- Forzamos el formato año-mes-día --}}
                                        value="{{ old('fecha_fin', $periodo->fecha_fin ? $periodo->fecha_fin->format('Y-m-d') : '') }}"
                                        required>
                                    @error('fecha_fin')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Estado --}}
                            <div class="col-md-4 form-group">
                                <label for="estado_id">Estado Actual <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white"><i class="fas fa-toggle-on"></i></span>
                                    </div>
                                    <select name="estado_id" id="estado_id"
                                        class="form-control @error('estado_id') is-invalid @enderror" required>
                                        @foreach ($estados as $estado)
                                            <option value="{{ $estado->id }}"
                                                {{ old('estado_id', $periodo->estado_id) == $estado->id ? 'selected' : '' }}>
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
                    </div>

                    <div class="card-footer bg-white border-top py-3 text-right">
                        <button type="submit" class="btn btn-sm btn-flat btn-success px-4 shadow-sm"
                            style="background-color: #1e4d2b; border-color: #1a4325;">
                            <i class="fas fa-sync-alt mr-1"></i> ACTUALIZAR PERIODO
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
            border-color: #1e4d2b;
            box-shadow: none;
        }
    </style>
@stop
