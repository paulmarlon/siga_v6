@extends('adminlte::page')

@section('title', 'Turnos Académicos | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3">
        <h1 class="text-dark" style="font-weight: 500; font-size: 1.6rem;">
            <i class="fas fa-clock text-primary mr-2"></i> Administración de Turnos
        </h1>
        <a href="{{ route('admin.turnos.create') }}" class="btn btn-primary shadow-sm"
            style="background-color: #003366; border-color: #003366; border-radius: 4px;">
            <i class="fas fa-plus-circle mr-1"></i> NUEVO TURNO
        </a>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary shadow-sm border-0">
        {{-- Filtros de Estado --}}
        <div class="d-flex justify-content-end p-3 bg-white border-bottom">
            <div class="btn-group shadow-sm">
                <a href="{{ route('admin.turnos.index') }}"
                    class="btn btn-sm btn-flat {{ !request()->has('papelera') ? 'btn-primary' : 'btn-outline-secondary' }}">
                    <i class="fas fa-check-circle mr-1"></i> ACTIVOS
                </a>
                <a href="{{ route('admin.turnos.index', ['papelera' => 1]) }}"
                    class="btn btn-sm btn-flat {{ request()->has('papelera') ? 'btn-danger' : 'btn-outline-secondary' }}">
                    <i class="fas fa-trash-alt mr-1"></i> EN PAPELERA
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="turnos-table" class="table table-hover mb-0 w-100">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th class="px-4 py-3" width="10%">ID</th>
                            <th class="py-3" width="65%">NOMBRE DEL TURNO</th>
                            <th class="py-3 text-center" width="25%">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($turnos as $turno)
                            <tr style="{{ $turno->trashed() ? 'background-color: #fffafa;' : '' }}">
                                <td class="px-4 align-middle font-italic text-muted">#{{ $turno->id }}</td>
                                <td class="align-middle">
                                    <span class="font-weight-bold text-dark text-uppercase">{{ $turno->nombre }}</span>
                                    @if ($turno->trashed())
                                        <span class="badge badge-danger-light text-danger ml-2"
                                            style="font-size: 0.7rem; background: #ffebeb; padding: 3px 8px; border-radius: 10px;">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> ELIMINADO
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    <div class="btn-group">
                                        @if ($turno->trashed())
                                            <form action="{{ route('admin.turnos.restore', $turno->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-link text-info p-1"
                                                    title="Restaurar registro">
                                                    <i class="fas fa-trash-restore-alt fa-lg"></i>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('admin.turnos.edit', $turno) }}"
                                                class="btn btn-link p-1 text-success" title="Editar Registro">
                                                <i class="fas fa-edit fa-lg" style="color: #28a745;"></i>
                                            </a>
                                            <form action="{{ route('admin.turnos.destroy', $turno) }}" method="POST"
                                                class="form-eliminar d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-1"
                                                    title="Mover a papelera">
                                                    <i class="fas fa-trash-alt fa-lg"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script>
        $(document).ready(function() {
            // Inicialización de DataTable
            const table = $('#turnos-table').DataTable({
                responsive: true,
                autoWidth: false,
                ordering: true,
                order: [
                    [0, "desc"]
                ],
                dom: '<"row px-4 py-3"<"col-md-9"B><"col-md-3"f>>' +
                    'tr' +
                    '<"row px-4 py-3"<"col-md-5"i><"col-md-7"p>>',
                buttons: [{
                        extend: 'copy',
                        text: '<i class="fas fa-copy"></i> Copiar',
                        className: 'btn btn-secondary btn-sm btn-flat shadow-sm',
                        titleAttr: 'Copiar filas al portapapeles'
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="fas fa-columns"></i> Columnas',
                        className: 'btn btn-secondary btn-sm btn-flat shadow-sm',
                        titleAttr: 'Seleccionar columnas visibles'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success btn-sm btn-flat shadow-sm',
                        title: 'SIG@ - Reporte de Turnos',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        className: 'btn btn-info btn-sm btn-flat shadow-sm',
                        title: 'SIG@ - Reporte de Turnos'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger btn-sm btn-flat shadow-sm',
                        title: 'SIG@ - Reporte de Turnos',
                        orientation: 'portrait',
                        pageSize: 'LETTER',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Imprimir',
                        className: 'btn btn-dark btn-sm btn-flat shadow-sm',
                        titleAttr: 'Imprimir tabla actual',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        text: '<i class="fas fa-sync-alt"></i> Recargar',
                        className: 'btn btn-default btn-sm btn-flat shadow-sm border',
                        action: function(e, dt, node, config) {
                            window.location.reload();
                        }
                    }
                ],
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json",
                    search: "<b>BUSCAR:</b>",
                    searchPlaceholder: "Filtrar resultados...",
                    buttons: {
                        colvis: 'Ver Columnas',
                        copy: 'Copiar',
                        copyTitle: 'Copiado al portapapeles',
                        copySuccess: {
                            _: '%d filas copiadas',
                            1: '1 fila copiada'
                        }
                    }
                }
            });

            // --- LÓGICA DE SWEETALERT2 PARA SIG@ ---

            // 1. Interceptar el formulario de eliminación
            $('.form-eliminar').submit(function(e) {
                e.preventDefault();

                Swal.fire({
                    title: '¿Mover a la papelera?',
                    text: "El turno no estará disponible en las listas activas.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#003366', // Azul institucional del SIG@
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-trash-alt"></i> Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });

            // 2. Configuración de Toasts (Notificaciones pequeñas)
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            // 3. Disparar Toasts según la sesión de Laravel
            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif

            @if (session('info'))
                Toast.fire({
                    icon: 'info',
                    title: "{{ session('info') }}"
                });
            @endif

            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: "{{ session('error') }}"
                });
            @endif
        });
    </script>
@stop
