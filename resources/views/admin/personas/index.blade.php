@extends('adminlte::page')

@section('title', 'Personas | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
        <h1 class="text-dark" style="font-weight: 400; font-size: 1.5rem;">
            <i class="fas fa-users text-secondary mr-2"></i> Registro de Personas
        </h1>
        <a href="{{ route('admin.personas.create') }}" class="btn btn-primary btn-sm"
            style="background-color: #003366; border-color: #003366;">
            <i class="fas fa-user-plus mr-1"></i> NUEVO REGISTRO
        </a>
    </div>
@stop

@section('content')
    <div class="card card-flat shadow-none border">
        <div class="card-header bg-white py-3 border-bottom-0 d-flex justify-content-end">
            {{-- Botones de Filtro (Estilo Turnos) alineados a la derecha --}}
            <div class="btn-group shadow-sm">
                <a href="{{ route('admin.personas.index') }}"
                    class="btn btn-sm btn-flat {{ !request('estado') ? 'btn-primary' : 'btn-outline-secondary' }}"
                    style="min-width: 100px;">
                    <i class="fas fa-check-circle mr-1"></i> ACTIVOS
                </a>
                <a href="{{ route('admin.personas.index', ['estado' => 'eliminados']) }}"
                    class="btn btn-sm btn-flat {{ request('estado') == 'eliminados' ? 'btn-danger' : 'btn-outline-danger' }}"
                    style="min-width: 100px;">
                    <i class="fas fa-trash-alt mr-1"></i> PAPELERA
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="personas-table" class="table table-sm table-hover mb-0">
                    <thead style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <tr class="text-secondary" style="font-size: 0.8rem; text-transform: uppercase;">
                            <th class="pl-4 py-3">ID</th>
                            <th class="py-3">Foto</th>
                            <th class="py-3">Documento</th>
                            <th class="py-3">Nombre Completo</th>
                            <th class="py-3">Contacto</th>
                            <th class="py-3 text-center">Estado</th>
                            <th class="py-3 text-center">Operaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($personas as $persona)
                            <tr
                                style="border-bottom: 1px solid #eee; {{ $persona->trashed() ? 'background-color: #fffafa;' : '' }}">
                                <td class="pl-4 text-muted small">#{{ $persona->id }}</td>
                                <td>
                                    <img src="{{ $persona->foto_path ? asset('storage/' . $persona->foto_path) : asset('img/default-avatar.png') }}"
                                        class="img-circle border" style="width: 35px; height: 35px; object-fit: cover;">
                                </td>
                                <td>{{ $persona->ci }}</td>
                                <td class="text-uppercase">
                                    <small class="text-muted">{{ $persona->nombres }} {{ $persona->ap_paterno }}
                                        {{ $persona->ap_materno }}</small>

                                </td>
                                <td>
                                    <span class="d-block small"><i
                                            class="fas fa-phone mr-1"></i>{{ $persona->celular ?? '---' }}</span>
                                    <small class="text-muted">{{ $persona->email_personal }}</small>
                                </td>
                                <td class="text-center">
                                    @if ($persona->estado)
                                        <span class="badge btn-flat text-xs"
                                            style="background-color: {{ $persona->estado->color_hex }}; color: white;">
                                            {{ $persona->estado->nombre }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary btn-flat text-xs">SIN ESTADO</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        @if ($persona->trashed())
                                            <form action="{{ route('admin.personas.restore', $persona->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-flat btn-sm border text-success bg-white"><i
                                                        class="fas fa-undo-alt"></i></button>
                                            </form>
                                        @else
                                            @if ($persona->user)
                                                {{-- Ya es usuario: Mostramos un check verde --}}
                                                <button class="btn btn-flat btn-sm border text-success bg-white mr-1"
                                                    title="Usuario ya activado" disabled>
                                                    <i class="fas fa-user-check"></i>
                                                </button>
                                            @else
                                                {{-- No es usuario: Botón para ACTIVAR --}}
                                                <form action="{{ route('admin.personas.activar-usuario', $persona->id) }}"
                                                    method="POST" class="d-inline form-activar">
                                                    @csrf
                                                    <button type="button"
                                                        class="btn btn-flat btn-sm border text-primary bg-white mr-1 btn-activar"
                                                        title="Activar como Usuario" data-nombre="{{ $persona->nombres }}">
                                                        <i class="fas fa-user-plus"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('admin.personas.show', $persona) }}"
                                                class="btn btn-flat btn-sm border text-info bg-white mr-1"
                                                title="Ver Detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.personas.edit', $persona) }}"
                                                class="btn btn-flat btn-sm border text-primary bg-white mr-1"><i
                                                    class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.personas.destroy', $persona) }}" method="POST"
                                                class="form-eliminar">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-flat btn-sm border text-danger bg-white"><i
                                                        class="fas fa-trash-alt"></i></button>
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

@section('css')
    <style>
        .btn-flat {
            border-radius: 0 !important;
        }

        .table td {
            vertical-align: middle;
        }

        /* Ajuste para que los botones de colores de DataTables no se vean pegados */
        .dt-buttons .btn {
            margin-right: 5px;
            font-size: 0.75rem;
            font-weight: bold;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('#personas-table').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "order": [
                    [0, "desc"]
                ],
                "dom": '<"row mx-0 border-bottom py-2"<"col-sm-8"B><"col-sm-4"f>>rt<"row mx-0 pt-2"<"col-sm-6"i><"col-sm-6"p>>',
                "buttons": [{
                        extend: 'copy',
                        text: '<i class="far fa-copy"></i> COPIAR',
                        className: 'btn btn-secondary btn-flat'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="far fa-file-excel"></i> EXCEL',
                        className: 'btn btn-success btn-flat',
                        title: 'Listado de Personas'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="far fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger btn-flat',
                        title: 'Listado de Personas'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> IMPRIMIR',
                        className: 'btn btn-info btn-flat'
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="fas fa-columns"></i>',
                        className: 'btn btn-dark btn-flat'
                    }
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
                }
            });

            // SweetAlert2 Toast
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif
        });
        // Usamos Delegación de Eventos para que funcione incluso al cambiar de página en la tabla
        $(document).on('click', '.btn-activar', function(e) {
            e.preventDefault();

            let nombre = $(this).data('nombre');
            let form = $(this).closest('form');

            Swal.fire({
                title: '¿Activar acceso?',
                text: `Se creará una cuenta de usuario para ${nombre} usando su CI como contraseña inicial.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#003366',
                cancelButtonColor: '#d33',
                confirmButtonText: '<i class="fas fa-check"></i> Sí, activar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@stop
