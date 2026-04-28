@extends('adminlte::page')

@section('content_header')
    <h1><i class="fas fa-plus-circle text-primary mr-2"></i> Registrar Nuevo Paralelo</h1>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-primary card-outline shadow">
                <form action="{{ route('admin.paralelos.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nombre" class="font-weight-bold">Nombre del Paralelo (Ej: A, B, Único)</label>
                            <input type="text" name="nombre" id="nombre"
                                class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}"
                                required autofocus style="text-transform: uppercase;">
                            @error('nombre')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer bg-light d-flex justify-content-between">
                        <a href="{{ route('admin.paralelos.index') }}" class="btn btn-secondary">CANCELAR</a>
                        <button type="submit" class="btn btn-primary" style="background-color: #003366;">GUARDAR
                            REGISTRO</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
