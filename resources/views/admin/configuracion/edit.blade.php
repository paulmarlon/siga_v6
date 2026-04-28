@extends('adminlte::page')

@section('title', 'Configuración Institucional | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
        <h1 style="font-weight: 300; font-size: 1.4rem; color: #333;">
            <i class="fas fa-cogs mr-2" style="color: #003366;"></i>Ajustes del Sistema
        </h1>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        {{-- Alertas --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show btn-flat small py-2 border-0 shadow-sm mt-2"
                role="alert">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <form action="{{ route('admin.configuracion.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row mt-3">
                {{-- Bloque Principal (Izquierda) --}}
                <div class="col-md-9">

                    {{-- 1. DATOS DE LA INSTITUCIÓN --}}
                    <div class="card card-outline shadow-sm border-0" style="border-top: 3px solid #003366;">
                        <div class="card-header bg-white py-2">
                            <h3 class="card-title font-weight-bold" style="font-size: 0.85rem; color: #333;">
                                <i class="fas fa-university mr-1" style="color: #003366;"></i> DATOS DE LA INSTITUCIÓN
                            </h3>
                        </div>
                        <div class="card-body py-3">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="small font-weight-bold mb-1 d-block text-uppercase text-muted">Nombre
                                        Institucional *</label>
                                    <input type="text" name="nombre_institucion"
                                        class="form-control form-control-sm text-uppercase shadow-none"
                                        value="{{ old('nombre_institucion', $config->nombre_institucion) }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label
                                        class="small font-weight-bold mb-1 d-block text-uppercase text-muted">Sigla</label>
                                    <input type="text" name="sigla_institucion"
                                        class="form-control form-control-sm text-uppercase shadow-none"
                                        value="{{ old('sigla_institucion', $config->sigla_institucion) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="small font-weight-bold mb-1 d-block text-uppercase text-muted">NIT</label>
                                    <input type="text" name="nit" class="form-control form-control-sm shadow-none"
                                        value="{{ old('nit', $config->nit) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label
                                        class="small font-weight-bold mb-1 d-block text-uppercase text-muted">Teléfono</label>
                                    <input type="text" name="telefono" class="form-control form-control-sm shadow-none"
                                        value="{{ old('telefono', $config->telefono) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="small font-weight-bold mb-1 d-block text-uppercase text-muted">Gestión
                                        Activa</label>
                                    <select name="gestion_actual_id" class="form-control form-control-sm shadow-none"
                                        required>
                                        @foreach ($gestiones as $gestion)
                                            <option value="{{ $gestion->id }}"
                                                {{ old('gestion_actual_id', $config->gestion_actual_id) == $gestion->id ? 'selected' : '' }}>
                                                {{ $gestion->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. UBICACIÓN INSTITUCIONAL --}}
                    <div class="card card-outline shadow-sm border-0 mt-3" style="border-top: 3px solid #003366;">
                        <div class="card-header bg-white py-2">
                            <h3 class="card-title font-weight-bold" style="font-size: 0.85rem; color: #333;">
                                <i class="fas fa-map-marked-alt mr-1" style="color: #003366;"></i> UBICACIÓN INSTITUCIONAL
                            </h3>
                        </div>
                        <div class="card-body py-3">
                            <div class="row">
                                @php
                                    $camposGeo = [
                                        'pais' => 'PAÍS',
                                        'departamento' => 'DEPARTAMENTO *',
                                        'provincia' => 'PROVINCIA',
                                        'ciudad' => 'CIUDAD',
                                        'zona' => 'ZONA / BARRIO *',
                                    ];
                                @endphp

                                @foreach ($camposGeo as $nombre => $label)
                                    <div class="{{ $nombre == 'zona' ? 'col-md-4' : 'col-md-2' }} mb-3">
                                        <label class="small font-weight-bold text-muted">{{ $label }}</label>
                                        <select name="{{ $nombre }}"
                                            class="form-control form-control-sm select2-tags shadow-none"
                                            {{ in_array($nombre, ['departamento', 'zona']) ? 'required' : '' }}>
                                            <option value="">-- SELECCIONAR --</option>
                                            @foreach ($listas[$nombre] ?? [] as $opcion)
                                                <option value="{{ $opcion }}"
                                                    {{ old($nombre, $config->direccion->$nombre ?? ($$nombre ?? '')) == $opcion ? 'selected' : '' }}>
                                                    {{ $opcion }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach

                                {{-- Inputs corregidos con triple fallback para asegurar que se vea el dato guardado --}}
                                <div class="col-md-4 mb-3">
                                    <label class="small font-weight-bold text-muted">CALLE / AVENIDA</label>
                                    <input type="text" name="direccion_calle"
                                        value="{{ old('direccion_calle', $direccion_calle ?? ($calle ?? ($config->direccion_calle ?? ''))) }}"
                                        class="form-control form-control-sm text-uppercase shadow-none">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="small font-weight-bold text-muted">NRO.</label>
                                    <input type="text" name="direccion_nro"
                                        value="{{ old('direccion_nro', $direccion_nro ?? ($nro ?? ($config->direccion_nro ?? ''))) }}"
                                        class="form-control form-control-sm text-uppercase shadow-none">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted">REFERENCIAS ADICIONALES</label>
                                    <textarea name="direccion_referencia" rows="1" class="form-control form-control-sm text-uppercase shadow-none">{{ old('direccion_referencia', $direccion_referencia ?? ($ref ?? ($config->direccion_referencia ?? ''))) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bloque Lateral (Derecha) --}}
                <div class="col-md-3">
                    <div class="card card-outline shadow-sm border-0 text-center" style="border-top: 3px solid #003366;">
                        <div class="card-body py-4">
                            <label class="small font-weight-bold mb-2 d-block text-uppercase text-muted">Logo
                                Institucional</label>
                            <div class="p-2 mb-3"
                                style="border: 1px dashed #00336633; border-radius: 10px; background: #fcfcfc;">
                                <img id="preview" src="{{ $config->logo_url }}" class="img-fluid"
                                    style="max-height: 140px; object-fit: contain;">
                            </div>
                            <div class="custom-file mt-2">
                                <input type="file" name="logo_path" class="custom-file-input" id="logoInput"
                                    accept="image/*">
                                <label class="custom-file-label btn-xs text-muted text-left" for="logoInput">Elegir
                                    archivo...</label>
                            </div>
                            <hr class="my-4" style="border-top: 1px solid #eee;">
                            <button type="submit" class="btn btn-sm btn-block btn-flat font-weight-bold shadow-sm"
                                style="background-color: #003366; color: white;">
                                <i class="fas fa-save mr-1"></i> GUARDAR CAMBIOS
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop

@section('css')
    <style>
        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(1.8125rem + 2px) !important;
            font-size: 0.875rem !important;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            line-height: 1.6 !important;
        }

        .custom-file-label::after {
            content: "Buscar";
            background-color: #f8f9fa;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // 1. Configuración global de SweetAlert2 Toast
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            // 2. Disparar Toast si hay mensajes de sesión (Laravel)
            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif

            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: '{{ session('error') }}'
                });
            @endif

            // 3. Previsualización de imagen del Logo
            $('#logoInput').change(function() {
                const file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $('#preview').attr('src', event.target.result);
                    }
                    reader.readAsDataURL(file);

                    let fileName = $(this).val().split('\\').pop();
                    $(this).next('.custom-file-label').addClass("selected").html(fileName);
                }
            });

            // 4. Inicializar Select2 con capacidad de Tags (Nuevas opciones)
            if ($('.select2-tags').length) {
                $('.select2-tags').select2({
                    tags: true,
                    theme: 'bootstrap4',
                    placeholder: "-- SELECCIONAR --",
                    allowClear: true,
                    width: '100%'
                });
            }

            // 5. Auto-cerrar alertas tradicionales (si aún las usas)
            setTimeout(function() {
                $(".alert").fadeTo(500, 0).slideUp(500, function() {
                    $(this).remove();
                });
            }, 4000);
        });
    </script>
@stop
