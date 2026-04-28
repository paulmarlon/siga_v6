@extends('adminlte::page')

@section('title', 'Editar Nivel | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
        <h1 class="text-dark" style="font-weight: 400; font-size: 1.5rem;">
            <i class="fas fa-edit text-secondary mr-2"></i> Modificar Nivel Académico
        </h1>
        <a href="{{ route('admin.niveles.index') }}" class="btn btn-default btn-sm btn-flat shadow-sm border">
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
                        <i class="fas fa-database mr-2"></i> ACTUALIZAR INFORMACIÓN DEL NIVEL #{{ $nivel->id }}
                    </h3>
                </div>

                <form action="{{ route('admin.niveles.update', $nivel) }}" method="POST" id="form-edit">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            {{-- Campo Nombre del Nivel --}}
                            <div class="col-md-8 form-group">
                                <label for="nombre">Nombre del Nivel Académico <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white"><i class="fas fa-tag"></i></span>
                                    </div>
                                    <input type="text" name="nombre" id="nombre"
                                        class="form-control @error('nombre') is-invalid @enderror"
                                        value="{{ old('nombre', $nivel->nombre) }}"
                                        placeholder="Ej. PRIMARIA, SECUNDARIA..." required maxlength="50">
                                    @error('nombre')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <small class="text-muted text-xs">Cualquier cambio afectará a los grados asociados.</small>
                            </div>

                            {{-- Selección de Estado --}}
                            <div class="col-md-4 form-group">
                                <label for="estado_id">Estado del Registro <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white"><i class="fas fa-toggle-on"></i></span>
                                    </div>
                                    <select name="estado_id" id="estado_id"
                                        class="form-control @error('estado_id') is-invalid @enderror" required>
                                        @foreach ($estados as $estado)
                                            <option value="{{ $estado->id }}"
                                                {{ old('estado_id', $nivel->estado_id) == $estado->id ? 'selected' : '' }}>
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

                        <div class="alert mt-3 border-0"
                            style="border-radius: 0; background-color: #f8f9fa; border-left: 4px solid #155724 !important;">
                            <p class="mb-0 text-muted" style="font-size: 0.85rem;">
                                <i class="fas fa-info-circle mr-2" style="color: #155724;"></i>
                                <strong>Nota:</strong> Al modificar el nombre, asegúrese de mantener la coherencia con la
                                estructura curricular vigente de la institución.
                            </p>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top py-3 text-right">
                        <button type="button" onclick="window.history.back();"
                            class="btn btn-sm btn-flat btn-light border mr-2">
                            <i class="fas fa-times mr-1"></i> CANCELAR
                        </button>
                        <button type="submit" class="btn btn-sm btn-flat btn-primary px-4"
                            style="background-color: #155724; border-color: #155724;">
                            <i class="fas fa-sync-alt mr-1"></i> ACTUALIZAR NIVEL
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
            border-color: #155724;
            box-shadow: none;
        }
    </style>
@stop
