@extends('adminlte::page')

@section('title', 'Malla Curricular | SIG@')

@section('css')
    <style>
        /* Contenedor Maestro para evitar el scroll del body */
        .malla-main-wrapper {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 180px);
            /* Ajuste según el header */
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }

        /* Layout de dos columnas principales */
        .malla-layout {
            display: flex;
            flex-direction: row;
            flex-grow: 1;
            overflow: hidden;
            width: 100%;
        }

        /* Área de la Malla (Izquierda) */
        .malla-section-container {
            flex: 1;
            /* Toma todo el espacio disponible */
            display: flex;
            overflow-x: auto;
            /* Scroll horizontal de los semestres */
            background: #f4f6f9;
            padding: 15px;
            gap: 15px;
            transition: all 0.3s ease;
        }

        /* Catálogo (Derecha) */
        .catalogo-col {
            width: 320px;
            /* Ancho fijo para estabilidad */
            min-width: 320px;
            border-left: 1px solid #ddd;
            display: flex;
            flex-direction: column;
            background: #ffffff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 10;
        }

        /* Clase de colapso */
        .catalogo-col.collapsed {
            width: 0;
            min-width: 0;
            overflow: hidden;
            border-left: none;
        }

        /* Estilo de Columnas (Semestres/Grados) */
        .columna-grado {
            min-width: 220px;
            max-width: 220px;
            background: #ebedef;
            border-radius: 5px;
            border: 1px solid #d1d4d7;
            display: flex;
            flex-direction: column;
            height: 100%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .grado-header {
            padding: 8px;
            background: #003366;
            color: white;
            font-size: 0.8rem;
            text-align: center;
            border-radius: 4px 4px 0 0;
        }

        .lista-materias {
            padding: 10px;
            flex-grow: 1;
            overflow-y: auto;
            min-height: 150px;
            scrollbar-width: thin;
        }

        /* Cards de Materias Compactas */
        .materia-card {
            background: white;
            border-radius: 3px;
            padding: 6px 10px;
            margin-bottom: 8px;
            font-size: 0.75rem;
            border-left: 4px solid #007bff;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            cursor: grab;
        }

        .materia-sigla {
            font-weight: bold;
            color: #6c757d;
            display: block;
            font-size: 0.65rem;
        }

        .materia-nombre {
            font-weight: 600;
            color: #333;
            display: block;
            line-height: 1.2;
        }

        #catalogo-list {
            overflow-y: auto;
            padding: 10px;
            flex-grow: 1;
        }

        .ghost-class {
            opacity: 0.5;
            background: #c8d6e5 !important;
            border: 2px dashed #003366 !important;
        }

        /* Materia que se está moviendo actualmente */
        .sortable-chosen {
            background: #e1f5fe !important;
            border: 2px solid #0288d1 !important;
        }

        /* Evitar que se arrastren las materias base sobre las nuevas */
        .lista-materias[data-puedo-editar="false"] {
            background-color: #f8f9fa;
            border: 1px dashed #ced4da;
        }
    </style>
@stop

@section('content_header')
    <div class="d-flex justify-content-between align-items-center py-2">
        <h1 class="h4 mb-0"><i class="fas fa-project-diagram mr-2"></i> Malla: <span
                class="text-primary">{{ $carrera->nombre }}</span></h1>
        <div class="btn-group">
            <button id="btn-toggle-catalogo" class="btn btn-sm btn-info">
                <i class="fas fa-list"></i> <span id="toggle-text">Catálogo</span>
            </button>
            <a href="{{ route('admin.carreras.index') }}" class="btn btn-sm btn-secondary">VOLVER</a>
        </div>
    </div>
@stop

