@extends('adminlte::page')

@section('title', 'Editar Carrera | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
        <h1 class="text-dark" style="font-weight: 400; font-size: 1.5rem;">
            <i class="fas fa-edit text-success mr-2"></i> Editar Carrera: <span
                class="text-muted">{{ $carrera->sigla }}</span>
        </h1>
        <a href="{{ route('admin.carreras.index') }}" class="btn btn-default btn-sm btn-flat shadow-sm border">
            <i class="fas fa-arrow-left mr-1"></i> VOLVER AL LISTADO
        </a>
    </div>
@stop

@section('content')
    <div class="row justify-content-center pt-3">
        <div class="col-md-11">
            <div class="card card-outline card-success shadow-sm border-0" style="border-radius: 0;">
                <div class="card-header bg-light py-3">
                    <h3 class="card-title text-secondary" style="font-size: 0.9rem; font-weight: 700;">
                        <i class="fas fa-graduation-cap mr-2"></i> ACTUALIZACIÓN DE PARÁMETROS ESTRUCTURALES
                    </h3>
                </div>

                <form action="{{ route('admin.carreras.update', $carrera) }}" method="POST" id="form-edit">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            {{-- Fila 1: Identificación --}}
                            <div class="col-md-8 form-group">
                                <label for="nombre">Nombre de la Carrera <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" id="nombre"
                                    class="form-control @error('nombre') is-invalid @enderror"
                                    value="{{ old('nombre', $carrera->nombre) }}" required maxlength="255">
                                @error('nombre')
                                    <span class="invalid-feedback font-weight-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 form-group">
                                <label for="sigla">Sigla <span class="text-danger">*</span></label>
                                <input type="text" name="sigla" id="sigla"
                                    class="form-control @error('sigla') is-invalid @enderror"
                                    value="{{ old('sigla', $carrera->sigla) }}" required maxlength="20">
                                @error('sigla')
                                    <span class="invalid-feedback font-weight-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Fila 2: Título y Duración --}}
                            <div class="col-md-8 form-group">
                                <label for="titulo">Título Académico <span class="text-danger">*</span></label>
                                <input type="text" name="titulo" id="titulo"
                                    class="form-control @error('titulo') is-invalid @enderror"
                                    value="{{ old('titulo', $carrera->titulo) }}" required>
                                @error('titulo')
                                    <span class="invalid-feedback font-weight-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 form-group">
                                <label for="duracion">Duración (Periodos) <span class="text-danger">*</span></label>
                                <input type="number" name="duracion" id="duracion"
                                    class="form-control @error('duracion') is-invalid @enderror"
                                    value="{{ old('duracion', $carrera->duracion) }}" required min="1">
                                @error('duracion')
                                    <span class="invalid-feedback font-weight-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <hr class="my-4">
                                <h5 class="text-success mb-3" style="font-size: 1rem; font-weight: 600;">Jerarquía y
                                    Dependencia (Herencia V6.2)</h5>
                            </div>

                            {{-- Fila 3: Lógica de Herencia --}}
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
                                            {{-- Evitar que la carrera se seleccione a sí misma como base --}}
                                            @if ($base->id !== $carrera->id)
                                                <option value="{{ $base->id }}"
                                                    {{ old('carrera_base_id', $carrera->carrera_base_id) == $base->id ? 'selected' : '' }}>
                                                    {{ $base->sigla }} - {{ $base->nombre }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <small class="text-muted">Nota: Si cambia la base, las materias heredadas podrían verse
                                    afectadas.</small>
                            </div>

                            <div class="col-md-6 form-group d-flex align-items-center pt-4">
                                <div
                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success shadow-sm p-2 border rounded bg-white w-100 pl-5">
                                    <input type="checkbox" name="es_tronco_comun" class="custom-control-input"
                                        id="es_tronco_comun" value="1"
                                        {{ old('es_tronco_comun', $carrera->es_tronco_comun) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold" for="es_tronco_comun">
                                        <i class="fas fa-layer-group mr-1 text-secondary"></i> ¿ESTA CARRERA ES TRONCO
                                        COMÚN?
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-12"><br></div>

                            {{-- Fila 4: Datos Administrativos --}}
                            <div class="col-md-4 form-group">
                                <label for="resolucion">Nro. de Resolución Ministerial</label>
                                <input type="text" name="resolucion" id="resolucion"
                                    class="form-control @error('resolucion') is-invalid @enderror"
                                    value="{{ old('resolucion', $carrera->resolucion) }}">
                                @error('resolucion')
                                    <span class="invalid-feedback d-block font-weight-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 form-group">
                                <label for="nivel_id">Nivel Académico <span class="text-danger">*</span></label>
                                <select name="nivel_id" id="nivel_id"
                                    class="form-control @error('nivel_id') is-invalid @enderror" required>
                                    @foreach ($niveles as $nivel)
                                        <option value="{{ $nivel->id }}"
                                            {{ old('nivel_id', $carrera->nivel_id) == $nivel->id ? 'selected' : '' }}>
                                            {{ $nivel->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 form-group">
                                <label for="estado_id">Estado Actual <span class="text-danger">*</span></label>
                                <select name="estado_id" id="estado_id"
                                    class="form-control @error('estado_id') is-invalid @enderror" required>
                                    @foreach ($estados as $estado)
                                        <option value="{{ $estado->id }}"
                                            {{ old('estado_id', $carrera->estado_id) == $estado->id ? 'selected' : '' }}>
                                            {{ $estado->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light border-top py-3 text-right">
                        <a href="{{ route('admin.carreras.index') }}"
                            class="btn btn-sm btn-flat btn-default border mr-2">
                            <i class="fas fa-times mr-1"></i> CANCELAR
                        </a>
                        <button type="submit" class="btn btn-sm btn-flat btn-success px-4 shadow-sm font-weight-bold">
                            <i class="fas fa-sync-alt mr-1"></i> GUARDAR CAMBIOS
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
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #28a745;
            box-shadow: none;
            background-color: #fdfdfd;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Mayúsculas automáticas
            $('#nombre, #sigla, #titulo, #resolucion').on('input', function() {
                this.value = this.value.toUpperCase();
            });

            // Lógica de jerarquía dinámica
            function checkHierarchy() {
                if ($('#es_tronco_comun').is(':checked')) {
                    $('#container_carrera_base').fadeOut('fast');
                    $('#carrera_base_id').val('');
                } else {
                    $('#container_carrera_base').fadeIn('fast');
                }
            }

            // Ejecutar al inicio y en cambios
            checkHierarchy();
            $('#es_tronco_comun').change(checkHierarchy);

            // Loader al procesar
            $('#form-edit').submit(function() {
                Swal.fire({
                    title: 'Actualizando...',
                    text: 'Sincronizando cambios en la estructura de la carrera.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            });
        });
    </script>
@stop
