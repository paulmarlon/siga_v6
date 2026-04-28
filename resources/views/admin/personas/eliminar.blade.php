@extends('adminlte::page')
@section('content_header')
    <h1><b>Creacion de un nuevo estudiante</b></h1>
    <hr>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Llene los datos del padre de familia en el formulario</h3>
                    <div class="card-tools">
                        <!-- Button trigger modal Busqueda PPFF -->
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#ModalCreate"><i
                                class="fas fa-search"></i>Buscar padre de familia</button>
                        <!-- Modal Busqueda PPFF-->
                        <div class="modal fade" id="ModalCreate" tabindex="-1" aria-labelledby="ModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Padres de Familia</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Tabla de PPFF BUSQUEDA PPFF-->
                                        <table id="example1"
                                            class="table table-bordered table-striped table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Nro</th>
                                                    <th style="text-align: center">Apellidos y Nombres</th>
                                                    <th style="text-align: center">Cédula de Identidad</th>
                                                    <th style="text-align: center">Celular</th>
                                                    <th style="text-align: center">Parentesco</th>
                                                    <th style="text-align: center">Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($ppffs as $ppff)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        {{-- Concatenamos apellidos y nombres según tu Schema --}}
                                                        <td>{{ $ppff->ap_paterno }} {{ $ppff->ap_materno }}
                                                            {{ $ppff->nombres }}</td>
                                                        <td>{{ $ppff->ci }}</td>
                                                        <td>{{ $ppff->celular }}</td>
                                                        <td>{{ $ppff->parentesco }}</td>
                                                        {{-- Enviamos toda la informacion del PPFF mediante btn-seleccionar --}}
                                                        <td style="text-align:center">
                                                            <button type="button" class="btn btn-info btn-seleccionar"
                                                                data-id="{{ $ppff->id }}"
                                                                data-nombres="{{ $ppff->nombres }}"
                                                                data-ap_paterno="{{ $ppff->ap_paterno }}"
                                                                data-ap_materno="{{ $ppff->ap_materno }}"
                                                                data-ci="{{ $ppff->ci }}"
                                                                data-celular="{{ $ppff->celular }}"
                                                                data-ocupacion="{{ $ppff->ocupacion }}"
                                                                data-parentesco="{{ $ppff->parentesco }}">
                                                                Seleccionar
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            data-dismiss="modal">Cerrar</button>
                                        <!-- Button trigger DISPARADOR modal Registro Nuevo PPFF -->
                                        <button class="btn btn-primary btn-sm" data-toggle="modal"
                                            data-target="#ModalCreatePpff">Crear nuevo PPFF</button>
                                        <!-- MODAL Registro NUEVO PPFF-->
                                        <div class="modal fade" id="ModalCreatePpff" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title" id="exampleModalLabel">Registro de un nuevo
                                                            PPFF</h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    {{-- FORMULARIO PARA CREAR UN NUEVO PPFF --}}
                                                    <div class="modal-body">
                                                        {{-- Cambio de la acción del form a la ruta 'store' --}}
                                                        <form action="{{ route('admin.ppffs.store') }}" method="POST">
                                                            @csrf
                                                            <div class="row">
                                                                {{-- NOMBRES --}}
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="nombres">Nombres</label><b> (*)</b>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i
                                                                                        class="fas fa-user"></i></span>
                                                                            </div>
                                                                            <input type="text" class="form-control"
                                                                                value="{{ old('nombres') }}" name="nombres"
                                                                                placeholder="Nombres.."
                                                                                required>{{-- Aquí no pones nada, o pones value="back" --}}
                                                                            <input type="hidden" name="redirect_to"
                                                                                value="back">
                                                                        </div>
                                                                        @error('nombres')
                                                                            <small
                                                                                class="text-danger">{{ $message }}</small>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                {{-- APELLIDO PATERNO --}}
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="ap_paterno">Apellido Paterno</label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i
                                                                                        class="fas fa-user"></i></span>
                                                                            </div>
                                                                            <input type="text" class="form-control"
                                                                                value="{{ old('ap_paterno') }}"
                                                                                name="ap_paterno"
                                                                                placeholder="Apellido paterno..">
                                                                        </div>
                                                                        @error('ap_paterno')
                                                                            <small
                                                                                class="text-danger">{{ $message }}</small>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                {{-- APELLIDO MATERNO --}}
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="ap_materno">Apellido Materno</label><b>
                                                                            (*)</b>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i
                                                                                        class="fas fa-user"></i></span>
                                                                            </div>
                                                                            <input type="text" class="form-control"
                                                                                value="{{ old('ap_materno') }}"
                                                                                name="ap_materno"
                                                                                placeholder="Apellido materno.." required>
                                                                        </div>
                                                                        @error('ap_materno')
                                                                            <small
                                                                                class="text-danger">{{ $message }}</small>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                {{-- CI --}}
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="ci">Cédula de
                                                                            Identidad</label><b> (*)</b>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i
                                                                                        class="fas fa-id-card"></i></span>
                                                                            </div>
                                                                            {{-- Usamos la función validarCI que ya tienes en el pie del Blade --}}
                                                                            <input type="text" class="form-control"
                                                                                value="{{ old('ci') }}"
                                                                                name="ci" placeholder="Ej: 1234567"
                                                                                required style="text-transform: uppercase;"
                                                                                oninput="validarCI(this)">
                                                                        </div>
                                                                        @error('ci')
                                                                            <small
                                                                                class="text-danger">{{ $message }}</small>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                {{-- CELULAR --}}
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="celular">Celular</label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i
                                                                                        class="fas fa-phone"></i></span>
                                                                            </div>
                                                                            {{-- Ajustado a maxlength 15 según tu schema --}}
                                                                            <input type="text" class="form-control"
                                                                                value="{{ old('celular') }}"
                                                                                name="celular" maxlength="15"
                                                                                placeholder="Celular.."
                                                                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                                        </div>
                                                                        @error('celular')
                                                                            <small
                                                                                class="text-danger">{{ $message }}</small>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                {{-- PARENTESCO --}}
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="parentesco">Parentesco</label><b>
                                                                            (*)</b>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i
                                                                                        class="fas fa-user-friends"></i></span>
                                                                            </div>
                                                                            <select name="parentesco" class="form-control"
                                                                                required>
                                                                                <option value="">Seleccione..
                                                                                </option>
                                                                                <option value="PADRE"
                                                                                    {{ old('parentesco') == 'PADRE' ? 'selected' : '' }}>
                                                                                    PADRE</option>
                                                                                <option value="MADRE"
                                                                                    {{ old('parentesco') == 'MADRE' ? 'selected' : '' }}>
                                                                                    MADRE</option>
                                                                                <option value="TUTOR"
                                                                                    {{ old('parentesco') == 'TUTOR' ? 'selected' : '' }}>
                                                                                    TUTOR</option>
                                                                            </select>
                                                                        </div>
                                                                        @error('parentesco')
                                                                            <small
                                                                                class="text-danger">{{ $message }}</small>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                {{-- OCUPACION --}}
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="ocupacion">Ocupación</label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i
                                                                                        class="fas fa-briefcase"></i></span>
                                                                            </div>
                                                                            <input type="text" class="form-control"
                                                                                value="{{ old('ocupacion') }}"
                                                                                name="ocupacion"
                                                                                placeholder="Escriba la ocupación..">
                                                                        </div>
                                                                        @error('ocupacion')
                                                                            <small
                                                                                class="text-danger">{{ $message }}</small>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-md-12 text-right">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Cancelar</button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Registrar Padre de
                                                                        Familia</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /. TABLA DE VISUALIZACION RESULTADO PPFF card-body -->
                <div class="card-body" style="display: none" id="datos_ppff">
                    <div class="row">
                        {{-- NOMBRES --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nombres</label>
                                <p id="nombres_ppff" class="form-control-static text-muted">--</p>
                            </div>
                        </div>
                        {{-- APELLIDO PATERNO --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Apellido Paterno</label>
                                <p id="ap_paterno_ppff" class="form-control-static text-muted">--</p>
                            </div>
                        </div>
                        {{-- APELLIDO MATERNO --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Apellido Materno</label>
                                <p id="ap_materno_ppff" class="form-control-static text-muted">--</p>
                            </div>
                        </div>
                        {{-- CI --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Carnet de Identidad</label>
                                <p id="ci_ppff" class="form-control-static text-muted">--</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        {{-- CELULAR --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Celular</label>
                                <p id="celular_ppff" class="form-control-static text-muted">--</p>
                            </div>
                        </div>
                        {{-- PARENTESCO --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Parentesco</label>
                                <p id="parentesco_ppff" class="form-control-static text-muted">--</p>
                            </div>
                        </div>
                        {{-- OCUPACION --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Ocupación</label>
                                <p id="ocupacion_ppff" class="form-control-static text-muted">--</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Llene los datos del estudiante en el formulario</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="{{ route('admin.estudiantes.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- Campos Ocultos --}}
                        <input type="hidden" name="ppff_id" id="ppff_id" value="{{ old('ppff_id') }}">
                        <input type="hidden" name="usuario_id" value="{{ $usuario_id ?? '' }}">
                        {{-- Estado por defecto (Activo) --}}
                        <input type="hidden" name="estado" value="1">

                        <div class="row">
                            {{-- 1. Campo oculto para mantener la referencia de la ruta temporal (ESTO ESTÁ BIEN) --}}
                            <input type="hidden" name="foto_temporal_path"
                                value="{{ session('foto_temporal') ?? old('foto_temporal_path') }}">

                            <div class="col-md-3 border-right text-center">
                                <div class="form-group">
                                    <label class="font-weight-bold text-muted small"><i class="fas fa-camera"></i>
                                        FOTOGRAFÍA</label>
                                    <div class="mb-3">
                                        @php
                                            // 1. Buscamos si hay una foto en sesión (error) o en old (recarga)
                                            $tempPath = session('foto_temporal') ?? old('foto_temporal_path');

                                            // 2. Definimos la ruta: Si hay temporal la usamos, si no, el avatar por defecto
                                            // IMPORTANTE: Asegúrate que 'dist/img/avatar.png' exista en tu carpeta public
                                            $imgSrc = $tempPath ? asset($tempPath) : asset('img/user.png');
                                        @endphp

                                        <img id="preview" src="{{ $imgSrc }}" class="img-thumbnail shadow-sm"
                                            style="width: 150px; height: 160px; object-fit: cover; border-radius: 10px;">
                                    </div>

                                    <div class="custom-file" style="font-size: 0.8rem;">
                                        <input type="file" name="foto" id="foto" class="custom-file-input"
                                            onchange="mostrarImagen(event)" accept="image/*">
                                        <label class="custom-file-label text-left" for="foto" data-browse="Subir">
                                            {{ $tempPath ? 'Cambiar foto...' : 'Elegir...' }}
                                        </label>
                                    </div>

                                    {{-- 3. Mensaje de éxito (ESTO ESTÁ BIEN) --}}
                                    @if ($tempPath)
                                        <small class="text-success d-block mt-1"><i class="fas fa-check-circle"></i>
                                            Imagen cargada temporalmente</small>
                                    @endif
                                </div>
                            </div>

                            {{-- COLUMNA DATOS --}}
                            <div class="col-md-9">
                                <h6 class="text-primary font-weight-bold border-bottom pb-1 mb-3">
                                    <i class="fas fa-user-grad"></i> INFORMACIÓN ACADÉMICA Y PERSONAL
                                </h6>

                                {{-- Fila 1: Identidad --}}
                                <div class="row">
                                    <div class="col-md-4 form-group mb-2">
                                        <label class="mb-0 font-weight-bold small">C.I. <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="ci"
                                            class="form-control form-control-sm @error('ci') is-invalid @enderror"
                                            value="{{ old('ci') }}" required style="text-transform: uppercase;"
                                            oninput="validarCI(this)">
                                        @error('ci')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 form-group mb-2">
                                        <label class="mb-0 font-weight-bold small">R.U. <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="registro_universitario"
                                            class="form-control form-control-sm @error('registro_universitario') is-invalid @enderror"
                                            value="{{ old('registro_universitario') }}" maxlength="10" required
                                            placeholder="Registro Univ.">
                                        @error('registro_universitario')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 form-group mb-2">
                                        <label class="mb-0 font-weight-bold small">SEXO <span
                                                class="text-danger">*</span></label>
                                        <select name="sexo"
                                            class="form-control form-control-sm @error('sexo') is-invalid @enderror"
                                            required>
                                            <option value="" disabled {{ old('sexo') ? '' : 'selected' }}>
                                                Seleccionar...</option>
                                            <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino
                                            </option>
                                            <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino
                                            </option>
                                        </select>
                                        @error('sexo')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Fila 2: Nombres --}}
                                <div class="row mt-1">
                                    <div class="col-md-4 form-group mb-2">
                                        <label class="mb-0 font-weight-bold small">NOMBRES <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="nombres"
                                            class="form-control form-control-sm @error('nombres') is-invalid @enderror"
                                            value="{{ old('nombres') }}" required>
                                        @error('nombres')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 form-group mb-2">
                                        <label class="mb-0 font-weight-bold small">AP. PATERNO</label>
                                        <input type="text" name="ap_paterno" class="form-control form-control-sm"
                                            value="{{ old('ap_paterno') }}">
                                        @error('ap_paterno')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 form-group mb-2">
                                        <label class="mb-0 font-weight-bold small">AP. MATERNO <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="ap_materno"
                                            class="form-control form-control-sm @error('ap_materno') is-invalid @enderror"
                                            value="{{ old('ap_materno') }}" required>
                                        @error('ap_materno')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <h6 class="text-primary font-weight-bold border-bottom pb-1 mt-3 mb-3 small">
                                    <i class="fas fa-id-card"></i> DATOS DE CONTACTO
                                </h6>

                                {{-- Fila 3: Contacto --}}
                                <div class="row">
                                    <div class="col-md-6 form-group mb-2">
                                        <label class="mb-0 font-weight-bold small">CORREO INSTITUCIONAL <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light"><i
                                                        class="fas fa-envelope text-primary small"></i></span>
                                            </div>
                                            <input type="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email') }}" required placeholder="usuario@siga.com">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3 form-group mb-2">
                                        <label class="mb-0 font-weight-bold small">F. NACIMIENTO <span
                                                class="text-danger">*</span></label>
                                        <input type="date" name="fecha_nacimiento"
                                            class="form-control form-control-sm @error('fecha_nacimiento') is-invalid @enderror"
                                            value="{{ old('fecha_nacimiento') }}" required>
                                        @error('fecha_nacimiento')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3 form-group mb-2">
                                        <label class="mb-0 font-weight-bold small">CELULAR</label>
                                        <input type="text" name="celular" class="form-control form-control-sm"
                                            value="{{ old('celular') }}" maxlength="15"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                        @error('celular')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="text-right pb-3">
                            <button type="button" class="btn btn-outline-secondary btn-sm px-4 mr-2"
                                onclick="window.history.back();">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary btn-sm px-5 shadow-sm">
                                <i class="fas fa-save"></i> <b>GUARDAR ESTUDIANTE</b>
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
@stop
@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        #example1_wrapper .dt-buttons {
            background-color: transparent;
            box-shadow: none;
            border: none;
            display: flex;
            justify-content: center;
            /*centrar los botones*/
            gap: 10px;
            /*espacio entre botones*/
            margin-bottom: 15px;
            /*tamaño de la fuente*/
        }

        /*estilo personalizado para los botones*/
        #example1_wrapper .btn {
            color: #fff;
            /*color del texto en blanco*/
            border-radius: 4px;
            /*bordes redondeados*/
            padding: 5px 15px;
            /*espaciado interno*/
            font-size: 14px;
            /*tamaño de la fuente*/
        }

        /*colores por tipo de boton*/
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
        }

        .btn-info {
            background-color: #17a2b8;
            border: none;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #212529;
            border: none;
        }

        .btn-default {
            background-color: #6e7176;
            color: #212529;
            border: none;
        }
    </style>
@stop
@section('js')
    <script>
        $(function() {
            // 1. DELEGACIÓN DE EVENTOS: Esto arregla el fallo con DataTable
            $(document).on('click', '.btn-seleccionar', function() {
                // Captura de datos
                var id = $(this).data('id');
                var nombres = $(this).data('nombres');
                var ap_paterno = $(this).data('ap_paterno');
                var ap_materno = $(this).data('ap_materno');
                var ci = $(this).data('ci');
                var celular = $(this).data('celular');
                var ocupacion = $(this).data('ocupacion');
                var parentesco = $(this).data('parentesco');

                // Asignación visual al Card Body
                $('#nombres_ppff').text(nombres);
                $('#ap_paterno_ppff').text(ap_paterno || ''); // Maneja nulos
                $('#ap_materno_ppff').text(ap_materno);
                $('#ci_ppff').text(ci);
                $('#celular_ppff').text(celular || 'S/N');
                $('#parentesco_ppff').text(parentesco);
                $('#ocupacion_ppff').text(ocupacion || 'S/O');

                // Asignación al input oculto para el formulario
                $('#ppff_id').val(id);

                // Mostrar el panel y cerrar el modal
                $('#datos_ppff').show();

                // IMPORTANTE: Asegúrate de que el ID del modal sea 'modal-registrar' 
                // o el que hayas puesto en tu HTML
                $('#ModalCreate').modal('hide');
            });

            // 2. Inicialización de DataTable
            $("#example1").DataTable({
                "pageLength": 5,
                "language": {
                    "emptyTable": "No hay información disponible",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ PPFF",
                    "infoEmpty": "Mostrando 0 a 0 de 0 PPFF",
                    "infoFiltered": "(Filtrado de _MAX_ PPFF totales)",
                    "lengthMenu": "Mostrar _MENU_ PPFF",
                    "search": "Buscar Padre de Familia:",
                    "zeroRecords": "No se encontraron resultados",
                    "paginate": {
                        "next": "Siguiente",
                        "previous": "Anterior",
                    }
                },
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
            });
        });

        // 3. Funciones auxiliares (Corregidas llaves de cierre)
        function mostrarImagen(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = () => {
                    const preview = document.getElementById('preview');
                    preview.src = reader.result;
                    preview.style.display = 'inline-block';
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        }

        function validarCI(input) {
            let valor = input.value.toUpperCase();
            if (valor.length > 0 && !/[0-9]/.test(valor[0])) valor = "";
            input.value = valor.replace(/[^0-9A-Z-]/g, '');
        } // <--- Esta llave faltaba
    </script>
@stop
