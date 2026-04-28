@extends('adminlte::page')

@section('title', 'Ficha de Persona | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
        <h1 class="text-dark" style="font-weight: 400; font-size: 1.5rem;">
            <i class="fas fa-user-tie text-info mr-2"></i> Ficha Informativa de Persona
        </h1>
        <div>
            <a href="{{ route('admin.personas.edit', $persona) }}" class="btn btn-sm btn-flat btn-default border mr-1">
                <i class="fas fa-edit text-success mr-1"></i> EDITAR
            </a>
            <a href="{{ route('admin.personas.index') }}" class="btn btn-default btn-sm border btn-flat">
                <i class="fas fa-arrow-left mr-1"></i> VOLVER
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid pt-3">
        <div class="row">
            {{-- PERFIL E IDENTIDAD (IZQUIERDA) --}}
            <div class="col-md-4">
                {{-- Foto y Nombre Principal --}}
                <div class="card card-info card-outline shadow-none border mb-3" style="border-radius: 0;">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle elevation-2"
                                src="{{ $persona->foto_path ? asset('storage/' . $persona->foto_path) : asset('img/default-avatar.png') }}"
                                alt="User profile picture"
                                style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #17a2b8;">
                        </div>
                        <h3 class="profile-username text-center text-uppercase mt-3"
                            style="font-weight: 700; font-size: 1.2rem;">
                            {{ $persona->nombres }}
                        </h3>
                        <p class="text-muted text-center text-uppercase mb-2">
                            {{ $persona->ap_paterno }} {{ $persona->ap_materno }}
                        </p>

                        <div class="text-center">
                            @if ($persona->user)
                                <span class="badge badge-success px-3 py-2"><i class="fas fa-check-circle mr-1"></i> CUENTA
                                    ACTIVA</span>
                            @else
                                <span class="badge badge-secondary px-3 py-2"><i class="fas fa-user-slash mr-1"></i> SIN
                                    ACCESO</span>
                            @endif
                        </div>

                        <ul class="list-group list-group-unbordered mt-3">
                            <li class="list-group-item">
                                <b class="text-secondary"><i class="fas fa-id-card mr-2"></i> C.I.</b>
                                <a class="float-right text-dark font-weight-bold">{{ $persona->ci }}</a>
                            </li>
                            <li class="list-group-item">
                                <b class="text-secondary"><i class="fas fa-venus-mars mr-2"></i> SEXO</b>
                                <a class="float-right text-dark">
                                    {{ $persona->sexo == 'M' ? 'MASCULINO' : 'FEMENINO' }}
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b class="text-secondary"><i class="fas fa-birthday-cake mr-2"></i> NACIMIENTO</b>
                                <a class="float-right text-dark">
                                    {{ $persona->fecha_nacimiento ? \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y') : 'NO REGISTRADO' }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Contacto --}}
                <div class="card card-flat shadow-none border" style="border-left: 4px solid #17a2b8 !important;">
                    <div class="card-header py-2 bg-light">
                        <h3 class="card-title small font-weight-bold text-uppercase text-secondary">
                            <i class="fas fa-address-book mr-1"></i> Canales de Contacto
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <strong class="text-xs text-info text-uppercase">Número Celular</strong>
                        <p class="text-dark font-weight-bold"><i class="fas fa-mobile-alt mr-2 text-muted"></i>
                            {{ $persona->celular }}</p>
                        <hr class="my-2">
                        <strong class="text-xs text-info text-uppercase">Correo Electrónico</strong>
                        <p class="text-dark">
                            <i class="fas fa-envelope mr-2 text-muted"></i> <span
                                class="text-lowercase">{{ $persona->email_personal }}</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- LOCALIZACIÓN Y DETALLES (DERECHA) --}}
            <div class="col-md-8">
                <div class="card card-flat shadow-none border">
                    <div class="card-header bg-light py-2">
                        <h3 class="card-title text-uppercase text-info" style="font-size: 0.85rem; font-weight: 700;">
                            <i class="fas fa-map-marked-alt mr-1"></i> Ubicación Geográfica Registrada
                        </h3>
                    </div>
                    <div class="card-body bg-white">
                        @if ($persona->direccion)
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="text-secondary text-xs text-uppercase d-block border-bottom">País</label>
                                    <span
                                        class="text-dark font-weight-bold text-uppercase">{{ $persona->direccion->pais }}</span>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label
                                        class="text-secondary text-xs text-uppercase d-block border-bottom">Departamento</label>
                                    <span
                                        class="text-dark font-weight-bold text-uppercase">{{ $persona->direccion->departamento }}</span>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label
                                        class="text-secondary text-xs text-uppercase d-block border-bottom">Provincia</label>
                                    <span
                                        class="text-dark font-weight-bold text-uppercase">{{ $persona->direccion->provincia }}</span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-secondary text-xs text-uppercase d-block border-bottom">Ciudad /
                                        Municipio</label>
                                    <span
                                        class="text-dark font-weight-bold text-uppercase">{{ $persona->direccion->ciudad }}</span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-secondary text-xs text-uppercase d-block border-bottom">Zona /
                                        Barrio</label>
                                    <span
                                        class="text-dark font-weight-bold text-uppercase">{{ $persona->direccion->zona }}</span>
                                </div>
                            </div>

                            <div class="card bg-light shadow-none border mt-2" style="border-radius: 0;">
                                <div class="card-body py-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <strong class="text-info text-xs text-uppercase">Avenida / Calle</strong>
                                            <p class="mb-0 text-uppercase font-weight-bold">
                                                {{ $direccion_formateada['calle'] }}</p>
                                        </div>
                                        <div class="col-md-4 border-left">
                                            <strong class="text-info text-xs text-uppercase">Nro. de Vivienda</strong>
                                            <p class="mb-0 text-uppercase font-weight-bold">
                                                {{ $direccion_formateada['nro'] }}</p>
                                        </div>
                                    </div>
                                    <hr class="my-2">
                                    <strong class="text-info text-xs text-uppercase">Referencias de Ubicación</strong>
                                    <p class="mb-0 text-uppercase text-sm">{{ $direccion_formateada['ref'] }}</p>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-map-marker-slash fa-3x text-muted mb-3"></i>
                                <p class="text-secondary italic">No se ha registrado información domiciliaria para esta
                                    persona.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Card de Auditoría (Opcional pero muy profesional para SIG@) --}}
                <div class="card card-flat shadow-none border mt-3">
                    <div class="card-header bg-light py-2">
                        <h3 class="card-title text-uppercase text-secondary" style="font-size: 0.75rem; font-weight: 700;">
                            <i class="fas fa-history mr-1"></i> Trazabilidad del Registro
                        </h3>
                    </div>
                    <div class="card-body py-2 px-3">
                        <div class="row text-center">
                            <div class="col-md-6 border-right">
                                <small class="text-muted text-uppercase">Fecha de Creación</small>
                                <p class="mb-0 text-xs">{{ $persona->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted text-uppercase">Última Actualización</small>
                                <p class="mb-0 text-xs">{{ $persona->updated_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .card-flat {
            border-radius: 0;
        }

        .btn-flat {
            border-radius: 0 !important;
        }

        .list-group-item {
            border-left: 0;
            border-right: 0;
            border-radius: 0 !important;
            padding-left: 0;
            padding-right: 0;
        }

        .profile-user-img {
            border: 3px solid #17a2b8 !important;
            padding: 3px;
        }

        label {
            margin-bottom: 2px;
        }
    </style>
@stop
