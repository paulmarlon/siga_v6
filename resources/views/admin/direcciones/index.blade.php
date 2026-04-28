@extends('adminlte::page')

@section('title', 'Direcciones | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
        <h1 class="text-dark" style="font-weight: 400; font-size: 1.5rem;">
            <i class="fas fa-folder-open text-secondary mr-2"></i> Registro de Direcciones
        </h1>
        <a href="{{ route('admin.direcciones.create') }}" class="btn btn-primary btn-sm"
            style="background-color: #003366; border-color: #003366;">
            <i class="fas fa-plus mr-1"></i> NUEVO REGISTRO
        </a>
    </div>
@stop

@section('content')
    <div class="card card-flat shadow-none border">
        {{-- MOVER EL FILTRO AQUÍ: Fuera del body para evitar bugs de layout --}}
        <div class="card-header bg-white py-3 border-bottom-0">
            <div class="btn-group">
                <a href="{{ route('admin.direcciones.index') }}"
                    class="btn btn-sm btn-flat {{ !request('estado') ? 'btn-secondary' : 'btn-outline-secondary' }}">
                    <i class="fas fa-check-circle mr-1"></i> ACTIVOS
                </a>
                <a href="{{ route('admin.direcciones.index', ['estado' => 'eliminados']) }}"
                    class="btn btn-sm btn-flat {{ request('estado') == 'eliminados' ? 'btn-danger' : 'btn-outline-danger' }}">
                    <i class="fas fa-trash-alt mr-1"></i> PAPELERA
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                {{-- ... (encabezado igual) ... --}}

                <table id="direcciones-table" class="table table-sm table-hover mb-0">
                    <thead style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <tr class="text-secondary" style="font-size: 0.8rem; text-transform: uppercase;">
                            <th class="pl-3 py-3" width="8%">Código</th>
                            <th class="py-3" width="12%">Ubicación</th>
                            <th class="py-3" width="12%">Provincia</th>
                            <th class="py-3" width="15%">Municipio</th>
                            <th class="py-3" width="15%">Zona / Barrio</th>
                            <th class="py-3" width="18%">Detalle Referencial</th>
                            <th class="py-3 text-center" width="20%">Operaciones</th>
                        </tr>
                    </thead>
                    <tbody style="color: #333; font-size: 0.95rem;">
                        @foreach ($direcciones as $direccion)
                            <tr style="border-bottom: 1px solid #eee;">
                                {{-- 1. Código --}}
                                <td class="pl-3 font-weight-bold text-secondary">
                                    {{ str_pad($direccion->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                {{-- 2. Ubicación --}}
                                <td>
                                    <span class="text-xs text-muted d-block"
                                        style="font-size: 0.7rem;">{{ $direccion->pais }}</span>
                                    <span class="font-weight-bold">{{ $direccion->departamento }}</span>
                                </td>
                                {{-- 3. Provincia --}}
                                <td>{{ $direccion->provincia ?? '---' }}</td>
                                {{-- 4. Municipio --}}
                                <td>{{ $direccion->ciudad ?? '---' }}</td>
                                {{-- 5. Zona --}}
                                <td>{{ $direccion->zona ?? '---' }}</td>
                                {{-- 6. Detalle --}}
                                <td class="small text-muted">
                                    {{ Str::limit($direccion->detalle, 40, '...') ?? 'Sin detalle' }}
                                </td>
                                {{-- 7. Operaciones (ESTA CELDA DEBE EXISTIR SIEMPRE) --}}
                                <td class="text-center">
                                    <div class="btn-group">
                                        @if ($direccion->trashed())
                                            {{-- Botón Restaurar --}}
                                            <form action="{{ route('admin.direcciones.restore', $direccion->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-flat btn-sm border text-success bg-white"
                                                    title="Restaurar">
                                                    <i class="fas fa-undo-alt"></i>
                                                </button>
                                            </form>
                                        @else
                                            {{-- Botones Estándar --}}
                                            <button type="button"
                                                class="btn btn-flat btn-sm border text-info bg-white mr-1"
                                                onclick="verDetalle({{ $direccion->id }})" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ route('admin.direcciones.edit', $direccion) }}"
                                                class="btn btn-flat btn-sm border text-primary bg-white mr-1"
                                                title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.direcciones.destroy', $direccion) }}"
                                                method="POST" class="form-eliminar">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-flat btn-sm border text-danger bg-white"
                                                    title="Eliminar">
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

                {{-- ... (scripts igual) ... --}}
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        /* Estética Gubernamental / Institucional */
        body {
            font-family: 'Source Sans Pro', sans-serif;
        }

        .card {
            border-radius: 0;
        }

        .table thead th {
            border-bottom: 0;
            vertical-align: middle;
        }

        .table td {
            vertical-align: middle;
        }

        /* Personalización de DataTables para que no distraiga */
        .dataTables_wrapper .row {
            margin: 10px 15px;
        }

        .btn-flat {
            border-radius: 0 !important;
        }

        /* Botones de exportación con estilo sobrio */
        .dt-buttons .btn {
            background: #fff !important;
            color: #444 !important;
            border: 1px solid #ccc !important;
            font-size: 0.8rem !important;
            border-radius: 0 !important;
        }

        .dt-buttons .btn:hover {
            background: #f4f4f4 !important;
        }
    </style>
@stop
@section('js')
    <script>
        $(document).ready(function() {
            var table = $('#direcciones-table').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "ordering": true,
                "info": true,
                "pageLength": 10,
                // Estructura del DOM: B=Botones, f=Filtro, rt=Tabla, i=Información, p=Paginación
                "dom": '<"row mx-0 border-bottom py-2"<"col-sm-8"B><"col-sm-4"f>>rt<"row mx-0 pt-2"<"col-sm-6"i><"col-sm-6"p>>',
                "buttons": [{
                        extend: 'copy',
                        text: '<i class="far fa-copy mr-1"></i> COPIAR',
                        className: 'btn-flat'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv mr-1"></i> CSV',
                        className: 'btn-flat'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="far fa-file-excel mr-1"></i> EXCEL',
                        className: 'btn-flat'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="far fa-file-pdf mr-1"></i> PDF',
                        className: 'btn-flat'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print mr-1"></i> IMPRIMIR',
                        className: 'btn-flat'
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="fas fa-columns mr-1"></i> COLUMNAS',
                        className: 'btn-flat'
                    }
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
                }
            });

            // Reafirmar estilo de botones después de la carga
            $('.dt-button').addClass('btn btn-white btn-sm border text-secondary');

            // SweetAlert2 Institucional para eliminar
            $('.form-eliminar').submit(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'CONFIRMACIÓN DE SISTEMA',
                    text: "¿Desea proceder con la eliminación lógica de este registro?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#003366',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'CONFIRMAR',
                    cancelButtonText: 'CANCELAR',
                    heightAuto: false,
                    customClass: {
                        confirmButton: 'btn-flat',
                        cancelButton: 'btn-flat'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
            @if (session('mensaje'))
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end', // Esquina superior derecha
                    showConfirmButton: false, // Sin botones que distraigan
                    timer: 2500, // Tiempo en milisegundos
                    timerProgressBar: true, // Línea de tiempo visual
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Toast.fire({
                    icon: "{{ session('icono') }}",
                    title: "{{ session('mensaje') }}"
                });
            @endif
        });
    </script>
@stop
