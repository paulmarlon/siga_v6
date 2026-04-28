@extends('adminlte::page')

@section('title', 'Gestiones | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
        <h1 class="text-dark" style="font-weight: 400; font-size: 1.5rem;">
            <i class="fas fa-calendar-alt text-secondary mr-2"></i> Administración de Gestiones
        </h1>
        <a href="{{ route('admin.gestiones.create') }}" class="btn btn-primary btn-sm shadow-sm"
            style="background-color: #003366; border-color: #003366; border-radius: 0;">
            <i class="fas fa-plus mr-1"></i> NUEVA GESTIÓN
        </a>
    </div>
@stop

@section('content')
    <div class="card card-flat shadow-none border">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="gestiones-table" class="table table-sm table-hover mb-0">
                    <thead style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <tr class="text-secondary"
                            style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <th class="pl-3 py-3" width="10%">ID</th>
                            <th class="py-3" width="30%">Gestión Anual</th>
                            <th class="py-3 text-center" width="20%">Estado Actual</th>
                            <th class="py-3 text-center" width="20%">Vigencia</th>
                            <th class="py-3 text-center" width="20%">Operaciones</th>
                        </tr>
                    </thead>
                    <tbody style="color: #333; font-size: 1rem;">
                        @foreach ($gestiones as $gestion)
                            <tr
                                style="border-bottom: 1px solid #f2f2f2; {{ $gestion->trashed() ? 'background-color: #f9f9f9; opacity: 0.7;' : '' }}">
                                <td class="pl-3 align-middle text-muted">#{{ $gestion->id }}</td>

                                <td class="align-middle">
                                    {{ $gestion->nombre }}
                                    @if ($gestion->trashed())
                                        <small class="text-danger ml-2">(ELIMINADO)</small>
                                    @endif
                                </td>

                                <td class="text-center align-middle">
                                    {{ strtoupper($gestion->estado->nombre) }}
                                </td>
                                <td class="text-center align-middle">
                                    @if ($gestion->trashed())
                                        {{-- Botón para restaurar: Gris sobrio con icono --}}
                                        <form action="{{ route('admin.gestiones.restore', $gestion->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-xs btn-outline-secondary"
                                                style="font-size: 0.7rem; font-weight: 800; border-radius: 0;">
                                                <i class="fas fa-trash-restore-alt mr-1"></i> RESTAURAR
                                            </button>
                                        </form>
                                    @elseif ($gestion->estado->slug == 'activo')
                                        {{-- Gestión Vigente: Texto en negrita con icono de check --}}
                                        <span class="text-dark"
                                            style="font-weight: 800; font-size: 0.85rem; letter-spacing: 0.5px;">
                                            <i class="fas fa-check-circle mr-1 text-success"></i> VIGENTE
                                        </span>
                                    @else
                                        {{-- Botón Activar: Azul formal con icono de encendido --}}
                                        <form action="{{ route('admin.gestiones.activar', $gestion->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-xs btn-link text-primary p-0"
                                                style="font-weight: 800; font-size: 0.75rem; text-decoration: none; text-transform: uppercase;">
                                                <i class="fas fa-power-off mr-1"></i> Activar
                                            </button>
                                        </form>
                                    @endif
                                </td>

                                <td class="text-center align-middle">
                                    @if (!$gestion->trashed())
                                        {{-- Botones de Edit y Delete solo si no está borrado --}}
                                        <div class="btn-group">
                                            <a href="{{ route('admin.gestiones.edit', $gestion) }}"
                                                class="btn btn-sm text-secondary"><i class="fas fa-pen-square"></i></a>
                                            <form action="{{ route('admin.gestiones.destroy', $gestion) }}" method="POST"
                                                class="form-eliminar d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm text-muted"><i
                                                        class="fas fa-trash-alt"></i></button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        body {
            font-family: 'Source Sans Pro', sans-serif;
        }

        .card {
            border-radius: 0;
        }

        .table thead th {
            vertical-align: middle;
            border-top: 0;
        }

        .table td {
            vertical-align: middle;
        }

        .btn-flat {
            border-radius: 0 !important;
        }

        .dataTables_filter {
            text-align: right !important;
            padding: 10px;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // 1. DataTable (Mantenido)
            $('#gestiones-table').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "ordering": false, // Desactivamos para que mande tu orderBy del controlador
                "dom": '<"row mx-0 border-bottom py-2"<"col-sm-12"f>>rt<"row mx-0 pt-2"<"col-sm-6"i><"col-sm-6"p>>',
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
                }
            });

            // 2. Manejador ACTIVAR (Delegación para paginación)
            $(document).on('submit', '.form-activar', function(e) {
                e.preventDefault();
                var formulario = this;
                Swal.fire({
                    title: '¿CAMBIAR GESTIÓN VIGENTE?',
                    text: "Se activará esta gestión y las demás pasarán a estado inactivo.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#003366',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'SÍ, ACTIVAR',
                    cancelButtonText: 'CANCELAR',
                    customClass: {
                        confirmButton: 'btn-flat',
                        cancelButton: 'btn-flat'
                    }
                }).then((result) => {
                    if (result.isConfirmed) formulario.submit();
                });
            });

            // 3. Manejador ELIMINAR
            $(document).on('submit', '.form-eliminar', function(e) {
                e.preventDefault();
                var formulario = this;
                Swal.fire({
                    title: '¿ELIMINAR REGISTRO?',
                    text: "Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'ELIMINAR',
                    cancelButtonText: 'CANCELAR',
                    customClass: {
                        confirmButton: 'btn-flat',
                        cancelButton: 'btn-flat'
                    }
                }).then((result) => {
                    if (result.isConfirmed) formulario.submit();
                });
            });

            // 4. Alertas Toast (Mantenido)
            @if (session('mensaje'))
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                Toast.fire({
                    icon: "{{ session('icono') }}",
                    title: "{{ session('mensaje') }}"
                });
            @endif
        });
    </script>
@stop
