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
                            {{-- SECCIÓN IZQUIERDA: IDENTIFICACIÓN --}}
                            <div class="col-md-7">
                                <div class="row">
                                    {{-- Sigla --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="sigla">Sigla <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light"><i
                                                            class="fas fa-fingerprint text-primary"></i></span>
                                                </div>
                                                <input type="text" name="sigla" id="sigla"
                                                    class="form-control btn-flat font-weight-bold @error('sigla') is-invalid @enderror"
                                                    value="{{ old('sigla') }}" placeholder="INF-101" required
                                                    maxlength="20" style="text-transform: uppercase;">
                                            </div>
                                            @error('sigla')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Nombre --}}
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="nombre">Nombre de la Materia <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="nombre" id="nombre"
                                                class="form-control btn-flat @error('nombre') is-invalid @enderror"
                                                value="{{ old('nombre') }}" placeholder="EJ: ESTRUCTURA DE DATOS" required
                                                style="text-transform: uppercase;">
                                            @error('nombre')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Descripción (Basado en tu Schema) --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="descripcion">Descripción / Resumen <span
                                                    class="text-muted small">(Opcional)</span></label>
                                            <textarea name="descripcion" id="descripcion" class="form-control btn-flat @error('descripcion') is-invalid @enderror"
                                                rows="3" maxlength="255" placeholder="Breve descripción de la materia..."
                                                style="resize: none; border-left: 3px solid #003366;">{{ old('descripcion') }}</textarea>
                                            <div class="d-flex justify-content-between">
                                                @error('descripcion')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                                <small class="text-muted ml-auto"><span id="char-count">0</span>/255</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- SECCIÓN DERECHA: CONFIGURACIÓN Y ESTADO --}}
                            <div class="col-md-5 border-left">
                                <div class="pl-3">
                                    <div class="row">
                                        {{-- Horas --}}
                                        <div class="col-md-6 form-group">
                                            <label for="horas_academicas">Horas Acad. <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" name="horas_academicas" id="horas_academicas"
                                                    class="form-control btn-flat @error('horas_academicas') is-invalid @enderror"
                                                    value="{{ old('horas_academicas', 80) }}" min="1" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text bg-white"><i
                                                            class="fas fa-clock text-warning"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Tipo --}}
                                        <div class="col-md-6 form-group">
                                            <label for="tipo_materia">Tipo <span class="text-danger">*</span></label>
                                            <select name="tipo_materia" id="tipo_materia"
                                                class="form-control btn-flat select2" required>
                                                @foreach ($tipos as $tipo)
                                                    <option value="{{ $tipo }}"
                                                        {{ old('tipo_materia') == $tipo ? 'selected' : '' }}>
                                                        {{ $tipo }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="estado_id">Estado Inicial</label>
                                        <select name="estado_id" id="estado_id" class="form-control btn-flat border-info"
                                            required>
                                            @foreach ($estados as $estado)
                                                <option value="{{ $estado->id }}"
                                                    {{ old('estado_id') == $estado->id ? 'selected' : '' }}>
                                                    ● {{ $estado->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mt-4 p-3 bg-light border">
                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" id="es_comun"
                                                name="es_comun" value="1" {{ old('es_comun') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="es_comun">
                                                <strong class="text-uppercase" style="font-size: 0.85rem;">¿Es Materia de
                                                    Tronco Común?</strong>
                                            </label>
                                        </div>
                                        <p class="text-muted mb-0 small mt-1">Si se activa, la materia será compartida
                                            entre diferentes menciones o carreras.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top py-3 text-right">
                        <button type="reset" class="btn btn-sm btn-flat btn-outline-secondary mr-2 px-3">
                            <i class="fas fa-undo mr-1"></i> REINICIAR
                        </button>
                        <button type="submit" class="btn btn-sm btn-flat btn-primary px-5 shadow"
                            style="background-color: #003366; border-color: #003366;">
                            <i class="fas fa-save mr-1"></i> GUARDAR MATERIA
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
