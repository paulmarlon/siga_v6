@extends('adminlte::page')

@section('title', 'Nueva Gestión | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
        <h1 class="text-dark" style="font-weight: 400; font-size: 1.5rem;">
            <i class="fas fa-plus-circle text-secondary mr-2"></i> Registrar Nueva Gestión Anual
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
                        <i class="fas fa-database mr-2"></i> DATOS TÉCNICOS DE LA GESTIÓN
                    </h3>
                </div>

                <form action="{{ route('admin.gestiones.store') }}" method="POST" id="form-create">
                    @csrf
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
                                        value="{{ old('nombre', date('Y')) }}" placeholder="Ej. 2026" required
                                        min="2000" max="2099">
                                    @error('nombre')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <small class="text-muted text-xs">Formato de 4 dígitos (AAAA).</small>
                            </div>

                            {{-- Estado Informativo (El sistema lo gestiona) --}}
                            <div class="col-md-6 form-group">
                                <label>Estado Inicial</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light"><i class="fas fa-info-circle"></i></span>
                                    </div>
                                    <input type="text" class="form-control bg-light" value="PENDIENTE DE ACTIVACIÓN"
                                        readonly>
                                </div>
                                <small class="text-muted text-xs">La gestión se creará como inactiva por seguridad.</small>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3 border-0 shadow-sm"
                            style="border-radius: 0; background-color: #e7f3ff;">
                            <p class="mb-0" style="font-size: 0.85rem; color: #004085;">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Nota:</strong> Una vez creada, podrá establecer esta gestión como
                                <strong>Vigente</strong> desde el listado principal.
                            </p>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top py-3 text-right">
                        <button type="reset" class="btn btn-sm btn-flat btn-light border mr-2">
                            <i class="fas fa-eraser mr-1"></i> LIMPIAR
                        </button>
                        <button type="submit" class="btn btn-sm btn-flat btn-primary px-4"
                            style="background-color: #003366; border-color: #003366;">
                            <i class="fas fa-save mr-1"></i> GUARDAR GESTIÓN
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
