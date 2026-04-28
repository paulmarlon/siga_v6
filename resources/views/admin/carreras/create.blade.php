@extends('adminlte::page')

@section('title', 'Nueva Carrera | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
        <h1 class="text-dark" style="font-weight: 400; font-size: 1.5rem;">
            <i class="fas fa-plus-circle text-primary mr-2"></i> Registrar Nueva Carrera
        </h1>
        <a href="{{ route('admin.carreras.index') }}" class="btn btn-default btn-sm btn-flat shadow-sm border">
            <i class="fas fa-arrow-left mr-1"></i> VOLVER AL LISTADO
        </a>
    </div>
@stop

@section('content')
    <div class="row justify-content-center pt-3">
        <div class="col-md-11">
            <div class="card card-outline card-primary shadow-sm border-0" style="border-radius: 0;">
                <div class="card-header bg-light py-3">
                    <h3 class="card-title text-secondary" style="font-size: 0.9rem; font-weight: 700;">
                        <i class="fas fa-graduation-cap mr-2"></i> DEFINICIÓN ESTRUCTURAL DE LA CARRERA
                    </h3>
                </div>

                <form action="{{ route('admin.carreras.store') }}" method="POST" id="form-create">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            {{-- Fila 1: Identificación Básica --}}
                            <div class="col-md-8 form-group">
                                <label for="nombre">Nombre de la Carrera <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" id="nombre"
                                    class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}"
                                    placeholder="EJ. CONTADURÍA GENERAL" required maxlength="255" autofocus>
                                @error('nombre')
                                    <span class="invalid-feedback font-weight-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 form-group">
                                <label for="sigla">Sigla <span class="text-danger">*</span></label>
                                <input type="text" name="sigla" id="sigla"
                                    class="form-control @error('sigla') is-invalid @enderror" value="{{ old('sigla') }}"
                                    placeholder="EJ. CONT-100" required maxlength="20">
                                @error('sigla')
                                    <span class="invalid-feedback font-weight-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Fila 2: Grado Académico y Título --}}
                            <div class="col-md-8 form-group">
                                <label for="titulo">Título Académico que Otorga <span class="text-danger">*</span></label>
                                <input type="text" name="titulo" id="titulo"
                                    class="form-control @error('titulo') is-invalid @enderror" value="{{ old('titulo') }}"
                                    placeholder="EJ. TÉCNICO SUPERIOR EN CONTADURÍA" required>
                                @error('titulo')
                                    <span class="invalid-feedback font-weight-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 form-group">
                                <label for="duracion">Duración Total (Semestres) <span class="text-danger">*</span></label>
                                <input type="number" name="duracion" id="duracion"
                                    class="form-control @error('duracion') is-invalid @enderror"
                                    value="{{ old('duracion') }}" placeholder="Ej. 6" required min="1">
                                @error('duracion')
                                    <span class="invalid-feedback font-weight-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <hr class="my-4">
                                <h5 class="text-primary mb-3" style="font-size: 1rem; font-weight: 600;">Configuración de
                                    Jerarquía Académica (V6.2)</h5>
                            </div>

                            {{-- Fila 3: Lógica de Herencia y Recursividad --}}
                            <div class="col-md-6 form-group" id="container_carrera_base">
                                <label for="carrera_base_id">Depende de (Tronco Común)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light"><i
                                                class="fas fa-sitemap text-muted"></i></span>
                                    </div>
                                    <select name="carrera_base_id" id="carrera_base_id"
                                        class="form-control @error('carrera_base_id') is-invalid @enderror">
                                        <option value="">-- Carrera Independiente / Raíz --</option>
                                        @foreach ($carrerasBase as $base)
                                            <option value="{{ $base->id }}"
                                                {{ old('carrera_base_id') == $base->id ? 'selected' : '' }}>
                                                {{ $base->sigla }} - {{ $base->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="text-muted italic">Si esta carrera hereda materias de los primeros semestres
                                    de otra, selecciónela aquí.</small>
                            </div>

                            <div class="col-md-6 form-group d-flex align-items-center pt-4">
                                <div
                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success shadow-sm p-2 border rounded bg-white w-100 pl-5">
                                    <input type="checkbox" name="es_tronco_comun" class="custom-control-input"
                                        id="es_tronco_comun" value="1" {{ old('es_tronco_comun') ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold" for="es_tronco_comun">
                                        <i class="fas fa-layer-group mr-1 text-secondary"></i> ¿DEFINIR COMO TRONCO COMÚN?
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-12"><br></div>

                            {{-- Fila 4: Datos Administrativos --}}
                            <div class="col-md-4 form-group">
                                <label for="resolucion">Nro. de Resolución Ministerial</label>
                                <input type="text" name="resolucion" id="resolucion"
                                    class="form-control @error('resolucion') is-invalid @enderror"
                                    value="{{ old('resolucion') }}" placeholder="EJ. R.M. 0540/2023">
                                @error('resolucion')
                                    <span class="invalid-feedback d-block font-weight-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 form-group">
                                <label for="nivel_id">Nivel Académico <span class="text-danger">*</span></label>
                                <select name="nivel_id" id="nivel_id"
                                    class="form-control @error('nivel_id') is-invalid @enderror" required>
                                    <option value="">-- Seleccione Nivel --</option>
                                    @foreach ($niveles as $nivel)
                                        <option value="{{ $nivel->id }}"
                                            {{ old('nivel_id') == $nivel->id ? 'selected' : '' }}>
                                            {{ $nivel->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 form-group">
                                <label for="estado_id">Estado del Registro <span class="text-danger">*</span></label>
                                <select name="estado_id" id="estado_id"
                                    class="form-control @error('estado_id') is-invalid @enderror" required>
                                    @foreach ($estados as $estado)
                                        <option value="{{ $estado->id }}"
                                            {{ (old('estado_id') ?? $estadoActivo->id) == $estado->id ? 'selected' : '' }}>
                                            {{ $estado->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light border-top py-3 text-right">
                        <button type="reset" class="btn btn-sm btn-flat btn-default border mr-2">
                            <i class="fas fa-eraser mr-1"></i> LIMPIAR FORMULARIO
                        </button>
                        <button type="submit" class="btn btn-sm btn-flat btn-primary px-4 shadow-sm"
                            style="background-color: #003366; border-color: #003366;">
                            <i class="fas fa-save mr-1"></i> REGISTRAR EN SIG@
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
            font-size: 0.72rem;
            color: #555;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 7px;
        }

        .form-control {
            border-radius: 0;
            border: 1px solid #ced4da;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #003366;
            box-shadow: none;
            background-color: #f8f9fa;
        }

        .custom-switch-on-success .custom-control-input:checked~.custom-control-label::before {
            background-color: #28a745;
            border-color: #28a745;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // 1. Transformación automática a MAYÚSCULAS
            $('#nombre, #sigla, #titulo, #resolucion').on('input', function() {
                this.value = this.value.toUpperCase();
            });

            // 2. Lógica Dinámica de Herencia
            function handleHierarchyLogic() {
                const isTronco = $('#es_tronco_comun').is(':checked');

                if (isTronco) {
                    // Si es tronco común, no puede tener un padre (raíz)
                    $('#container_carrera_base').fadeOut('fast');
                    $('#carrera_base_id').val('');
                } else {
                    $('#container_carrera_base').fadeIn('fast');
                }
            }

            // Ejecutar al cargar (por si hay errores de validación de Laravel)
            handleHierarchyLogic();

            // Escuchar cambios en el switch
            $('#es_tronco_comun').change(function() {
                handleHierarchyLogic();
            });

            // 3. Confirmación y Loading al guardar
            $('#form-create').submit(function() {
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Guardando datos en la base de datos central.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            });
        });
    </script>
@stop
