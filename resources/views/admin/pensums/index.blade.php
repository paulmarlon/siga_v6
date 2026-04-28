@extends('adminlte::page')

@section('title', 'Malla Curricular | SIG@')

@section('css')
    <style>
        .malla-container {
            display: flex;
            overflow-x: auto;
            padding: 20px;
            gap: 15px;
            align-items: flex-start;
            background: #f4f6f9;
            min-height: 70vh;
        }

        .columna-grado {
            min-width: 280px;
            max-width: 280px;
            background: #ebedef;
            border-radius: 6px;
            border: 1px solid #d1d4d7;
            display: flex;
            flex-direction: column;
            max-height: 100%;
        }

        .grado-header {
            padding: 12px;
            background: #003366;
            color: white;
            border-radius: 6px 6px 0 0;
            font-weight: bold;
            text-align: center;
        }

        .lista-materias {
            padding: 10px;
            min-height: 150px;
            flex-grow: 1;
        }

        .materia-card {
            background: white;
            border-radius: 4px;
            padding: 12px;
            margin-bottom: 10px;
            cursor: grab;
            border-left: 4px solid #007bff;
            transition: all 0.2s;
        }

        .materia-card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .materia-card.heredada {
            opacity: 0.8;
            filter: grayscale(0.5);
        }

        .materia-card.heredada:hover {
            transform: none;
            box-shadow: none;
        }

        /* Feedback visual cuando una columna está bloqueada */
        .sortable-list[data-put="false"] {
            background-color: #fceaea;
            border: 1px dashed #f5c6cb;
        }

        .materia-sigla {
            font-size: 0.75rem;
            color: #6c757d;
            font-weight: bold;
            display: block;
        }

        .materia-nombre {
            font-size: 0.9rem;
            font-weight: 600;
            color: #333;
        }

        .catalogo-sidebar {
            width: 300px;
            background: white;
            border-left: 1px solid #ddd;
            height: calc(100vh - 57px);
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
        }

        .ghost-class {
            opacity: 0.4;
            background: #e2eafc !important;
            border: 2px dashed #003366 !important;
        }
    </style>
