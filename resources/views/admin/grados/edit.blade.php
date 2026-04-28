@extends('adminlte::page')

@section('title', 'Editar Grado | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
        <h1 class="text-dark" style="font-weight: 400; font-size: 1.5rem;">
            <i class="fas fa-edit text-primary mr-2"></i> Editar Grado Académico
        </h1>
        <a href="{{ route('admin.grados.index') }}" class="btn btn-default btn-sm btn-flat shadow-sm border">
            <i class="fas fa-arrow-left mr-1"></i> VOLVER AL LISTADO
        </a>
    </div>
@stop

@section('content')
    <div class="row justify-content-center pt-3">
        <div class="col-md-8">
            <div class="card card-outline card-primary shadow-sm border-0" style="border-radius: 0;">
                <div class="card-header bg-light py-3">
                    <h3 class="card-title text-secondary" style="font-size: 0.9rem; font-weight: 700;">
                        <i class="fas fa-info-circle mr-2"></i> MODIFICAR: <strong>{{ strtoupper($grado->nombre) }}</strong>
                    </h3>
                </div>

                <form action="{{ route('admin.grados.update', $grado) }}" method="POST" id="form-edit" autocomplete="off">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <div class="row">
                            {{-- Nombre del Grado --}}
                            <div class="col-md-8 form-group">
                                <label for="nombre">Nombre del Grado <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white text-muted"><i class="fas fa-tag"></i></span>
                                    </div>
                                    <input type="text" name="nombre" id="nombre"
                                        class="form-control @error('nombre') is-invalid @enderror"
                                        value="{{ old('nombre', $grado->nombre) }}" required
                                        style="border-radius: 0; font-weight: 600; text-transform: uppercase;">
                                    @error('nombre')
                                        <span class="invalid-feedback font-weight-bold">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Orden --}}
                            <div class="col-md-4 form-group">
                                <label for="orden">Orden <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white text-muted"><i
                                                class="fas fa-sort-numeric-down"></i></span>
                                    </div>
                                    <input type="number" name="orden" id="orden"
                                        class="form-control @error('orden') is-invalid @enderror"
                                        value="{{ old('orden', $grado->orden) }}" min="0" required
                                        style="border-radius: 0; font-weight: bold; color: #003366;">
                                    @error('orden')
                                        <span class="invalid-feedback font-weight-bold">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Nivel Académico --}}
                            <div class="col-md-6 form-group mt-2">
                                <label for="nivel_id">Nivel Académico <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white text-muted"><i
                                                class="fas fa-layer-group"></i></span>
                                    </div>
                                    <select name="nivel_id" id="nivel_id"
                                        class="form-control @error('nivel_id') is-invalid @enderror"
                                        style="border-radius: 0;" required>
                                        @foreach ($niveles as $nivel)
                                            <option value="{{ $nivel->id }}"
                                                {{ old('nivel_id', $grado->nivel_id) == $nivel->id ? 'selected' : '' }}>
                                                {{ strtoupper($nivel->nombre) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('nivel_id')
                                        <span class="invalid-feedback font-weight-bold">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Ciclo Académico (Adecuado al Schema) --}}
                            <div class="col-md-6 form-group mt-2">
                                <label for="ciclo">Ciclo Académico <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white text-muted"><i
                                                class="fas fa-sync-alt"></i></span>
                                    </div>
                                    <select name="ciclo" id="ciclo"
                                        class="form-control @error('ciclo') is-invalid @enderror" style="border-radius: 0;"
                                        required>
                                        <option value="1" {{ old('ciclo', $grado->ciclo) == 1 ? 'selected' : '' }}>
                                            TRONCO COMÚN (CICLO 1)</option>
                                        <option value="2" {{ old('ciclo', $grado->ciclo) == 2 ? 'selected' : '' }}>
                                            ESPECIALIDAD (CICLO 2)</option>
                                    </select>
                                    @error('ciclo')
                                        <span class="invalid-feedback font-weight-bold">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Estado del Registro --}}
                            <div class="col-md-12 form-group mt-2">
                                <label for="estado_id">Estado del Registro <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white text-muted"><i
                                                class="fas fa-toggle-on"></i></span>
                                    </div>
                                    <select name="estado_id" id="estado_id"
                                        class="form-control @error('estado_id') is-invalid @enderror"
                                        style="border-radius: 0;" required>
                                        @foreach ($estados as $estado)
                                            <option value="{{ $estado->id }}"
                                                {{ old('estado_id', $grado->estado_id) == $estado->id ? 'selected' : '' }}>
                                                {{ strtoupper($estado->nombre) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('estado_id')
                                        <span class="invalid-feedback font-weight-bold">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light border-top py-3 text-right">
                        <a href="{{ route('admin.grados.index') }}" class="btn btn-default btn-sm btn-flat border mr-2">
                            <i class="fas fa-times mr-1"></i> CANCELAR
                        </a>
                        <button type="submit" class="btn btn-sm btn-flat btn-primary px-4 shadow-sm"
                            style="background-color: #003366; border-color: #003366;">
                            <i class="fas fa-sync-alt mr-1"></i> ACTUALIZAR GRADO
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .btn-flat {
            border-radius: 0 !important;
        }

        label {
            font-size: 0.75rem;
            color: #444;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            display: block;
        }

        .input-group-text {
            border-radius: 0;
            border-right: none;
        }

        .form-control:focus {
            border-color: #003366;
            box-shadow: none;
            background-color: #fdfdfd;
        }

        .form-control {
            transition: all 0.3s ease;
            border-radius: 0;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Forzar mayúsculas en el nombre
            $('#nombre').on('input', function() {
                this.value = this.value.toUpperCase();
            });

            // Feedback visual al procesar
            $('#form-edit').on('submit', function() {
                $('button[type="submit"]').html('<i class="fas fa-spinner fa-spin mr-1"></i> PROCESANDO...')
                    .prop('disabled', true);
            });
        });
    </script>
@stop
