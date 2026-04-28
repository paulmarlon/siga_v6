@extends('adminlte::page')

@section('title', 'Carreras | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3">
        <h1 class="text-dark" style="font-weight: 500; font-size: 1.6rem;">
            <i class="fas fa-graduation-cap text-primary mr-2"></i> Gestión de Carreras
        </h1>
        <a href="{{ route('admin.carreras.create') }}" class="btn btn-primary shadow-sm"
            style="background-color: #003366; border-color: #003366; border-radius: 4px;">
            <i class="fas fa-plus-circle mr-1"></i> NUEVA CARRERA
        </a>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary shadow-sm border-0">
        {{-- Filtros de Estado --}}
        <div class="d-flex justify-content-end p-3 bg-white border-bottom">
            <div class="btn-group shadow-sm">
                <a href="{{ route('admin.carreras.index') }}"
                    class="btn btn-sm btn-flat {{ !request()->has('papelera') ? 'btn-primary' : 'btn-outline-secondary' }}">
                    <i class="fas fa-check-circle mr-1"></i> ACTIVAS
                </a>
                <a href="{{ route('admin.carreras.index', ['papelera' => 1]) }}"
                    class="btn btn-sm btn-flat {{ request()->has('papelera') ? 'btn-danger' : 'btn-outline-secondary' }}">
                    <i class="fas fa-trash-alt mr-1"></i> EN PAPELERA
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="carreras-table" class="table table-hover mb-0 w-100">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th class="px-4 py-3" width="5%">SIGLA</th>
                            <th class="py-3" width="25%">NOMBRE DE LA CARRERA</th>
                            <th class="py-3" width="15%">JERARQUÍA / BASE</th>
                            <th class="py-3" width="12%">NIVEL</th>
                            <th class="py-3 text-center" width="10%">DURACIÓN</th>
                            <th class="py-3 text-center" width="8%">ESTADO</th>
                            <th class="py-3 text-center" width="20%">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($carreras as $carrera)
                            <tr style="{{ $carrera->trashed() ? 'background-color: #fffafa;' : '' }}">
                                <td class="px-4 align-middle">
                                    <code class="text-primary font-weight-bold">{{ $carrera->sigla }}</code>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex flex-column">
                                        <span
                                            class="font-weight-bold text-dark text-uppercase">{{ $carrera->nombre }}</span>
                                        <small class="text-muted">{{ $carrera->titulo }}</small>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    @if ($carrera->es_tronco_comun)
                                        <span class="badge badge-info p-2" style="font-size: 0.7rem;">
                                            <i class="fas fa-layer-group mr-1"></i> TRONCO COMÚN
                                        </span>
                                    @elseif($carrera->carrera_base_id)
                                        <div class="d-flex flex-column">
                                            <span class="badge badge-light border text-muted"
                                                style="font-size: 0.65rem; width: fit-content;">
                                                ESPECIALIDAD DE:
                                            </span>
                                            <span class="small font-weight-bold text-primary">
                                                <i class="fas fa-level-up-alt fa-rotate-90 mr-1"></i>
                                                {{ $carrera->carreraBase->sigla }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-muted small">---</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <span class="text-muted">{{ $carrera->nivel->nombre }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="badge badge-light border">{{ $carrera->duracion }} Sem.</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="badge shadow-sm"
                                        style="background-color: {{ $carrera->estado->color_hex }}; color: white; padding: 5px 10px;">
                                        {{ $carrera->estado->nombre }}
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="btn-group">
                                        @if ($carrera->trashed())
                                            <form action="{{ route('admin.carreras.restore', $carrera->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-link text-info p-1"
                                                    title="Restaurar carrera">
                                                    <i class="fas fa-trash-restore-alt fa-lg"></i>
                                                </button>
                                            </form>
                                        @else
                                            {{-- Botón para ir a la Malla Curricular (Pensum) --}}
                                            <a href="#" class="btn btn-link p-1 text-primary"
                                                title="Ver Malla Curricular">
                                                <i class="fas fa-th fa-lg"></i>
                                            </a>

                                            <a href="{{ route('admin.carreras.edit', $carrera) }}"
                                                class="btn btn-link p-1 text-success" title="Editar Carrera">
                                                <i class="fas fa-edit fa-lg"></i>
                                            </a>

                                            <form action="{{ route('admin.carreras.destroy', $carrera) }}" method="POST"
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

{{-- ... Mantener tu sección @section('js') intacta --}}
