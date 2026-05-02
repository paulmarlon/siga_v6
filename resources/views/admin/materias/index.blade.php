@extends('adminlte::page')
@section('title', 'Catálogo de Materias | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="text-dark font-weight-bold m-0">
            <i class="fas fa-book text-primary mr-2"></i> Catálogo de Materias
        </h1>
        <a href="{{ route('admin.materias.create') }}" class="btn btn-primary shadow-sm"
            style="background:#003366; border:none;">
            <i class="fas fa-plus-circle mr-1"></i> NUEVA MATERIA
        </a>
    </div>
@stop

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <!-- Columna del Título -->
                <div class="col-md-8">
                    <h6 class="m-0 font-weight-bold text-muted text-uppercase">
                        Listado General de Materias
                    </h6>
                </div>

                <!-- Columna de Botones (Alineada a la derecha) -->
                <div class="col-md-4 text-right">
                    <div class="btn-group shadow-sm">
                        <a href="{{ route('admin.materias.index') }}"
                            class="btn btn-sm {{ !request()->has('papelera') ? 'btn-primary' : 'btn-outline-secondary' }}">
                            <i class="fas fa-check-circle mr-1"></i> ACTIVAS
                        </a>
                        <a href="{{ route('admin.materias.index', ['papelera' => 1]) }}"
                            class="btn btn-sm {{ request()->has('papelera') ? 'btn-danger' : 'btn-outline-secondary' }}">
                            <i class="fas fa-trash-alt mr-1"></i> PAPELERA
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive p-3">
                {{-- Quitamos 'table-sm' para una apariencia normal y más espaciada --}}
                <table id="materias-table" class="table table-hover table-striped w-100 border">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th class="text-center">SIGLA</th>
                            <th>NOMBRE DE LA MATERIA</th>
                            <th class="text-center">TIPO</th>
                            <th class="text-center">HORAS</th>
                            <th class="text-center">ESTADO</th>
                            <th class="text-center" style="width: 120px;">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materias as $materia)
                            <tr style="{{ $materia->trashed() ? 'background:#fff5f5;' : '' }}">
                                <td class="text-center align-middle font-weight-bold text-primary">
                                    {{ $materia->sigla }}
                                </td>
                                <td class="align-middle">
                                    <span class="d-block font-weight-bold text-dark">{{ $materia->nombre }}</span>
                                    @if ($materia->es_comun)
                                        <small class="text-info"><i class="fas fa-share-alt mr-1"></i>Tronco Común</small>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    @php $colors = ['Teorica'=>'bg-info','Practica'=>'bg-primary','Teorica-Practica'=>'bg-warning text-dark']; @endphp
                                    <span
                                        class="badge {{ $colors[$materia->tipo_materia] ?? 'bg-secondary' }} px-3 py-2 shadow-sm">
                                        {{ $materia->tipo_materia }}
                                    </span>
                                </td>
                                <td class="text-center align-middle font-weight-bold">
                                    {{ $materia->horas_academicas }} <small>Hrs</small>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge px-3 py-2"
                                        style="background:{{ $materia->trashed() ? '#dc3545' : $materia->estado->color_hex ?? '#6c757d' }}; color:white;">
                                        {{ $materia->trashed() ? 'ELIMINADO' : $materia->estado->nombre }}
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="btn-group">
                                        @if ($materia->trashed())
                                            <form action="{{ route('admin.materias.restore', $materia->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-info btn-sm"
                                                    title="Restaurar Registro">
                                                    <i class="fas fa-trash-restore"></i>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('admin.materias.edit', $materia) }}"
                                                class="btn btn-outline-success btn-sm mr-1" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.materias.destroy', $materia) }}" method="POST"
                                                class="form-eliminar d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    title="Mover a papelera">
                                                    <i class="fas fa-trash-alt"></i>
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
            $('#materias-table').DataTable({
                responsive: true,
                autoWidth: false,
                order: [
                    [0, "asc"]
                ],
                // El 'dom' controla la posición: B=Botones, f=Filtrar, r=procesando, t=tabla, i=información, p=paginación
                dom: '<"row mb-3"<"col-md-8"B><"col-md-4"f>>' +
                    'tr' +
                    '<"row mt-4"<"col-md-5"i><"col-md-7"p>>',
                buttons: [{
                        extend: 'colvis',
                        text: '<i class="fas fa-columns mr-1"></i> Columnas',
                        className: 'btn btn-secondary btn-sm' // Gris estándar
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel mr-1"></i> Excel',
                        className: 'btn btn-success btn-sm', // Verde Excel
                        title: 'SIG@ - Catálogo de Materias'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf mr-1"></i> PDF',
                        className: 'btn btn-danger btn-sm', // Rojo PDF
                        orientation: 'landscape'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print mr-1"></i> Imprimir',
                        className: 'btn btn-info btn-sm' // Azul claro / Información
                    }
                ],
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json",
                    search: "<b>BUSCAR:</b>",
                    searchPlaceholder: "Escriba sigla o nombre..."
                }
            });

            $('.form-eliminar').on('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Mover a la papelera?',
                    text: "Podrás restaurar esta materia después si es necesario.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#003366',
                    confirmButtonText: '<i class="fas fa-trash-alt"></i> Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((r) => {
                    if (r.isConfirmed) this.submit();
                });
            });

            @if (session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif
        });
    </script>
@stop
