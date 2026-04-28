@extends('adminlte::page')

@section('title', 'Nuevo Registro | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
        <h1 class="text-dark" style="font-weight: 400; font-size: 1.5rem;">
            <i class="fas fa-user-plus text-secondary mr-2"></i> Registro Maestro de Persona
        </h1>
        <a href="{{ route('admin.personas.index') }}" class="btn btn-default btn-sm border btn-flat">
            <i class="fas fa-arrow-left mr-1"></i> VOLVER AL LISTADO
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <form action="{{ route('admin.personas.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="row mt-3">
                {{-- SECCIÓN IZQUIERDA: IDENTIDAD Y LOCALIZACIÓN --}}
                <div class="col-md-8">
                    {{-- Card Información de Identidad --}}
                    <div class="card card-flat shadow-none border mb-3">
                        <div class="card-header bg-light border-bottom py-2">
                            <h3 class="card-title text-uppercase text-secondary"
                                style="font-size: 0.85rem; font-weight: 700;">
                                <i class="fas fa-id-card mr-1"></i> Información de Identidad
                            </h3>
                        </div>
                        <div class="card-body bg-white">
                            <div class="row">
                                {{-- CI --}}
                                <div class="col-md-4 mb-3">
                                    <label class="text-secondary small font-weight-bold">NRO. DOCUMENTO (CI) *</label>
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light"><i
                                                    class="fas fa-fingerprint"></i></span>
                                        </div>
                                        <input type="text" name="ci" value="{{ old('ci') }}"
                                            class="form-control @error('ci') is-invalid @enderror"
                                            oninput="this.value = this.value.toUpperCase().replace(/[^0-9A-Z-]/g, '')"
                                            placeholder="Ej. 1234567" maxlength="15" required>
                                    </div>
                                    @error('ci')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Nombres --}}
                                <div class="col-md-8 mb-3">
                                    <label class="text-secondary small font-weight-bold">NOMBRES *</label>
                                    <input type="text" name="nombres" value="{{ old('nombres') }}"
                                        class="form-control form-control-sm @error('nombres') is-invalid @enderror"
                                        style="text-transform: uppercase;" required>
                                    @error('nombres')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Apellidos --}}
                                <div class="col-md-6 mb-3">
                                    <label class="text-secondary small font-weight-bold">APELLIDO PATERNO</label>
                                    <input type="text" name="ap_paterno" value="{{ old('ap_paterno') }}"
                                        class="form-control form-control-sm text-uppercase">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-secondary small font-weight-bold">APELLIDO MATERNO</label>
                                    <input type="text" name="ap_materno" value="{{ old('ap_materno') }}"
                                        class="form-control form-control-sm text-uppercase">
                                </div>

                                {{-- Fecha Nacimiento, Sexo y Celular --}}
                                <div class="col-md-4 mb-3">
                                    <label class="text-secondary small font-weight-bold">FECHA NACIMIENTO</label>
                                    <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                                        max="{{ date('Y-m-d', strtotime('-15 years')) }}"
                                        class="form-control form-control-sm">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="text-secondary small font-weight-bold">SEXO *</label>
                                    <select name="sexo" class="form-control form-control-sm" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>MASCULINO
                                        </option>
                                        <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>FEMENINO</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="text-secondary small font-weight-bold">CELULAR *</label>
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light"><i class="fas fa-mobile-alt"></i></span>
                                        </div>
                                        <input type="text" name="celular" value="{{ old('celular') }}"
                                            class="form-control @error('celular') is-invalid @enderror"
                                            placeholder="Ej. 70012345" maxlength="8" inputmode="numeric" required>
                                    </div>
                                    @error('celular')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="col-12 mb-2">
                                    <label class="text-secondary small font-weight-bold">EMAIL PERSONAL *</label>
                                    <input type="email" name="email_personal" value="{{ old('email_personal') }}"
                                        class="form-control form-control-sm @error('email_personal') is-invalid @enderror"
                                        required>
                                    @error('email_personal')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card Localización Completa --}}
                    <div class="card card-flat shadow-none border">
                        <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center"
                            style="border-left: 4px solid #17a2b8 !important;">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="habilitar_direccion"
                                    name="habilitar_direccion" value="1"
                                    {{ old('habilitar_direccion') ? 'checked' : '' }}>
                                <label class="custom-control-label small font-weight-bold text-secondary text-uppercase"
                                    for="habilitar_direccion">
                                    <i class="fas fa-map-marker-alt mr-1"></i> ¿REGISTRAR DIRECCIÓN?
                                </label>
                            </div>
                        </div>
                        <div class="card-body bg-white py-3" id="seccion-direccion"
                            style="{{ old('habilitar_direccion') ? '' : 'opacity: 0.5; pointer-events: none;' }}">

                            <div class="row">
                                {{-- Ubicación Geográfica --}}
                                <div class="col-md-4 mb-3">
                                    <label class="text-secondary small font-weight-bold">PAÍS</label>
                                    <select name="pais" class="form-control select2-ajax"></select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="text-secondary small font-weight-bold">DEPARTAMENTO</label>
                                    <select name="departamento" class="form-control select2-ajax"></select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="text-secondary small font-weight-bold">PROVINCIA</label>
                                    <select name="provincia" class="form-control select2-ajax"></select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-secondary small font-weight-bold">CIUDAD / LOCALIDAD</label>
                                    <select name="ciudad" class="form-control select2-ajax"></select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-secondary small font-weight-bold">ZONA / BARRIO</label>
                                    <select name="zona" class="form-control select2-ajax"></select>
                                </div>

                                {{-- Detalle Específico (Lo que faltaba) --}}
                                <div class="col-md-9 mb-3">
                                    <label class="text-secondary small font-weight-bold">AVENIDA / CALLE / PASAJE</label>
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light"><i class="fas fa-road"></i></span>
                                        </div>
                                        <input type="text" name="direccion_calle"
                                            value="{{ old('direccion_calle') }}" class="form-control text-uppercase"
                                            placeholder="Ej. Av. 6 de Agosto">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="text-secondary small font-weight-bold">NRO. VIVIENDA</label>
                                    <input type="text" name="direccion_nro" value="{{ old('direccion_nro') }}"
                                        class="form-control form-control-sm text-uppercase" placeholder="Ej. 123 o S/N">
                                </div>

                                <div class="col-12">
                                    <label class="text-secondary small font-weight-bold">REFERENCIAS ADICIONALES</label>
                                    <textarea name="direccion_referencia" rows="2" class="form-control form-control-sm text-uppercase"
                                        placeholder="Ej. Frente al edificio Alianza, puerta color negro.">{{ old('direccion_referencia') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN DERECHA --}}
                <div class="col-md-4">
                    <div class="card card-flat shadow-none border mb-3">
                        <div class="card-header bg-light py-2">
                            <span class="small font-weight-bold text-secondary text-uppercase">
                                <i class="fas fa-camera mr-1"></i> Fotografía de Perfil
                            </span>
                        </div>
                        <div class="card-body text-center bg-white py-3">
                            <img id="img-preview" src="{{ asset('img/default-avatar.png') }}"
                                class="img-thumbnail elevation-1 mb-3"
                                style="width: 180px; height: 200px; object-fit: cover; border-radius: 4px;">

                            <div class="input-group input-group-sm">
                                <div class="custom-file">
                                    <input type="file" name="foto_path" id="foto_path" class="custom-file-input"
                                        accept="image/*">
                                    <label class="custom-file-label text-left text-xs" for="foto_path">Cargar
                                        imagen...</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-flat border mb-3" style="border-left: 4px solid #ffc107 !important;">
                        <div class="card-body py-2">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="crear_cuenta"
                                    name="crear_cuenta" value="1" checked>
                                <label class="custom-control-label small font-weight-bold text-secondary"
                                    for="crear_cuenta">CREAR ACCESO AL SISTEMA</label>
                            </div>
                            <small class="text-muted">Se usará el CI como contraseña inicial.</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="card card-flat shadow-none border mt-2">
                <div class="card-footer bg-light d-flex justify-content-end py-3">
                    <button type="reset" class="btn btn-default btn-flat btn-sm mr-2 border">
                        <i class="fas fa-eraser mr-1"></i> LIMPIAR
                    </button>
                    <button type="submit" class="btn btn-primary btn-flat btn-sm px-4"
                        style="background-color: #003366; border-color: #003366;">
                        <i class="fas fa-save mr-1"></i> GUARDAR REGISTRO
                    </button>
                </div>
            </div>
        </form>
    </div>
