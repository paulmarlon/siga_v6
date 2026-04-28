@extends('adminlte::page')

@section('title', 'Editar Dirección | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
        <h1 class="text-dark" style="font-weight: 400; font-size: 1.5rem;">
            <i class="fas fa-edit text-secondary mr-2"></i> Editar Registro de Dirección
        </h1>
        <a href="{{ route('admin.direcciones.index') }}" class="btn btn-default btn-sm border">
            <i class="fas fa-arrow-left mr-1"></i> VOLVER AL LISTADO
        </a>
    </div>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card card-flat shadow-none border mt-3">
                <div class="card-header bg-light border-bottom">
                    <h3 class="card-title text-uppercase text-secondary" style="font-size: 0.85rem; font-weight: 700;">
                        Actualización de Información Geográfica
                    </h3>
                </div>

                <form action="{{ route('admin.direcciones.update', $direccion) }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT')

                    <div class="card-body bg-white">
                        <div class="row">
                            {{-- País (Opcional, se muestra pero puede venir de la DB) --}}
                            <div class="col-md-6 mb-3">
                                <label for="pais" class="text-secondary small font-weight-bold">PAÍS</label>
                                <input type="text" name="pais" id="pais"
                                    class="form-control form-control-sm @error('pais') is-invalid @enderror"
                                    value="{{ old('pais', $direccion->pais) }}" placeholder="Ej. Bolivia">
                                @error('pais')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Departamento --}}
                            <div class="col-md-6 mb-3">
                                <label for="departamento" class="text-secondary small font-weight-bold">DEPARTAMENTO /
                                    ESTADO</label>
                                <input type="text" name="departamento" id="departamento"
                                    class="form-control form-control-sm @error('departamento') is-invalid @enderror"
                                    value="{{ old('departamento', $direccion->departamento) }}" required>
                                @error('departamento')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Provincia --}}
                            <div class="col-md-6 mb-3">
                                <label for="provincia" class="text-secondary small font-weight-bold">PROVINCIA /
                                    REGIÓN</label>
                                <input type="text" name="provincia" id="provincia"
                                    class="form-control form-control-sm @error('provincia') is-invalid @enderror"
                                    value="{{ old('provincia', $direccion->provincia) }}">
                                @error('provincia')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Ciudad --}}
                            <div class="col-md-6 mb-3">
                                <label for="ciudad" class="text-secondary small font-weight-bold">CIUDAD /
                                    MUNICIPIO</label>
                                <input type="text" name="ciudad" id="ciudad"
                                    class="form-control form-control-sm @error('ciudad') is-invalid @enderror"
                                    value="{{ old('ciudad', $direccion->ciudad) }}">
                                @error('ciudad')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Zona --}}
                            <div class="col-md-6 mb-3">
                                <label for="zona" class="text-secondary small font-weight-bold">ZONA / BARRIO /
                                    COMUNIDAD</label>
                                <input type="text" name="zona" id="zona"
                                    class="form-control form-control-sm @error('zona') is-invalid @enderror"
                                    value="{{ old('zona', $direccion->zona) }}">
                                @error('zona')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Detalle Adicional --}}
                            <div class="col-12">
                                <label for="detalle" class="text-secondary small font-weight-bold">DETALLE DE DIRECCIÓN /
                                    REFERENCIAS</label>
                                <textarea name="detalle" id="detalle" rows="3"
                                    class="form-control form-control-sm @error('detalle') is-invalid @enderror">{{ old('detalle', $direccion->detalle) }}</textarea>
                                @error('detalle')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Revise la información detallada para asegurar la precisión de los
                                    datos auditados.</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light border-top d-flex justify-content-end">
                        <button type="submit" class="btn btn-success-formal btn-flat btn-sm">
                            <i class="fas fa-sync-alt mr-1"></i> ACTUALIZAR REGISTRO
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

        .form-control-sm {
            border-radius: 0;
            border: 1px solid #ced4da;
        }

        .form-control-sm:focus {
            border-color: #003366;
            box-shadow: none;
        }

        label {
            margin-bottom: 2px;
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }

        .btn-success-formal {
            background-color: #1e4d2b !important;
            /* Verde oscuro formal */
            border-color: #1e4d2b !important;
            color: #ffffff !important;
        }

        .btn-success-formal:hover {
            background-color: #163a20 !important;
            border-color: #163a20 !important;
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Log de consola para seguimiento operativo
            console.log("Módulo de Edición SIG@ cargado correctamente.");
        });
    </script>
@stop
