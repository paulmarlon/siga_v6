@extends('adminlte::page')

@section('title', 'Nueva Materia | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
        <h1 class="text-dark" style="font-weight: 400; font-size: 1.5rem;">
            <i class="fas fa-book-medical text-primary mr-2"></i> Registrar Nueva Materia
        </h1>
        <a href="{{ route('admin.materias.index') }}" class="btn btn-default btn-sm btn-flat shadow-sm border">
            <i class="fas fa-arrow-left mr-1"></i> VOLVER AL LISTADO
        </a>
    </div>
@stop

@section('content')
    <div class="row justify-content-center pt-3">
        <div class="col-md-10">
            <div class="card card-outline card-primary shadow-sm border-0" style="border-radius: 0;">
                <div class="card-header bg-light py-3">
                    <h3 class="card-title text-secondary" style="font-size: 0.9rem; font-weight: 700;">
                        <i class="fas fa-edit mr-2"></i> DATOS TÉCNICOS DE LA MATERIA
                    </h3>
                </div>

                <form action="{{ route('admin.materias.store') }}" method="POST" id="form-create">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            {{-- Columna Izquierda: Identificación --}}
                            <div class="col-md-6">
                                {{-- Sigla --}}
                                <div class="form-group">
                                    <label for="sigla">Sigla / Código <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white"><i class="fas fa-barcode"></i></span>
                                        </div>
                                        <input type="text" name="sigla" id="sigla"
                                            class="form-control btn-flat @error('sigla') is-invalid @enderror"
                                            value="{{ old('sigla') }}" placeholder="Ej: MAT-101" required maxlength="20"
                                            style="text-transform: uppercase; font-weight: 700;">
                                        @error('sigla')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Nombre --}}
                                <div class="form-group">
                                    <label for="nombre">Nombre de la Materia <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre" id="nombre"
                                        class="form-control btn-flat @error('nombre') is-invalid @enderror"
                                        value="{{ old('nombre') }}" placeholder="Ej: ÁLGEBRA LINEAL" required
                                        style="text-transform: uppercase;">
                                    @error('nombre')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Columna Derecha: Configuración --}}
                            <div class="col-md-6">
                                <div class="row">
                                    {{-- Horas Académicas --}}
                                    <div class="col-md-6 form-group">
                                        <label for="horas_academicas">Horas Académicas <span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="horas_academicas" id="horas_academicas"
                                            class="form-control btn-flat @error('horas_academicas') is-invalid @enderror"
                                            value="{{ old('horas_academicas', 80) }}" min="1" required>
                                        @error('horas_academicas')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- Tipo de Materia --}}
                                    <div class="col-md-6 form-group">
                                        <label for="tipo_materia">Tipo de Materia <span class="text-danger">*</span></label>
                                        <select name="tipo_materia" id="tipo_materia" class="form-control btn-flat"
                                            required>
                                            @foreach ($tipos as $tipo)
                                                <option value="{{ $tipo }}"
                                                    {{ old('tipo_materia') == $tipo ? 'selected' : '' }}>
                                                    {{ $tipo }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- Estado (Global) --}}
                                    <div class="col-md-6 form-group">
                                        <label for="estado_id">Estado Inicial <span class="text-danger">*</span></label>
                                        <select name="estado_id" id="estado_id" class="form-control btn-flat" required>
                                            @foreach ($estados as $estado)
                                                <option value="{{ $estado->id }}"
                                                    {{ old('estado_id') == $estado->id ? 'selected' : '' }}>
                                                    {{ $estado->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Tronco Común --}}
                                    <div class="col-md-6 form-group d-flex align-items-center pt-4">
                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" id="es_comun"
                                                name="es_comun" value="1" {{ old('es_comun') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="es_comun"
                                                style="font-size: 0.8rem; padding-top: 2px;">¿ES MATERIA COMÚN?</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light border-top py-3 text-right">
                        <button type="reset" class="btn btn-sm btn-flat btn-default border mr-2">
                            <i class="fas fa-eraser mr-1"></i> LIMPIAR
                        </button>
                        <button type="submit" class="btn btn-sm btn-flat btn-primary px-4 shadow-sm"
                            style="background-color: #003366; border-color: #003366;">
                            <i class="fas fa-save mr-1"></i> REGISTRAR MATERIA
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
            // Sigla y Nombre siempre en Mayúsculas
            $('#sigla, #nombre').on('input', function() {
                this.value = this.value.toUpperCase();
            });

            // Limpieza con Confirmación
            $('button[type="reset"]').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Limpiar formulario?',
                    text: "Se perderán los datos actuales.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, limpiar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#form-create')[0].reset();
                        $('#sigla').focus();
                    }
                });
            });
        });
    </script>
@stop
