@extends('adminlte::page')

@section('title', 'Periodos Académicos | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3">
        <h1 class="text-dark" style="font-weight: 500; font-size: 1.6rem;">
            <i class="fas fa-calendar-alt text-primary mr-2"></i> Gestión de Periodos Académicos
        </h1>
        <a href="{{ route('admin.periodos.create') }}" class="btn btn-primary shadow-sm"
            style="background-color: #003366; border-color: #003366; border-radius: 4px;">
            <i class="fas fa-plus-circle mr-1"></i> NUEVO PERIODO
        </a>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary shadow-sm border-0">
        {{-- Filtros de Estado y Papelera --}}
        <div class="d-flex justify-content-end p-3 bg-white border-bottom">
            <div class="btn-group shadow-sm">
                <a href="{{ route('admin.periodos.index') }}"
                    class="btn btn-sm btn-flat {{ !request()->has('papelera') ? 'btn-primary' : 'btn-outline-secondary' }}">
                    <i class="fas fa-check-circle mr-1"></i> ACTIVOS
                </a>
                <a href="{{ route('admin.periodos.index', ['papelera' => 1]) }}"
                    class="btn btn-sm btn-flat {{ request()->has('papelera') ? 'btn-danger' : 'btn-outline-secondary' }}">
                    <i class="fas fa-trash-alt mr-1"></i> EN PAPELERA
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="periodos-table" class="table table-hover mb-0 w-100">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th class="px-4 py-3" width="5%">ID</th>
                            <th class="py-3" width="25%">NOMBRE DEL PERIODO</th>
                            <th class="py-3 text-center" width="15%">GESTIÓN</th>
                            <th class="py-3 text-center" width="20%">DURACIÓN</th>
                            <th class="py-3 text-center" width="15%">ESTADO</th>
                            <th class="py-3 text-center" width="20%">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($periodos as $periodo)
                            <tr style="{{ $periodo->trashed() ? 'background-color: #fffafa;' : '' }}">
                                <td class="px-4 align-middle font-italic text-muted">#{{ $periodo->id }}</td>
                                <td class="align-middle">
                                    <span class="font-weight-bold text-dark text-uppercase">{{ $periodo->nombre }}</span>
                                    @if ($periodo->trashed())
                                        <span class="badge badge-danger-light text-danger ml-2"
                                            style="font-size: 0.7rem; background: #ffebeb; padding: 3px 8px; border-radius: 10px;">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> EN PAPELERA
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-info px-3 py-1"
                                        style="font-size: 0.85rem; border-radius: 4px;">
                                        {{ $periodo->gestion->nombre ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    <small class="text-muted d-block">
                                        <i class="far fa-calendar-check text-success"></i>
                                        {{ $periodo->fecha_inicio->format('d/m/Y') }}
                                    </small>
                                    <small class="text-muted d-block">
                                        <i class="far fa-calendar-times text-danger"></i>
                                        {{ $periodo->fecha_fin->format('d/m/Y') }}
                                    </small>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge text-uppercase px-2 py-1 shadow-sm"
                                        style="font-size: 0.75rem; background-color: {{ optional($periodo->estado)->color_hex ?? '#6c757d' }}; color: white;">
                                        {{ optional($periodo->estado)->nombre ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="btn-group">
                                        @if ($periodo->trashed())
                                            {{-- BOTÓN RESTAURAR --}}
                                            <form action="{{ route('admin.periodos.restore', $periodo->id) }}"
                                                method="POST" {{-- Debe ser POST para coincidir con tu ruta --}} class="form-restaurar d-inline">
                                                @csrf
                                                {{-- ELIMINA CUALQUIER LÍNEA QUE DIGA @method('PUT') AQUÍ --}}

                                                <button type="submit" class="btn btn-link text-info p-1"
                                                    title="Restaurar registro">
                                                    <i class="fas fa-trash-restore-alt fa-lg"></i>
                                                </button>
                                            </form>
                                        @else
                                            {{-- BOTÓN EDITAR --}}
                                            <a href="{{ route('admin.periodos.edit', $periodo) }}"
                                                class="btn btn-link p-1 text-success" title="Editar Registro">
                                                <i class="fas fa-edit fa-lg"></i>
                                            </a>

                                            {{-- BOTÓN ELIMINAR (SOFT DELETE) --}}
                                            <form action="{{ route('admin.periodos.destroy', $periodo) }}" method="POST"
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // 1. Configuración de DataTable
            const table = $('#periodos-table').DataTable({
                responsive: true,
                autoWidth: false,
                ordering: true,
                order: [
                    [2, "desc"],
                    [3, "asc"]
                ],
                dom: '<"row px-4 py-3"<"col-md-9"B><"col-md-3"f>>' + 'tr' +
                    '<"row px-4 py-3"<"col-md-5"i><"col-md-7"p>>',
                buttons: [{
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success btn-sm btn-flat'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger btn-sm btn-flat'
                    }
                ],
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
                }
            });

            // 2. Configuración de Toasts (Notificaciones rápidas)
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            // 3. Captura de mensajes del Controller (swal-success / swal-error)
            @if (session('swal-success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('swal-success') }}"
                });
            @endif

            @if (session('swal-error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Operación fallida',
                    text: "{{ session('swal-error') }}",
                    confirmButtonColor: '#003366',
                });
            @endif

            // 4. Confirmación para ELIMINAR (Soft Delete)
            $('.form-eliminar').submit(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Mover a papelera?',
                    text: "El periodo se ocultará del sistema pero permanecerá en la base de datos.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#003366',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-trash-alt"></i> Sí, mover',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });

            // 5. Confirmación para RESTAURAR
            $('.form-restaurar').submit(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Restaurar periodo?',
                    text: "El registro volverá a estar activo en el sistema.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#17a2b8',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-check"></i> Sí, restaurar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    </script>
@stop
