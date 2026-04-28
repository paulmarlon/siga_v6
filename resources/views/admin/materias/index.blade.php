@extends('adminlte::page')

@section('title', 'Catálogo de Materias | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3">
        <h1 class="text-dark" style="font-weight: 500; font-size: 1.6rem;">
            <i class="fas fa-book text-primary mr-2"></i> Catálogo de Materias
        </h1>
        <a href="{{ route('admin.materias.create') }}" class="btn btn-primary shadow-sm"
            style="background-color: #003366; border-color: #003366; border-radius: 4px;">
            <i class="fas fa-plus-circle mr-1"></i> NUEVA MATERIA
        </a>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary shadow-sm border-0">
        {{-- Filtros de Estado y Navegación --}}
        <div class="d-flex justify-content-between p-3 bg-white border-bottom">
            <div>
                <h5 class="text-muted m-0"><i class="fas fa-filter mr-2"></i>Listado General</h5>
            </div>
            <div class="btn-group shadow-sm">
                <a href="{{ route('admin.materias.index') }}"
                    class="btn btn-sm btn-flat {{ !request()->has('papelera') ? 'btn-primary' : 'btn-outline-secondary' }}">
                    <i class="fas fa-check-circle mr-1"></i> ACTIVAS
                </a>
                <a href="{{ route('admin.materias.index', ['papelera' => 1]) }}"
                    class="btn btn-sm btn-flat {{ request()->has('papelera') ? 'btn-danger' : 'btn-outline-secondary' }}">
                    <i class="fas fa-trash-alt mr-1"></i> EN PAPELERA
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="materias-table" class="table table-hover mb-0 w-100">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th class="px-4 py-3">SIGLA</th>
                            <th class="py-3">NOMBRE DE LA MATERIA</th>
                            <th class="py-3 text-center">TIPO</th>
                            <th class="py-3 text-center">HORAS</th>
                            <th class="py-3 text-center">ESTADO</th>
                            <th class="py-3 text-center">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materias as $materia)
                            <tr style="{{ $materia->trashed() ? 'background-color: #fffafa;' : '' }}">
                                <td class="px-4 align-middle">
                                    <code class="text-primary font-weight-bold"
                                        style="font-size: 1rem;">{{ $materia->sigla }}</code>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex flex-column">
                                        <span
                                            class="font-weight-bold text-dark text-uppercase">{{ $materia->nombre }}</span>
                                        @if ($materia->es_comun)
                                            <small class="text-info"><i class="fas fa-users-cog"></i> Tronco Común</small>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center align-middle">
                                    @php
                                        $badgeColor =
                                            [
                                                'Teorica' => 'info',
                                                'Tecnica' => 'warning',
                                                'Laboratorio' => 'purple',
                                            ][$materia->tipo_materia] ?? 'secondary';
                                    @endphp
                                    <span class="badge badge-{{ $badgeColor }}"
                                        style="{{ $materia->tipo_materia == 'Laboratorio' ? 'background-color: #6f42c1; color: white;' : '' }}">
                                        {{ $materia->tipo_materia }}
                                    </span>
                                </td>
                                <td class="text-center align-middle font-weight-bold">
                                    {{ $materia->horas_academicas }} <small>Hrs</small>
                                </td>
                                <td class="text-center align-middle">
                                    @if ($materia->trashed())
                                        <span class="badge badge-danger">ELIMINADO</span>
                                    @else
                                        <span class="badge shadow-sm"
                                            style="background-color: {{ $materia->estado->color_hex }}; color: white;">
                                            {{ $materia->estado->nombre }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    <div class="btn-group">
                                        @if ($materia->trashed())
                                            <form action="{{ route('admin.materias.restore', $materia->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-link text-info p-1"
                                                    title="Restaurar registro">
                                                    <i class="fas fa-trash-restore-alt fa-lg"></i>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('admin.materias.edit', $materia) }}"
                                                class="btn btn-link p-1 text-success" title="Editar Registro">
                                                <i class="fas fa-edit fa-lg"></i>
                                            </a>
                                            <form action="{{ route('admin.materias.destroy', $materia) }}" method="POST"
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
            const table = $('#materias-table').DataTable({
                responsive: true,
                autoWidth: false,
                order: [
                    [0, "asc"]
                ],
                dom: '<"row px-4 py-3"<"col-md-8"B><"col-md-4"f>>' + 'tr' +
                    '<"row px-4 py-3"<"col-md-5"i><"col-md-7"p>>',
                buttons: [{
                        extend: 'copy',
                        text: '<i class="fas fa-copy"></i> Copiar',
                        className: 'btn btn-secondary btn-sm btn-flat shadow-sm'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success btn-sm btn-flat shadow-sm',
                        title: 'SIG@ - Catálogo de Materias'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger btn-sm btn-flat shadow-sm',
                        orientation: 'landscape',
                        pageSize: 'LETTER'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Imprimir',
                        className: 'btn btn-dark btn-sm btn-flat shadow-sm'
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
                    searchPlaceholder: "Sigla o Nombre..."
                }
            });

            // SweetAlert para eliminación
            $('.form-eliminar').submit(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Mover a la papelera?',
                    text: "La materia ya no podrá asignarse a nuevas ofertas académicas.",
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

            // Toasts de Notificación
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
