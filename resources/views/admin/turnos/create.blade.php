@extends('adminlte::page')

@section('title', 'Nuevo Turno | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
        <h1 class="text-dark" style="font-weight: 400; font-size: 1.5rem;">
            <i class="fas fa-plus-circle text-primary mr-2"></i> Registrar Nuevo Turno
        </h1>
        <a href="{{ route('admin.turnos.index') }}" class="btn btn-default btn-sm btn-flat shadow-sm border">
            <i class="fas fa-arrow-left mr-1"></i> VOLVER AL LISTADO
        </a>
    </div>
@stop

@section('content')
    <div class="row justify-content-center pt-3">
        <div class="col-md-7">
            <div class="card card-outline card-primary shadow-sm border-0" style="border-radius: 0;">
                <div class="card-header bg-light py-3">
                    <h3 class="card-title text-secondary" style="font-size: 0.9rem; font-weight: 700;">
                        <i class="fas fa-clock mr-2"></i> DEFINICIÓN DEL TURNO
                    </h3>
                </div>

                <form action="{{ route('admin.turnos.store') }}" method="POST" id="form-create">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            {{-- Campo Nombre del Turno --}}
                            <div class="col-md-12 form-group">
                                <label for="nombre">Nombre del Turno <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white text-muted">
                                            <i class="fas fa-hourglass-half"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="nombre" id="nombre"
                                        class="form-control @error('nombre') is-invalid @enderror"
                                        value="{{ old('nombre') }}" placeholder="Ej. MAÑANA, TARDE..." required
                                        maxlength="50" autofocus
                                        style="border-radius: 0; font-weight: 600; text-transform: uppercase; height: 38px;">

                                    @error('nombre')
                                        <span class="invalid-feedback font-weight-bold">{{ $message }}</span>
                                    @enderror
                                </div>
                                <p class="text-muted mt-2 mb-0" style="font-size: 0.8rem;">
                                    <i class="fas fa-info-circle mr-1 text-info"></i>
                                    Este nombre se utilizará para organizar los horarios de clases y asistencia.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light border-top py-3 text-right">
                        <button type="reset" class="btn btn-sm btn-flat btn-default border mr-2">
                            <i class="fas fa-eraser mr-1"></i> LIMPIAR
                        </button>
                        <button type="submit" class="btn btn-sm btn-flat btn-primary px-4 shadow-sm"
                            style="background-color: #003366; border-color: #003366;">
                            <i class="fas fa-save mr-1"></i> GUARDAR TURNO
                        </button>
                    </div>
                </form>
            </div>

            {{-- Card de ayuda visual --}}
            <div class="alert alert-secondary mt-3 border-0 shadow-none"
                style="background-color: #f4f6f9; border-left: 4px solid #adb5bd !important; border-radius: 0;">
                <h6 class="font-weight-bold text-dark" style="font-size: 0.85rem;">Consideraciones:</h6>
                <ul class="mb-0" style="font-size: 0.8rem; color: #555;">
                    <li>El nombre debe ser único en el sistema.</li>
                    <li>Evite duplicar turnos con nombres similares (ej. "MAÑANA" y "M").</li>
                    <li>Una vez guardado, podrá editarlo desde el listado principal.</li>
                </ul>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        /* Estilos personalizados para mantener la línea de diseño SIG@ */
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

        /* Animación suave para el foco del input */
        .form-control {
            transition: all 0.3s ease;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Convertir automáticamente a mayúsculas mientras escribe para consistencia visual
            $('#nombre').on('input', function() {
                this.value = this.value.toUpperCase();
            });

            // Confirmación antes de limpiar el formulario
            $('button[type="reset"]').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Limpiar formulario?',
                    text: "Se borrará todo lo que hayas escrito.",
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
