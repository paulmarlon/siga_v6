@extends('adminlte::page')

@section('title', 'Grados Académicos | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3">
        <h1 class="text-dark" style="font-weight: 500; font-size: 1.6rem;">
            <i class="fas fa-layer-group text-primary mr-2"></i> Gestión de Grados
        </h1>
        <a href="{{ route('admin.grados.create') }}" class="btn btn-primary shadow-sm"
            style="background-color: #003366; border-color: #003366; border-radius: 4px;">
            <i class="fas fa-plus-circle mr-1"></i> NUEVO GRADO
        </a>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary shadow-sm border-0">
        {{-- Filtros de Estado y Papelera --}}
        <div class="d-flex justify-content-end p-3 bg-white border-bottom">
            <div class="btn-group shadow-sm">
                <a href="{{ route('admin.grados.index') }}"
                    class="btn btn-sm btn-flat {{ !request()->has('papelera') ? 'btn-primary' : 'btn-outline-secondary' }}">
                    <i class="fas fa-list mr-1"></i> TODOS LOS GRADOS
                </a>
                <a href="{{ route('admin.grados.index', ['papelera' => 1]) }}"
                    class="btn btn-sm btn-flat {{ request()->has('papelera') ? 'btn-danger' : 'btn-outline-secondary' }}">
                    <i class="fas fa-trash-alt mr-1"></i> EN PAPELERA
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="grados-table" class="table table-hover mb-0 w-100">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th class="px-4 py-3 text-center" width="5%">ORDEN</th>
                            <th class="py-3" width="25%">NOMBRE DEL GRADO</th>
                            <th class="py-3 text-center" width="15%">CICLO</th> {{-- NUEVA COLUMNA --}}
                            <th class="py-3" width="20%">NIVEL ACADÉMICO</th>
                            <th class="py-3 text-center" width="10%">ESTADO</th>
                            <th class="py-3 text-center" width="25%">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($grados as $grado)
                            <tr style="{{ $grado->trashed() ? 'background-color: #fffafa;' : '' }}">
                                <td class="px-4 align-middle text-center">
                                    <span class="badge badge-light border text-muted px-2">{{ $grado->orden }}</span>
                                </td>
                                <td class="align-middle">
                                    <span class="font-weight-bold text-dark text-uppercase">{{ $grado->nombre }}</span>
                                    @if ($grado->trashed())
                                        <span class="badge text-danger ml-1"
                                            style="font-size: 0.65rem; background: #ffebeb;">ELIMINADO</span>
                                    @endif
                                </td>
                                {{-- Lógica de Ciclo --}}
                                <td class="text-center align-middle">
                                    @if ($grado->ciclo == 1)
                                        <span class="badge border px-2 py-1" style="color: #4b545c; background: #f8f9fa;">
                                            <i class="fas fa-book-reader mr-1 small"></i> TRONCO COMÚN
                                        </span>
                                    @else
                                        <span class="badge border px-2 py-1" style="color: #1f2d3d; background: #e9ecef;">
                                            <i class="fas fa-user-graduate mr-1 small"></i> ESPECIALIDAD
                                        </span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <span class="badge px-2 py-1"
                                        style="background: #e7f3ff; color: #0056b3; border: 1px solid #d0e7ff;">
                                        <i class="fas fa-tag mr-1 small"></i> {{ $grado->nivel->nombre ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    @if ($grado->estado)
                                        <span class="badge px-2 py-1 shadow-none"
                                            style="font-size: 0.75rem; color: #fff; background-color: {{ $grado->estado->color_hex ?? '#6c757d' }};">
                                            <i
                                                class="fas {{ $grado->estado->slug == 'activo' ? 'fa-check-circle' : 'fa-info-circle' }} mr-1"></i>
                                            {{ strtoupper($grado->estado->nombre) }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1" style="font-size: 0.75rem;">S/E</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    <div class="btn-group">
                                        @if ($grado->trashed())
                                            <form action="{{ route('admin.grados.restore', $grado->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-link text-info p-1" title="Restaurar">
                                                    <i class="fas fa-trash-restore-alt fa-lg"></i>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('admin.grados.edit', $grado) }}"
                                                class="btn btn-link p-1 text-success" title="Editar">
                                                <i class="fas fa-edit fa-lg"></i>
                                            </a>
                                            <form action="{{ route('admin.grados.destroy', $grado) }}" method="POST"
                                                class="form-eliminar d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-1"
                                                    title="Eliminar">
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
            const table = $('#grados-table').DataTable({
                responsive: true,
                autoWidth: false,
                ordering: true,
                order: [
                    [0, "asc"]
                ],
                dom: '<"row px-4 py-3"<"col-md-9"B><"col-md-3"f>>' + 'tr' +
                    '<"row px-4 py-3"<"col-md-5"i><"col-md-7"p>>',
                buttons: [{
                        extend: 'copy',
                        text: '<i class="fas fa-copy"></i> Copiar',
                        className: 'btn btn-secondary btn-sm btn-flat shadow-sm'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success btn-sm btn-flat shadow-sm'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger btn-sm btn-flat shadow-sm',
                        orientation: 'portrait',
                        pageSize: 'LETTER'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Imprimir',
                        className: 'btn btn-dark btn-sm btn-flat shadow-sm'
                    }
                ],
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json",
                    search: "<b>BUSCAR:</b>",
                    searchPlaceholder: "Filtrar grados..."
                }
            });

            $('.form-eliminar').submit(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Mover a la papelera?',
                    text: "El grado no estará disponible para nuevas inscripciones.",
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