@stop

@section('css')
    <style>
        .card-flat {
            border-radius: 0;
        }

        .btn-flat,
        .form-control-sm,
        .custom-file-label {
            border-radius: 0 !important;
        }

        .form-control-sm:focus {
            border-color: #003366;
            box-shadow: none;
        }

        .select2-container--bootstrap4 .select2-selection--single {
            border-radius: 0 !important;
            height: calc(1.8125rem + 2px) !important;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // 1. PREVISUALIZACIÓN DE FOTOGRAFÍA
            $('#foto_path').change(function() {
                if (this.files && this.files[0]) {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        $('#img-preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                    let fileName = $(this).val().split('\\').pop();
                    $(this).next('.custom-file-label').html(fileName);
                }
            });

            // 2. FORZAR MAYÚSCULAS
            $('input[type="text"]').on('input', function() {
                if ($(this).attr('name') !== 'email_personal') {
                    $(this).val($(this).val().toUpperCase());
                }
            });

            // 3. SELECT2 CON AJAX (Geografía)
            $('.select2-ajax').select2({
                theme: 'bootstrap4',
                tags: true,
                placeholder: "-- Seleccione o escriba --",
                allowClear: false,
                width: '100%',
                ajax: {
                    url: "{{ route('admin.personas.buscar_geografia') }}",
                    dataType: 'json',
                    delay: 300,
                    data: function(params) {
                        return {
                            term: params.term,
                            campo: $(this).attr('name')
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.text,
                                    text: item.text
                                };
                            })
                        };
                    }
                }
            });

            // 4. CONTROL HABILITAR DIRECCIÓN
            $('#habilitar_direccion').change(function() {
                const isChecked = $(this).is(':checked');
                const container = $('#seccion-direccion');

                if (isChecked) {
                    container.css({
                        'opacity': '1',
                        'pointer-events': 'auto'
                    });
                } else {
                    container.css({
                        'opacity': '0.5',
                        'pointer-events': 'none'
                    });
                    container.find('select').val(null).trigger('change');
                }
            });

            // 5. RESET FORMULARIO CON SWEETALERT
            $('button[type="reset"]').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿VACIAR FORMULARIO?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'SÍ, LIMPIAR',
                    confirmButtonColor: '#d33',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('form')[0].reset();
                        $('#img-preview').attr('src', "{{ asset('img/default-avatar.png') }}");
                        $('.select2-ajax').val(null).trigger('change');
                    }
                });
            });
            $('button[type="reset"]').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿VACIAR FORMULARIO?',
                    // ... configuración de la alerta
                });
            });
        });
    </script>
@stop
