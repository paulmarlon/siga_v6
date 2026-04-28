@extends('adminlte::page')

@section('title', 'Paralelos | SIG@')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3">
        <h1 class="text-dark" style="font-weight: 500; font-size: 1.6rem;">
            <i class="fas fa-layer-group text-primary mr-2"></i> Catálogo de Paralelos
        </h1>
        <a href="{{ route('admin.paralelos.create') }}" class="btn btn-primary shadow-sm"
            style="background-color: #003366; border-color: #003366; border-radius: 4px;">
            <i class="fas fa-plus-circle mr-1"></i> NUEVO PARALELO
        </a>
    </div>
@stop

@section('content')
    <div class="row mt-4">
        @foreach ($paralelos as $paralelo)
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <div class="card shadow-sm border-light mb-4 card-paralelo">
                    <div class="card-body text-center p-3">
                        <small class="text-muted d-block mb-2">#{{ $paralelo->id }}</small>
                        <h2 class="font-weight-bold text-dark mb-3">{{ $paralelo->nombre }}</h2>
                        <div class="border-top pt-2">
                            <a href="{{ route('admin.paralelos.edit', $paralelo->id) }}"
                                class="btn btn-sm btn-block btn-outline-success">
                                <i class="fas fa-edit"></i> EDITAR
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@stop

@section('css')
    <style>
        .card-paralelo {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border-radius: 10px;
        }

        .card-paralelo:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
            border-color: #003366 !important;
        }

        .card-paralelo h2 {
            font-size: 2rem;
            color: #003366;
        }
    </style>
@stop