@section('content')


    {{-- ESTRUCTURA CORREGIDA --}}
    <div class="malla-main-wrapper">
        <div class="malla-layout">

            <!-- ÁREA DE LA MALLA -->
            <div class="malla-section-container" id="malla-grid">
                @foreach ($grados as $grado)
                    @php $esBloqueado = in_array($grado->id, $gradosBloqueadosIds ?? []); @endphp
                    <div class="columna-grado">
                        <div class="grado-header {{ $esBloqueado ? 'bg-secondary' : '' }}">
                            {{ strtoupper($grado->nombre) }}
                            @if ($esBloqueado)
                                <br><small><i class="fas fa-lock"></i> BASE</small>
                            @endif
                        </div>
                        <div class="lista-materias sortable-list" data-grado-id="{{ $grado->id }}"
                            data-puedo-editar="{{ $esBloqueado ? 'false' : 'true' }}">
                            @foreach ($pensums->get($grado->id, []) as $item)
                                @php
                                    $esHeredado = $item->carrera_id != $carrera->id;
                                    $bloqueado = $esHeredado || $esBloqueado;
                                @endphp
                                <div class="materia-card {{ $bloqueado ? 'bg-light border-secondary' : '' }}"
                                    data-id="{{ $item->id }}"
                                    style="{{ $bloqueado ? 'cursor: not-allowed; opacity: 0.8;' : '' }}">

                                    <div class="d-flex justify-content-between align-items-start">
                                        <small class="materia-sigla font-weight-bold">{{ $item->materia->sigla }}</small>
                                        @if (!$bloqueado)
                                            <button class="btn btn-xs text-danger btn-eliminar p-0"
                                                data-id="{{ $item->id }}">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                        @endif
                                    </div>

                                    <span class="materia-nombre d-block text-truncate"
                                        title="{{ $item->materia->nombre }}">
                                        {{ $item->materia->nombre }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- ÁREA DEL CATÁLOGO -->
            {{-- Selector de Carrera --}}

            <div class="catalogo-col" id="catalogo-section">
                <div class="card mb-2">
                    <div class="card-body p-2">
                        <form action="{{ route('admin.pensums.index') }}" method="GET" class="form-inline">
                            <select name="carrera_id" class="form-control form-control-sm select2" style="width: 350px"
                                onchange="this.form.submit()">
                                @foreach ($carreras as $c)
                                    <option value="{{ $c->id }}" {{ $carrera->id == $c->id ? 'selected' : '' }}>
                                        {{ $c->nombre }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
                <div class="p-2 bg-light border-bottom">
                    <input type="text" id="buscarMateria" class="form-control form-control-sm"
                        placeholder="Buscar materia...">
                </div>
                <!-- En tu sección de ÁREA DEL CATÁLOGO -->
                <div id="catalogo-list">
                    @foreach ($materias_disponibles as $materia)
                        <div class="materia-card" data-materia-id="{{ $materia->id }}"
                            style="border-left-color: #17a2b8; cursor: move;">
                            <div class="d-flex justify-content-between align-items-start">
                                <small class="materia-sigla font-weight-bold text-info">{{ $materia->sigla }}</small>
                                <!-- Aquí está vacío, JS insertará el botón al soltarlo en la malla -->
                            </div>
                            <span class="materia-nombre d-block text-truncate">{{ $materia->nombre }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle Catálogo
            $('#btn-toggle-catalogo').on('click', function() {
                $('#catalogo-section').toggleClass('collapsed');
                const isCollapsed = $('#catalogo-section').hasClass('collapsed');
                $('#toggle-text').text(isCollapsed ? 'Mostrar Catálogo' : 'Catálogo');
                $(this).find('i').toggleClass('fa-list fa-plus-circle');
            });

            // Configurar el Catálogo para que la materia se MUEVA (no se clone)
            new Sortable(document.getElementById('catalogo-list'), {
                group: {
                    name: 'malla',
                    pull: true, // Cambiado de 'clone' a true para que desaparezca al arrastrar
                    put: false // Sigue evitando que metan cosas al catálogo
                },
                sort: false,
                animation: 150,
                ghostClass: 'ghost-class'
            });

            // 2. CONFIGURAR LISTAS DE LA MALLA (DESTINO)
            $('.sortable-list').each(function() {
                const canEdit = $(this).data('puedo-editar') === true;
                new Sortable(this, {
                    group: 'malla',
                    animation: 150,
                    ghostClass: 'ghost-class',
                    filter: '.bg-light',
                    disabled: !canEdit,

                    onAdd: function(evt) {
                        gestionarCambio(evt);
                    },
                    onUpdate: function(evt) {
                        gestionarCambio(evt);
                    }
                });
            });

            function gestionarCambio(evt) {
                const itemEl = evt.item;
                const nuevoGradoId = evt.to.dataset.gradoId;
                const pensumId = itemEl.getAttribute('data-id');
                const materiaId = itemEl.getAttribute('data-materia-id');

                if (pensumId) {
                    actualizarGrado(pensumId, nuevoGradoId);
                } else if (materiaId) {
                    // Al clonar, a veces se necesita limpiar estilos del catálogo
                    $(itemEl).css('border-left-color', '#007bff').css('cursor', 'grab');
                    crearAsignacion(materiaId, nuevoGradoId, itemEl);
                }
            }

            function crearAsignacion(materiaId, gradoId, element) {
                // Aseguramos que 'element' sea un objeto jQuery para evitar líos
                const $el = $(element);

                $.post("{{ route('admin.pensums.store') }}", {
                    _token: "{{ csrf_token() }}",
                    carrera_id: "{{ $carrera->id }}",
                    materia_id: materiaId,
                    grado_id: gradoId
                }).done(res => {
                    // 1. Actualizar IDs
                    $el.attr('data-id', res.id);
                    $el.removeAttr('data-materia-id');

                    // 2. INYECTAR LA "X" (Buscando con más precisión)
                    let headerDiv = $el.find('.d-flex');

                    // Si no encuentra .d-flex, intentamos buscar el primer div que contenga la sigla
                    if (headerDiv.length === 0) {
                        headerDiv = $el.children().first();
                    }

                    // Limpiamos cualquier botón viejo por si acaso y ponemos el nuevo
                    $el.find('.btn-eliminar').remove();

                    headerDiv.append(`
            <button type="button" class="btn btn-xs text-danger btn-eliminar p-0" data-id="${res.id}" style="margin-left: 5px;">
                <i class="fas fa-times-circle"></i>
            </button>
        `);

                    // 3. Ajustar visuales
                    $el.css({
                        'border-left': '4px solid #007bff',
                        'cursor': 'grab'
                    });
                    $el.find('.materia-sigla').removeClass('text-info');

                    toastr.success('Asignación exitosa');
                }).fail(err => {
                    $el.remove();
                    toastr.error('Error en el servidor');
                });
            }

            function actualizarGrado(pensumId, nuevoGradoId) {
                $.post("{{ route('admin.pensums.update-grado') }}", {
                    _token: "{{ csrf_token() }}",
                    id: pensumId,
                    grado_id: nuevoGradoId,
                    // Pasamos el ID de la carrera actual para la validación de herencia
                    carrera_contexto_id: "{{ $carrera->id }}"
                }).done(res => {
                    // Usamos el mensaje que viene del controlador para mayor claridad
                    toastr.info(res.message || 'Posición actualizada');
                }).fail(err => {
                    // Si el controlador devuelve un 403 (Prohibido), mostramos el mensaje específico
                    const msg = err.responseJSON ? err.responseJSON.message : 'Error al mover';
                    toastr.error(msg);

                    // Es vital recargar aquí para que la materia regrese a su semestre original en la vista
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                });
            }

            // Buscador
            $('#buscarMateria').on('keyup', function() {
                let val = $(this).val().toLowerCase();
                $('#catalogo-list .materia-card').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1);
                });
            });
            $(document).on('click', '.btn-eliminar', function() {
                const btn = $(this);
                const id = btn.data('id');

                if (confirm('¿Deseas quitar esta materia de la malla?')) {
                    $.ajax({
                        url: `/admin/pensums/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}",
                            // AGREGAMOS ESTO:
                            carrera_contexto_id: "{{ $carrera->id }}"
                        },
                        success: function(res) {
                            btn.closest('.materia-card').fadeOut(function() {
                                $(this).remove();
                            });
                            toastr.warning('Materia removida de la malla');
                        },
                        error: function(err) {
                            // Es importante ver el error en consola si falla
                            const msg = err.responseJSON ? err.responseJSON.message :
                                'Error desconocido';
                            toastr.error(msg);
                        }
                    });
                }
            });
        });
    </script>
@stop
