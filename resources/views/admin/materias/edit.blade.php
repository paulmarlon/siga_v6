@extends('adminlte::page')

@section('title', 'Editar Materia | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
        <h1 class="text-dark" style="font-weight: 400; font-size: 1.5rem;">
            <i class="fas fa-edit text-primary mr-2"></i> Editar Materia: {{ $materia->sigla }}
        </h1>
        <a href="{{ route('admin.materias.index') }}" class="btn btn-default btn-sm btn-flat shadow-sm border">
            <i class="fas fa-arrow-left mr-1"></i> VOLVER AL LISTADO
        </a>
    </div>
@stop

@section('content')
    <div class="row justify-content-center pt-3">
        <div class="col-md-11">
            <div class="card card-outline card-dark shadow-sm border-0"
                style="border-radius: 0; border-top: 3px solid #001f3f;">
                <div class="card-header bg-light py-3">
                    <h3 class="card-title text-secondary" style="font-size: 0.9rem; font-weight: 700;">
                        <i class="fas fa-database mr-2"></i> MODIFICAR INFORMACIÓN TÉCNICA
                    </h3>
                </div>

                <form action="{{ route('admin.materias.update', $materia->id) }}" method="POST" id="form-edit">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <div class="row">
                            {{-- Columna Izquierda: Identificación y Descripción --}}
                            <div class="col-md-7">
                                <div class="row">
                                    {{-- Sigla --}}
                                    <div class="col-md-4 form-group">
                                        <label for="sigla">Sigla <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light"><i
                                                        class="fas fa-fingerprint text-primary"></i></span>
                                            </div>
                                            <input type="text" name="sigla" id="sigla"
                                                class="form-control btn-flat font-weight-bold @error('sigla') is-invalid @enderror"
                                                value="{{ old('sigla', $materia->sigla) }}" required maxlength="20"
                                                style="text-transform: uppercase;">
                                        </div>
                                        @error('sigla')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- Nombre --}}
                                    <div class="col-md-8 form-group">
                                        <label for="nombre">Nombre de la Materia <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="nombre" id="nombre"
                                            class="form-control btn-flat @error('nombre') is-invalid @enderror"
                                            value="{{ old('nombre', $materia->nombre) }}" required
                                            style="text-transform: uppercase;">
                                        @error('nombre')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- Descripción (NUEVO) --}}
                                    <div class="col-md-12 form-group">
                                        <label for="descripcion">Descripción / Resumen Profesional</label>
                                        <textarea name="descripcion" id="descripcion" class="form-control btn-flat @error('descripcion') is-invalid @enderror"
                                            rows="4" maxlength="255" placeholder="Ingrese el enfoque de la materia..."
                                            style="resize: none; border-left: 3px solid #001f3f; background-color: #f9f9f9;">{{ old('descripcion', $materia->descripcion) }}</textarea>
                                        <div class="d-flex justify-content-between mt-1">
                                            @error('descripcion')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                            <small class="text-muted ml-auto"><span id="char-count">0</span>/255
                                                caracteres</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Columna Derecha: Configuración --}}
                            <div class="col-md-5 border-left">
                                <div class="pl-3">
                                    <div class="row">
                                        {{-- Horas Académicas --}}
                                        <div class="col-md-6 form-group">
                                            <label for="horas_academicas">Horas Acad. <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" name="horas_academicas" id="horas_academicas"
                                                    class="form-control btn-flat @error('horas_academicas') is-invalid @enderror"
                                                    value="{{ old('horas_academicas', $materia->horas_academicas) }}"
                                                    min="1" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text bg-white"><i
                                                            class="fas fa-clock text-warning"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Tipo de Materia --}}
                                        <div class="col-md-6 form-group">
                                            <label for="tipo_materia">Tipo <span class="text-danger">*</span></label>
                                            <select name="tipo_materia" id="tipo_materia" class="form-control btn-flat"
                                                required>
                                                @foreach ($tipos as $tipo)
                                                    <option value="{{ $tipo }}"
                                                        {{ old('tipo_materia', $materia->tipo_materia) == $tipo ? 'selected' : '' }}>
                                                        {{ $tipo }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Estado --}}
                                    <div class="form-group">
                                        <label for="estado_id">Estado del Registro</label>
                                        <select name="estado_id" id="estado_id" class="form-control btn-flat border-info"
                                            required>
                                            @foreach ($estados as $estado)
                                                <option value="{{ $estado->id }}"
                                                    {{ old('estado_id', $materia->estado_id) == $estado->id ? 'selected' : '' }}>
                                                    ● {{ $estado->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Tronco Común --}}
                                    <div class="form-group mt-4 p-3 border" style="background-color: #f4f6f9;">
                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" id="es_comun"
                                                name="es_comun" value="1"
                                                {{ old('es_comun', $materia->es_comun) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="es_comun">
                                                <strong style="font-size: 0.85rem;">MATERIA DE TRONCO COMÚN</strong>
                                            </label>
                                        </div>
                                        <small class="text-muted d-block mt-1">Habilite esta opción si la materia pertenece
                                            a múltiples áreas.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top py-3 text-right">
                        <button type="button" onclick="window.history.back();"
                            class="btn btn-sm btn-flat btn-outline-secondary mr-2 px-3">
                            <i class="fas fa-times mr-1"></i> DESCARTAR CAMBIOS
                        </button>
                        <button type="submit" class="btn btn-sm btn-flat px-5 shadow"
                            style="font-weight: 700; background-color: #001f3f; color: white; border-color: #001f3f;">
                            <i class="fas fa-save mr-1"></i> ACTUALIZAR REGISTRO
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
            color: #333;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .form-control:focus {
            border-color: #001f3f;
            box-shadow: none;
            background-color: #fff;
        }

        /* Estilo oscuro para el éxito o botones principales */
        .card-dark.card-outline {
            border-top: 3px solid #001f3f;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Mayúsculas automáticas
            $('#sigla, #nombre').on('input', function() {
                this.value = this.value.toUpperCase();
            });

            // Contador de caracteres para descripción
            function updateCount() {
                let length = $('#descripcion').val().length;
                $('#char-count').text(length);
                if (length >= 250) $('#char-count').addClass('text-danger');
                else $('#char-count').removeClass('text-danger');
            }

            $('#descripcion').on('input', updateCount);
            updateCount(); // Inicializar al cargar
        });
    </script>
@stop
