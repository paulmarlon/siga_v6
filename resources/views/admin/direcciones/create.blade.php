@extends('adminlte::page')

@section('title', 'Nuevo Registro | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
        <h1 class="text-dark" style="font-weight: 400; font-size: 1.5rem;">
            <i class="fas fa-plus-square text-secondary mr-2"></i> Nuevo Registro de Dirección
        </h1>
        <a href="{{ route('admin.direcciones.index') }}" class="btn btn-default btn-sm border">
            <i class="fas fa-arrow-left mr-1"></i> VOLVER AL LISTADO
        </a>
    </div>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card card-flat shadow-none border mt-3">
                <div class="card-header bg-light border-bottom">
                    <h3 class="card-title text-uppercase text-secondary" style="font-size: 0.85rem; font-weight: 700;">
                        Información Geográfica y Localización
                    </h3>
                </div>

                <form action="{{ route('admin.direcciones.store') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="card-body bg-white">
                        <div class="row">
                            {{-- Departamento --}}
                            <div class="col-md-6 mb-3">
                                <label for="departamento" class="text-secondary small font-weight-bold">DEPARTAMENTO /
                                    ESTADO</label>
                                <input type="text" name="departamento" id="departamento"
                                    class="form-control form-control-sm border-secondary-subtle @error('departamento') is-invalid @enderror"
                                    value="{{ old('departamento') }}" placeholder="Ej. La Paz" required>
                                @error('departamento')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Provincia --}}
                            <div class="col-md-6 mb-3">
                                <label for="provincia" class="text-secondary small font-weight-bold">PROVINCIA /
                                    REGIÓN</label>
                                <input type="text" name="provincia" id="provincia"
                                    class="form-control form-control-sm @error('provincia') is-invalid @enderror"
                                    value="{{ old('provincia') }}" placeholder="Ej. Murillo">
                                @error('provincia')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Ciudad --}}
                            <div class="col-md-6 mb-3">
                                <label for="ciudad" class="text-secondary small font-weight-bold">CIUDAD /
                                    MUNICIPIO</label>
                                <input type="text" name="ciudad" id="ciudad"
                                    class="form-control form-control-sm @error('ciudad') is-invalid @enderror"
                                    value="{{ old('ciudad') }}" placeholder="Ej. El Alto">
                                @error('ciudad')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Zona --}}
                            <div class="col-md-6 mb-3">
                                <label for="zona" class="text-secondary small font-weight-bold">ZONA / BARRIO /
                                    COMUNIDAD</label>
                                <input type="text" name="zona" id="zona"
                                    class="form-control form-control-sm @error('zona') is-invalid @enderror"
                                    value="{{ old('zona') }}" placeholder="Ej. Villa Adela">
                                @error('zona')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Detalle Adicional --}}
                            <div class="col-12">
                                <label for="detalle" class="text-secondary small font-weight-bold">DETALLE DE DIRECCIÓN /
                                    REFERENCIAS</label>
                                <textarea name="detalle" id="detalle" rows="3"
                                    class="form-control form-control-sm @error('detalle') is-invalid @enderror"
                                    placeholder="Calle, número de casa, referencias visuales...">{{ old('detalle') }}</textarea>
                                @error('detalle')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="text-muted italic">Proporcione información específica para facilitar
                                    auditorías de campo.</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light border-top d-flex justify-content-end">
                        <button type="reset" class="btn btn-default btn-flat btn-sm mr-2 border">
                            <i class="fas fa-eraser mr-1"></i> LIMPIAR
                        </button>
                        <button type="submit" class="btn btn-primary btn-flat btn-sm"
                            style="background-color: #003366; border-color: #003366;">
                            <i class="fas fa-save mr-1"></i> GUARDAR REGISTRO
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        /* Consistencia Institucional */
        .card-flat {
            border-radius: 0;
        }

        .btn-flat {
            border-radius: 0 !important;
        }

        .form-control-sm {
            border-radius: 0;
            border: 1px solid #ced4da;
        }

        .form-control-sm:focus {
            border-color: #003366;
            box-shadow: none;
        }

        label {
            margin-bottom: 2px;
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Confirmación institucional antes de limpiar
            $('button[type="reset"]').click(function(e) {
                // Evitamos que limpie automáticamente para preguntar primero
                e.preventDefault();

                // Solo preguntar si hay algo escrito
                let form = $(this).closest('form');
                let hasContent = false;
                form.find('input, textarea').each(function() {
                    if ($(this).val() !== "") {
                        hasContent = true;
                        return false;
                    }
                });

                if (!hasContent) return;

                Swal.fire({
                    title: 'CONFIRMACIÓN',
                    text: "¿Desea vaciar todos los campos del formulario?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#6c757d',
                    cancelButtonColor: '#dee2e6',
                    confirmButtonText: 'SÍ, LIMPIAR',
                    cancelButtonText: 'CANCELAR',
                    heightAuto: false,
                    customClass: {
                        confirmButton: 'btn-flat',
                        cancelButton: 'btn-flat text-dark'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Forzamos la limpieza manual de cada campo
                        form.find('input, textarea').val('');
                        // Quitamos las clases de error de Laravel si existieran
                        form.find('.is-invalid').removeClass('is-invalid');
                        form.find('.invalid-feedback').remove();
                    }
                });
            });
        });
    </script>
@stop
