@extends('adminlte::page')

@section('title', 'Editar Paralelo | SIG@')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-edit text-success mr-2"></i> Editar Paralelo</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.paralelos.index') }}">Catálogo</a></li>
                    <li class="breadcrumb-item active">Edición</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-success card-outline shadow-sm border-0">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold text-muted">
                        Modificar Identificador del Catálogo
                    </h3>
                </div>

                <form action="{{ route('admin.paralelos.update', $paralelo->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <div class="form-group">
                            <label for="nombre" class="small font-weight-bold text-dark">
                                IDENTIFICADOR / NOMBRE
                            </label>
                            <input type="text" name="nombre" id="nombre"
                                class="form-control form-control-lg @error('nombre') is-invalid @enderror"
                                value="{{ old('nombre', $paralelo->nombre) }}" required
                                style="text-transform: uppercase; letter-spacing: 2px; font-weight: bold;">

                            @error('nombre')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <p class="text-muted mt-2 small">
                                <i class="fas fa-info-circle mr-1"></i>
                                Al cambiar este nombre, se actualizará en todas las ofertas académicas vinculadas
                                históricamente.
                            </p>
                        </div>
                    </div>

                    <div class="card-footer bg-light d-flex justify-content-between">
                        <a href="{{ route('admin.paralelos.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> REGRESAR
                        </a>
                        <button type="submit" class="btn btn-success shadow-sm px-4">
                            <i class="fas fa-save mr-1"></i> ACTUALIZAR CATÁLOGO
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .card {
            border-radius: 8px;
        }

        .form-control:focus {
            border-color: #28a745;
            box-shadow: none;
        }
    </style>
@stop
