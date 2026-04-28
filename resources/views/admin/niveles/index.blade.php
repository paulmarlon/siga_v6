@extends('adminlte::page')

@section('title', 'Niveles Académicos | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3">
        <h1 class="text-dark" style="font-weight: 500; font-size: 1.6rem;">
            <i class="fas fa-layer-group text-primary mr-2"></i> Administración de Niveles Académicos
        </h1>
        <a href="{{ route('admin.niveles.create') }}" class="btn btn-primary shadow-sm"
            style="background-color: #003366; border-color: #003366; border-radius: 4px;">
            <i class="fas fa-plus-circle mr-1"></i> NUEVO NIVEL
        </a>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary shadow-sm border-0">
        {{-- Filtros de Estado --}}
        <div class="d-flex justify-content-end p-3 bg-white border-bottom">
            <div class="btn-group shadow-sm">
                <a href="{{ route('admin.niveles.index') }}"
                    class="btn btn-sm btn-flat {{ !request()->has('papelera') ? 'btn-primary' : 'btn-outline-secondary' }}">
                    <i class="fas fa-check-circle mr-1"></i> ACTIVOS
                </a>
                <a href="{{ route('admin.niveles.index', ['papelera' => 1]) }}"
                    class="btn btn-sm btn-flat {{ request()->has('papelera') ? 'btn-danger' : 'btn-outline-secondary' }}">
                    <i class="fas fa-trash-alt mr-1"></i> EN PAPELERA
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="niveles-table" class="table table-hover mb-0 w-100">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th class="px-4 py-3" width="10%">ID</th>
                            <th class="py-3" width="40%">NOMBRE DEL NIVEL</th>
                            <th class="py-3 text-center" width="25%">ESTADO</th>
                            <th class="py-3 text-center" width="25%">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($niveles as $nivel)
                            <tr style="{{ $nivel->trashed() ? 'background-color: #fffafa;' : '' }}">
                                <td class="px-4 align-middle font-italic text-muted">#{{ $nivel->id }}</td>
                                <td class="align-middle">
                                    <span class="font-weight-bold text-dark text-uppercase">{{ $nivel->nombre }}</span>
                                    @if ($nivel->trashed())
                                        <span class="badge badge-danger-light text-danger ml-2"
                                            style="font-size: 0.7rem; background: #ffebeb; padding: 3px 8px; border-radius: 10px;">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> ELIMINADO
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    <span
                                        class="badge {{ optional($nivel->estado)->slug == 'activo' ? 'badge-success' : 'badge-secondary' }} text-uppercase px-2 py-1"
                                        style="font-size: 0.75rem;">
                                        {{ optional($nivel->estado)->nombre ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="btn-group">
                                        @if ($nivel->trashed())
                                            <form action="{{ route('admin.niveles.restore', $nivel->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-link text-info p-1"
                                                    title="Restaurar registro">
                                                    <i class="fas fa-trash-restore-alt fa-lg"></i>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('admin.niveles.edit', $nivel) }}"
                                                class="btn btn-link p-1 text-success" title="Editar Registro">
                                                <i class="fas fa-edit fa-lg"></i>
                                            </a>
                                            <form action="{{ route('admin.niveles.destroy', $nivel) }}" method="POST"
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
            // Inicialización de DataTable con Botones de Exportación
            const table = $('#niveles-table').DataTable({
                responsive: true,
                autoWidth: false,
                ordering: true,
                order: [
                    [0, "desc"]
                ],
                dom: '<"row px-4 py-3"<"col-md-9"B><"col-md-3"f>>' + 'tr' +
                    '<"row px-4 py-3"<"col-md-5"i><"col-md-7"p>>',
                buttons: [{
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success btn-sm btn-flat shadow-sm',
                        title: 'SIG@ - Reporte de Niveles Académicos',
                        exportOptions: {
                            columns: [0, 1, 2]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger btn-sm btn-flat shadow-sm',
                        title: 'SIG@ - Reporte de Niveles Académicos',
                        pageSize: 'LETTER',
                        exportOptions: {
                            columns: [0, 1, 2]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Imprimir',
                        className: 'btn btn-dark btn-sm btn-flat shadow-sm',
                        exportOptions: {
                            columns: [0, 1, 2]
                        }
                    },
                    {
                        text: '<i class="fas fa-sync-alt"></i>',
                        className: 'btn btn-default btn-sm btn-flat shadow-sm border',
                        action: function() {
                            window.location.reload();
                        }
                    }
                ],
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json",
                    search: "<b>BUSCAR:</b>",
                    searchPlaceholder: "Filtrar niveles..."
                }
            });

            // SweetAlert2: Confirmación de eliminación
            $('.form-eliminar').submit(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Mover a la papelera?',
                    text: "El nivel no aparecerá en las listas de inscripción.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#003366',
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

            // Lógica de Toasts (Notificaciones)
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
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
