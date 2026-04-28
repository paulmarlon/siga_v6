@extends('adminlte::page')

@section('title', 'Editar Gestión | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
        <h1 class="text-dark" style="font-weight: 400; font-size: 1.5rem;">
            <i class="fas fa-edit text-secondary mr-2"></i> Modificar Gestión Anual
        </h1>
        <a href="{{ route('admin.gestiones.index') }}" class="btn btn-default btn-sm btn-flat">
            <i class="fas fa-arrow-left mr-1"></i> VOLVER AL LISTADO
        </a>
    </div>
@stop

@section('content')
    <div class="row justify-content-center pt-3">
        <div class="col-md-8">
            <div class="card card-flat shadow-none border">
                <div class="card-header bg-light py-3">
                    <h3 class="card-title text-secondary" style="font-size: 0.9rem; font-weight: 700;">
                        <i class="fas fa-database mr-2"></i> ACTUALIZAR DATOS DE LA GESTIÓN #{{ $gestion->id }}
                    </h3>
                </div>

                <form action="{{ route('admin.gestiones.update', $gestion) }}" method="POST" id="form-edit">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            {{-- Campo Nombre (Año) --}}
                            <div class="col-md-6 form-group">
                                <label for="nombre">Año de la Gestión <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="number" name="nombre" id="nombre"
                                        class="form-control @error('nombre') is-invalid @enderror"
                                        value="{{ old('nombre', $gestion->nombre) }}" placeholder="Ej. 2026" required
                                        min="2000" max="2099">
                                    @error('nombre')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <small class="text-muted text-xs">Asegúrese de que el año no coincida con otro
                                    existente.</small>
                            </div>

                            {{-- Estado Actual (Solo lectura para mantener coherencia) --}}
                            <div class="col-md-6 form-group">
                                <label>Estado de Vigencia</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light"><i class="fas fa-info-circle"></i></span>
                                    </div>
                                    <input type="text" class="form-control bg-light text-uppercase font-weight-bold"
                                        value="{{ $gestion->estado->nombre }}" readonly
                                        style="color: {{ $gestion->estado->slug == 'activo' ? '#003366' : '#666' }};">
                                </div>
                                <small class="text-muted text-xs">Estado gestionado por el interruptor del listado.</small>
                            </div>
                        </div>

                        @if ($gestion->estado->slug == 'activo')
                            <div class="alert mt-3 border-0"
                                style="border-radius: 0; background-color: #f8f9fa; border-left: 4px solid #003366 !important;">
                                <p class="mb-0 text-muted" style="font-size: 0.85rem;">
                                    <i class="fas fa-exclamation-triangle mr-2 text-warning"></i>
                                    Esta es la <strong>Gestión Vigente</strong> actual. Cualquier cambio de año afectará los
                                    registros vinculados en el sistema.
                                </p>
                            </div>
                        @endif
                    </div>

                    <div class="card-footer bg-white border-top py-3 text-right">
                        <button type="button" onclick="window.history.back();"
                            class="btn btn-sm btn-flat btn-light border mr-2">
                            <i class="fas fa-times mr-1"></i> CANCELAR
                        </button>
                        <button type="submit" class="btn btn-sm btn-flat btn-primary px-4"
                            style="background-color: #003366; border-color: #003366;">
                            <i class="fas fa-sync-alt mr-1"></i> ACTUALIZAR GESTIÓN
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
            font-size: 0.75rem;
            color: #666;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .form-control {
            border-radius: 0;
        }

        .input-group-text {
            border-radius: 0;
            border-right: none;
            color: #adb5bd;
        }

        .form-control:focus {
            border-color: #003366;
            box-shadow: none;
        }
    </style>
@stop
