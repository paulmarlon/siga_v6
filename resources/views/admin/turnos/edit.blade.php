@extends('adminlte::page')

@section('title', 'Editar Turno | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
        <h1 class="text-dark" style="font-weight: 400; font-size: 1.5rem;">
            <i class="fas fa-edit mr-2" style="color: #155724;"></i> Actualizar Turno
        </h1>
        <a href="{{ route('admin.turnos.index') }}" class="btn btn-default btn-sm btn-flat shadow-sm border">
            <i class="fas fa-arrow-left mr-1"></i> VOLVER AL LISTADO
        </a>
    </div>
@stop

@section('content')
    <div class="row justify-content-center pt-3">
        <div class="col-md-7">
            <div class="card card-outline shadow-sm border-0" style="border-radius: 0; border-top: 3px solid #155724;">
                <div class="card-header bg-light py-3">
                    <h3 class="card-title text-secondary" style="font-size: 0.9rem; font-weight: 700;">
                        <i class="fas fa-history mr-2"></i> MODIFICAR INFORMACIÓN
                    </h3>
                </div>

                <form action="{{ route('admin.turnos.update', $turno) }}" method="POST" id="form-edit">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
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
                                        value="{{ old('nombre', $turno->nombre) }}" placeholder="Ej. MAÑANA, TARDE..."
                                        required maxlength="50" autofocus
                                        style="border-radius: 0; font-weight: 600; text-transform: uppercase; height: 38px;">

                                    @error('nombre')
                                        <span class="invalid-feedback font-weight-bold">{{ $message }}</span>
                                    @enderror
                                </div>
                                <p class="text-muted mt-2 mb-0" style="font-size: 0.8rem;">
                                    <i class="fas fa-exclamation-triangle mr-1 text-warning"></i>
                                    Al cambiar el nombre, se actualizará en todos los registros asociados.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light border-top py-3 text-right">
                        <a href="{{ route('admin.turnos.index') }}" class="btn btn-sm btn-flat btn-default border mr-2">
                            <i class="fas fa-times mr-1"></i> CANCELAR
                        </a>
                        <button type="submit" class="btn btn-sm btn-flat px-4 shadow-sm text-white"
                            style="background-color: #155724; border-color: #155724;">
                            <i class="fas fa-sync-alt mr-1"></i> ACTUALIZAR TURNO
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
            border-color: #155724;
            box-shadow: none;
            background-color: #fdfdfd;
        }

        .form-control {
            transition: all 0.3s ease;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Mayúsculas automáticas
            $('#nombre').on('input', function() {
                this.value = this.value.toUpperCase();
            });

            // Alerta de confirmación al enviar
            $('#form-edit').on('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Guardar cambios?',
                    text: "Se modificará la información del turno.",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#155724',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, actualizar',
                    cancelButtonText: 'Revisar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    </script>
@stop
