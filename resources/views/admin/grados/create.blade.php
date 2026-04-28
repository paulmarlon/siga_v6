@extends('adminlte::page')

@section('title', 'Nuevo Grado | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
        <h1 class="text-dark" style="font-weight: 400; font-size: 1.5rem;">
            <i class="fas fa-plus-circle text-primary mr-2"></i> Registrar Nuevo Grado
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
                        <i class="fas fa-graduation-cap mr-2"></i> CONFIGURACIÓN DEL GRADO ACADÉMICO
                    </h3>
                </div>

                <form action="{{ route('admin.grados.store') }}" method="POST" id="form-create">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            {{-- Campo Nombre del Grado --}}
                            <div class="col-md-8 form-group">
                                <label for="nombre">Nombre del Grado <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white text-muted">
                                            <i class="fas fa-tag"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="nombre" id="nombre"
                                        class="form-control @error('nombre') is-invalid @enderror"
                                        value="{{ old('nombre') }}" placeholder="Ej. PRIMER SEMESTRE" required
                                        maxlength="100" autofocus
                                        style="border-radius: 0; font-weight: 600; text-transform: uppercase;">
                                    @error('nombre')
                                        <span class="invalid-feedback font-weight-bold">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Campo Orden Autocalculado --}}
                            <div class="col-md-4 form-group">
                                <label for="orden">Orden <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white text-muted">
                                            <i class="fas fa-sort-numeric-down"></i>
                                        </span>
                                    </div>
                                    <input type="number" name="orden" id="orden"
                                        class="form-control @error('orden') is-invalid @enderror"
                                        value="{{ old('orden', $siguienteOrden) }}" min="0" required
                                        style="border-radius: 0; font-weight: bold; color: #003366;">
                                    @error('orden')
                                        <span class="invalid-feedback font-weight-bold">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Selector de Nivel Académico --}}
                            <div class="col-md-6 form-group mt-2">
                                <label for="nivel_id">Nivel Académico <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white text-muted">
                                            <i class="fas fa-layer-group"></i>
                                        </span>
                                    </div>
                                    <select name="nivel_id" id="nivel_id"
                                        class="form-control @error('nivel_id') is-invalid @enderror"
                                        style="border-radius: 0;" required>
                                        <option value="" disabled selected>-- Seleccione nivel --</option>
                                        @foreach ($niveles as $nivel)
                                            <option value="{{ $nivel->id }}"
                                                {{ old('nivel_id') == $nivel->id ? 'selected' : '' }}>
                                                {{ strtoupper($nivel->nombre) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('nivel_id')
                                        <span class="invalid-feedback font-weight-bold">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Selector de Ciclo (NUEVO) --}}
                            <div class="col-md-6 form-group mt-2">
                                <label for="ciclo">Ciclo Académico <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white text-muted">
                                            <i class="fas fa-sync-alt"></i>
                                        </span>
                                    </div>
                                    <select name="ciclo" id="ciclo"
                                        class="form-control @error('ciclo') is-invalid @enderror" style="border-radius: 0;"
                                        required>
                                        <option value="1" {{ old('ciclo') == '1' ? 'selected' : '' }}>TRONCO COMÚN
                                            (CICLO 1)</option>
                                        <option value="2" {{ old('ciclo') == '2' ? 'selected' : '' }}>ESPECIALIDAD
                                            (CICLO 2)</option>
                                    </select>
                                    @error('ciclo')
                                        <span class="invalid-feedback font-weight-bold">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12 mt-3">
                                <div class="p-3 bg-light border-left border-info">
                                    <p class="mb-0 small text-muted">
                                        <i class="fas fa-info-circle text-info mr-1"></i>
                                        <strong>Nota de Sistema:</strong> Al guardar, este grado se registrará
                                        automáticamente con estado
                                        <span class="badge badge-success shadow-none"
                                            style="font-size: 0.65rem;">ACTIVO</span>.
                                    </p>
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
                            <i class="fas fa-save mr-1"></i> GUARDAR GRADO
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
            // Forzar mayúsculas en tiempo real
            $('#nombre').on('input', function() {
                this.value = this.value.toUpperCase();
            });

            // Confirmación de limpieza con SweetAlert2
            $('button[type="reset"]').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Limpiar formulario?',
                    text: "Se borrarán los datos ingresados.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#6c757d',
                    cancelButtonColor: '#343a40',
                    confirmButtonText: 'Sí, limpiar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#form-create')[0].reset();
                        $('#nombre').focus();
                    }
                });
            });
        });
    </script>
@stop