@stop

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-project-diagram mr-2"></i> Malla Curricular: <span
                class="text-primary">{{ $carrera->nombre }}</span></h1>
        <div class="btn-group">
            <button class="btn btn-default shadow-sm" onclick="window.location.reload()"><i class="fas fa-sync"></i></button>
            <a href="{{ route('admin.carreras.index') }}" class="btn btn-secondary shadow-sm">VOLVER</a>
        </div>
    </div>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.pensums.index') }}" method="GET" id="filtroCarrera">
                <label>Seleccione la Carrera para gestionar su Malla:</label>
                <select name="carrera_id" class="form-control select2" onchange="this.form.submit()">
                    <option value="">-- Seleccione Carrera --</option>
                    @foreach ($carreras as $c)
                        <option value="{{ $c->id }}"
                            {{ isset($carrera) && $carrera->id == $c->id ? 'selected' : '' }}>
                            {{ $c->nombre }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
    <div class="row no-gutters shadow-sm" style="background: #fff; border-radius: 8px; overflow: hidden;">
        {{-- ÁREA DE LA MALLA (MATRIZ) --}}
        <div class="col-md-9 border-right">
            <div class="malla-container" id="malla-grid">
                @foreach ($grados as $grado)
                    <div class="columna-grado shadow-sm">
                        <div class="grado-header">
                            {{ strtoupper($grado->nombre) }}
                            <small class="d-block text-white-50">Orden: {{ $grado->orden }}</small>
                        </div>

                        {{-- Contenedor de caída para Sortable --}}
                        <div class="lista-materias sortable-list" data-grado-id="{{ $grado->id }}">
                            @foreach ($pensums->where('grado_id', $grado->id) as $item)
                                @php
                                    // Si el pensum NO pertenece a la carrera actual, es heredado (Tronco Común)
                                    $esHeredado = $item->carrera_id != $carrera->id;
                                @endphp

                                <div class="materia-card shadow-sm {{ $esHeredado ? 'heredada' : '' }}"
                                    data-id="{{ $item->id }}"
                                    style="{{ $esHeredado ? 'border-left-color: #6c757d; cursor: not-allowed; background: #f8f9fa;' : '' }}">

                                    <span class="materia-sigla">{{ $item->materia->sigla }}</span>
                                    <span class="materia-nombre">{{ $item->materia->nombre }}</span>

                                    <div class="mt-2 d-flex justify-content-between align-items-center">
                                        @if ($esHeredado)
                                            <span class="badge badge-secondary" style="font-size: 0.6rem;">TRONCO
                                                COMÚN</span>
                                        @else
                                            <span></span> {{-- Espacio --}}
                                            <button class="btn btn-xs text-danger btn-eliminar"
                                                data-id="{{ $item->id }}">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- CATÁLOGO LATERAL --}}
        <div class="col-md-3">
            <div class="p-3 bg-light border-bottom">
                <h5 class="mb-0 font-weight-bold">CATÁLOGO GLOBAL</h5>
                <small class="text-muted">Arrastra materias a la malla</small>
                <input type="text" id="buscarMateria" class="form-control form-control-sm mt-2"
                    placeholder="Buscar materia...">
            </div>
            <div class="p-3" id="catalogo-list" style="height: 600px; overflow-y: auto;">
                @foreach ($materias_disponibles as $materia)
                    <div class="materia-card border-left-info shadow-sm" data-materia-id="{{ $materia->id }}"
                        style="border-left-color: #17a2b8; cursor: copy;">
                        <span class="materia-sigla text-info">{{ $materia->sigla }}</span>
                        <span class="materia-nombre">{{ $materia->nombre }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@stop

@section('js')
    {{-- Cargamos SortableJS vía CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
        $(document).ready(function() {
            // 1. Configurar cada columna de grado como lista de Sortable
            $('.sortable-list').each(function() {
                const gradoId = this.dataset.gradoId;
                const esCicloBase = $(this).closest('.columna-grado').find('small').text().includes(
                        'Orden: 1') ||
                    $(this).closest('.columna-grado').find('small').text().includes('Orden: 2');

                const bloqueoIngreso = "{{ $carrera->carrera_base_id }}" !== "" && esCicloBase;

                new Sortable(this, {
                    group: {
                        name: 'malla',
                        pull: function(sub, el) {
                            return !el.classList.contains('heredada');
                        },
                        put: !bloqueoIngreso
                    },
                    animation: 150,
                    ghostClass: 'ghost-class',
                    filter: '.heredada',
                    onAdd: function(evt) { // <--- ¡ESTO ES VITAL PARA GUARDAR!
                        const itemEl = evt.item;
                        const nuevoGradoId = evt.to.dataset.gradoId;
                        const pensumId = itemEl.dataset.id;
                        const materiaId = itemEl.dataset.materiaId;

                        if (materiaId) {
                            crearAsignacion(materiaId, nuevoGradoId, itemEl);
                        } else {
                            actualizarGrado(pensumId, nuevoGradoId);
                        }
                    }
                });
            });

            // 2. Configurar el catálogo (solo permite arrastrar hacia afuera)
            new Sortable(document.getElementById('catalogo-list'), {
                group: {
                    name: 'malla',
                    pull: 'clone', // Clona el elemento al arrastrarlo
                    put: false // No permite soltar materias de la malla aquí
                },
                sort: false,
                animation: 150
            });

            // AJAX: Actualizar grado de materia existente
            function actualizarGrado(id, gradoId) {
                $.post("{{ route('admin.pensums.update-grado') }}", {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    grado_id: gradoId
                }).done(data => {
                    toastr.success('Malla actualizada');
                }).fail(err => {
                    toastr.error('Error al mover materia');
                });
            }

            // AJAX: Crear nueva entrada en Pensum
            function crearAsignacion(materiaId, gradoId, element) {
                $.post("{{ route('admin.pensums.store') }}", {
                    _token: "{{ csrf_token() }}",
                    carrera_id: "{{ $carrera->id }}",
                    materia_id: materiaId,
                    grado_id: gradoId
                }).done(response => {
                    // Refrescamos ID para que ahora se pueda mover o borrar
                    $(element).attr('data-id', response.id);
                    $(element).removeAttr('data-materia-id');
                    toastr.success('Materia asignada');
                }).fail(err => {
                    $(element).remove(); // Si falla (ej: duplicado), eliminamos visualmente
                    toastr.error(err.responseJSON.message || 'No se pudo asignar');
                });
            }
        });
    </script>
@stop
