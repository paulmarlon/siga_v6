@extends('adminlte::page')

@section('title', 'Editar Registro | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
        <h1 class="text-dark" style="font-weight: 400; font-size: 1.5rem;">
            <i class="fas fa-user-edit text-secondary mr-2"></i> Editar Registro de Persona
        </h1>
        <a href="{{ route('admin.personas.index') }}" class="btn btn-default btn-sm border btn-flat">
            <i class="fas fa-arrow-left mr-1"></i> VOLVER AL LISTADO
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <form action="{{ route('admin.personas.update', $persona->id) }}" method="POST" enctype="multipart/form-data"
            autocomplete="off">
            @csrf
            @method('PUT')
            <div class="row mt-3">
                {{-- SECCIÓN IZQUIERDA: IDENTIDAD Y LOCALIZACIÓN --}}
                <div class="col-md-8">
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
                                        <input type="text" name="ci" value="{{ old('ci', $persona->ci) }}"
                                            class="form-control @error('ci') is-invalid @enderror"
                                            oninput="this.value = this.value.toUpperCase().replace(/[^0-9A-Z-]/g, '')"
                                            maxlength="15" required>
                                    </div>
                                    @error('ci')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Nombres --}}
                                <div class="col-md-8 mb-3">
                                    <label class="text-secondary small font-weight-bold">NOMBRES *</label>
                                    <input type="text" name="nombres" value="{{ old('nombres', $persona->nombres) }}"
                                        class="form-control form-control-sm @error('nombres') is-invalid @enderror"
                                        style="text-transform: uppercase;" required>
                                    @error('nombres')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Apellidos --}}
                                <div class="col-md-6 mb-3">
                                    <label class="text-secondary small font-weight-bold">APELLIDO PATERNO</label>
                                    <input type="text" name="ap_paterno"
                                        value="{{ old('ap_paterno', $persona->ap_paterno) }}"
                                        class="form-control form-control-sm text-uppercase">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-secondary small font-weight-bold">APELLIDO MATERNO</label>
                                    <input type="text" name="ap_materno"
                                        value="{{ old('ap_materno', $persona->ap_materno) }}"
                                        class="form-control form-control-sm text-uppercase">
                                </div>

                                {{-- Fecha Nacimiento, Sexo y Celular --}}
                                <div class="col-md-4 mb-3">
                                    <label class="text-secondary small font-weight-bold">FECHA NACIMIENTO</label>
                                    {{-- Busca esta línea en tu edit.blade.php y cámbiala por esta: --}}

                                    <input type="date" name="fecha_nacimiento"
                                        value="{{ old('fecha_nacimiento', $persona->fecha_nacimiento ? \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('Y-m-d') : '') }}"
                                        class="form-control form-control-sm">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="text-secondary small font-weight-bold">SEXO *</label>
                                    <select name="sexo" class="form-control form-control-sm" required>
                                        <option value="M" {{ old('sexo', $persona->sexo) == 'M' ? 'selected' : '' }}>
                                            MASCULINO</option>
                                        <option value="F" {{ old('sexo', $persona->sexo) == 'F' ? 'selected' : '' }}>
                                            FEMENINO</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="text-secondary small font-weight-bold">CELULAR *</label>
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light"><i class="fas fa-mobile-alt"></i></span>
                                        </div>
                                        <input type="text" name="celular"
                                            value="{{ old('celular', $persona->celular) }}"
                                            class="form-control @error('celular') is-invalid @enderror" maxlength="8"
                                            required>
                                    </div>
                                </div>

                                {{-- Email --}}
                                <div class="col-12 mb-2">
                                    <label class="text-secondary small font-weight-bold">EMAIL PERSONAL *</label>
                                    <input type="email" name="email_personal"
                                        value="{{ old('email_personal', $persona->email_personal) }}"
                                        class="form-control form-control-sm @error('email_personal') is-invalid @enderror"
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card Localización --}}
                    @php $tiene_dir = $persona->direccion_id ? true : false; @endphp
                    <div class="card card-flat shadow-none border">
                        <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center"
                            style="border-left: 4px solid #17a2b8 !important;">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="habilitar_direccion"
                                    name="habilitar_direccion" value="1"
                                    {{ old('habilitar_direccion', $tiene_dir) ? 'checked' : '' }}>
                                <label class="custom-control-label small font-weight-bold text-secondary text-uppercase"
                                    for="habilitar_direccion">
                                    <i class="fas fa-map-marker-alt mr-1"></i> ¿REGISTRAR DIRECCIÓN?
                                </label>
                            </div>
                        </div>
                        <div class="card-body bg-white py-3" id="seccion-direccion"
                            style="{{ old('habilitar_direccion', $tiene_dir) ? '' : 'opacity: 0.5; pointer-events: none;' }}">

                            <div class="row">
                                {{-- Selects con valores cargados para Select2 --}}
                                @foreach (['pais', 'departamento', 'provincia', 'ciudad', 'zona'] as $campo)
                                    <div class="col-md-{{ $campo == 'ciudad' || $campo == 'zona' ? '6' : '4' }} mb-3">
                                        <label
                                            class="text-secondary small font-weight-bold text-uppercase">{{ $campo }}</label>
                                        <select name="{{ $campo }}" class="form-control select2-ajax">
                                            @if ($persona->direccion && $persona->direccion->$campo)
                                                <option value="{{ $persona->direccion->$campo }}" selected>
                                                    {{ $persona->direccion->$campo }}</option>
                                            @endif
                                        </select>
                                    </div>
                                @endforeach

                                {{-- Detalle Desglosado --}}
                                <div class="col-md-9 mb-3">
                                    <label class="text-secondary small font-weight-bold">AVENIDA / CALLE / PASAJE</label>
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend"><span class="input-group-text bg-light"><i
                                                    class="fas fa-road"></i></span></div>
                                        <input type="text" name="direccion_calle"
                                            value="{{ old('direccion_calle', $calle) }}"
                                            class="form-control text-uppercase">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="text-secondary small font-weight-bold">NRO. VIVIENDA</label>
                                    <input type="text" name="direccion_nro" value="{{ old('direccion_nro', $nro) }}"
                                        class="form-control form-control-sm text-uppercase">
                                </div>
                                <div class="col-12">
                                    <label class="text-secondary small font-weight-bold">REFERENCIAS ADICIONALES</label>
                                    <textarea name="direccion_referencia" rows="2" class="form-control form-control-sm text-uppercase">{{ old('direccion_referencia', $ref) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN DERECHA --}}
                <div class="col-md-4">
                    <div class="card card-flat shadow-none border mb-3">
                        <div class="card-header bg-light py-2">
                            <span class="small font-weight-bold text-secondary text-uppercase"><i
                                    class="fas fa-camera mr-1"></i> Fotografía</span>
                        </div>
                        <div class="card-body text-center bg-white py-3">
                            <img id="img-preview"
                                src="{{ $persona->foto_path ? asset('storage/' . $persona->foto_path) : asset('img/default-avatar.png') }}"
                                class="img-thumbnail elevation-1 mb-3"
                                style="width: 180px; height: 200px; object-fit: cover;">
                            <div class="input-group input-group-sm">
                                <div class="custom-file">
                                    <input type="file" name="foto_path" id="foto_path" class="custom-file-input"
                                        accept="image/*">
                                    <label class="custom-file-label text-left text-xs" for="foto_path">Cambiar
                                        imagen...</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Información de Cuenta (Solo lectura o estado) --}}
                    <div class="card card-flat border mb-3" style="border-left: 4px solid #17a2b8 !important;">
                        <div class="card-body py-2">
                            <span class="small font-weight-bold text-secondary">ESTADO DE ACCESO</span><br>
                            @if ($persona->user)
                                <span class="badge badge-success">CUENTA ACTIVA:
                                    {{ $persona->user->username ?? $persona->ci }}</span>
                            @else
                                <span class="badge badge-warning">SIN ACCESO AL SISTEMA</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-flat shadow-none border mt-2">
                <div class="card-footer bg-light d-flex justify-content-end py-3">
                    <button type="submit" class="btn btn-primary btn-flat btn-sm px-4"
                        style="background-color: #003366; border-color: #003366;">
                        <i class="fas fa-sync-alt mr-1"></i> ACTUALIZAR REGISTRO
                    </button>
                </div>
            </div>
        </form>
    </div>
@stop

{{-- Los bloques de CSS y JS se mantienen iguales al Create, ya que Select2 y Previsualización funcionan igual --}}
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

        .select2-container--bootstrap4 .select2-selection--single {
            border-radius: 0 !important;
            height: calc(1.8125rem + 2px) !important;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Reutilizamos toda tu lógica de JS del create
            $('#foto_path').change(function() {
                if (this.files && this.files[0]) {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        $('#img-preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });

            $('.select2-ajax').select2({
                theme: 'bootstrap4',
                tags: true,
                placeholder: "-- Seleccione o escriba --",
                allowClear: false,
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

            $('#habilitar_direccion').change(function() {
                const isChecked = $(this).is(':checked');
                $('#seccion-direccion').css({
                    'opacity': isChecked ? '1' : '0.5',
                    'pointer-events': isChecked ? 'auto' : 'none'
                });
            });
        });
    </script>
@stop
