@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">
                <i class="fas fa-globe-americas mr-2"></i> Gestión de Áreas Protegidas
            </h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></div>
                <div class="breadcrumb-item active">Áreas Protegidas</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-lg">
                        <div class="card-header custom-card-header">
                            <h4><i class="fas fa-list-ul mr-2"></i> Listado de Áreas</h4>
                            <div class="card-header-action">
                                <a href="{{ route('areas.create') }}" class="btn btn-primary btn-create">
                                    <i class="fas fa-plus-circle mr-1"></i> Nueva Área Protegida
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Por favor, corrige los siguientes errores:
                                    <ul class="mb-0 mt-2 pl-4">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="table-responsive" style="max-height: calc(100vh - 350px); overflow-y: auto;">
                                <table class="table table-striped table-hover table-md" id="table-areas">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th class="sticky-header" style="width: 25%">Área Protegida</th>
                                            <th class="sticky-header" style="width: 50%">Descripción</th>
                                            <th class="sticky-header" style="width: 15%">Estado</th>
                                            <th class="sticky-header text-center" style="width: 10%">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($areas as $area)
                                            <tr>
                                                <td>
                                                    <strong class="text-dark">{{ $area->area }}</strong>
                                                </td>
                                                <td>
                                                    @if ($area->descripcion)
                                                        {{ Str::limit($area->descripcion, 70) }}
                                                        @if (strlen($area->descripcion) > 70)
                                                            <a href="#" data-toggle="tooltip"
                                                                title="{{ $area->descripcion }}" class="text-info">
                                                                <i class="fas fa-info-circle ml-1"></i>
                                                            </a>
                                                        @endif
                                                    @else
                                                        <span class="text-muted font-italic">Sin descripción detallada</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $area->estado ? 'success' : 'danger' }} badge-lg">
                                                        <i
                                                            class="fas {{ $area->estado ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                                        {{ $area->estado ? 'Activa' : 'Inactiva' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group"
                                                        aria-label="Acciones de Área Protegida">
                                                        <a href="{{ route('areas.edit', $area) }}"
                                                            class="btn btn-icon btn-sm btn-outline-warning"
                                                            data-toggle="tooltip" title="Editar área">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        <form action="{{ route('areas.toggle', $area->id) }}"
                                                            method="POST" class="d-inline ml-1"
                                                            id="form-toggle-{{ $area->id }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            @if ($area->estado == 1)
                                                                <button type="button"
                                                                    class="btn btn-icon btn-sm btn-outline-danger btn-toggle-state"
                                                                    data-toggle="tooltip" title="Desactivar área"
                                                                    onclick="confirmarAccion({{ $area->id }}, 'desactivar')">
                                                                    <i class="fas fa-toggle-off"></i>
                                                                </button>
                                                            @else
                                                                <button type="button"
                                                                    class="btn btn-icon btn-sm btn-outline-success btn-toggle-state"
                                                                    data-toggle="tooltip" title="Activar área"
                                                                    onclick="confirmarAccion({{ $area->id }}, 'activar')">
                                                                    <i class="fas fa-toggle-on"></i>
                                                                </button>
                                                            @endif
                                                        </form>

                                                        <form action="{{ route('areas.destroy', $area->id) }}"
                                                            method="POST" class="d-inline ml-1"
                                                            id="form-delete-{{ $area->id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button"
                                                                class="btn btn-icon btn-sm btn-outline-secondary"
                                                                data-toggle="tooltip"
                                                                title="{{ $area->zonas_count > 0 ? 'No se puede eliminar, tiene zonas asociadas' : 'Eliminar área' }}"
                                                                onclick="confirmarEliminar({{ $area->id }})"
                                                                {{ $area->zonas_count > 0 ? 'disabled' : '' }}>
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-5">
                                                    <div class="empty-state custom-empty-state">
                                                        <div class="empty-state-icon text-muted">
                                                            <i class="fas fa-map-marker-alt"></i>
                                                        </div>
                                                        <h2 class="text-dark">No se encontraron áreas protegidas</h2>
                                                        <p class="lead text-dark-50">
                                                            Aún no hay áreas protegidas registradas en el sistema.
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                {{ $areas->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script>
        function confirmarAccion(areaId, accion) {
            let titulo = accion === 'desactivar' ?
                '¿Desactivar esta área protegida?' :
                '¿Activar esta área protegida?';

            let texto = accion === 'desactivar' ?
                'El área dejará de estar disponible. Podrás reactivarla después.' :
                'El área volverá a estar disponible.';

            let icono = accion === 'desactivar' ? 'warning' : 'info';
            let botonConfirmar = accion === 'desactivar' ? 'Sí, Desactivar' : 'Sí, Activar';
            let color = accion === 'desactivar' ? '#dc3545' : '#198754';

            Swal.fire({
                title: titulo,
                text: texto,
                icon: icono,
                showCancelButton: true,
                confirmButtonColor: color,
                cancelButtonColor: '#6c757d',
                confirmButtonText: botonConfirmar,
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-toggle-' + areaId).submit();
                }
            })
        }

        function confirmarEliminar(areaId) {
            Swal.fire({
                title: '¿Eliminar esta área protegida?',
                text: "¡Esta acción es irreversible y se eliminará permanentemente!",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, Eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-delete-' + areaId).submit();
                }
            })
        }

        // Inicializar tooltips de Bootstrap
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endpush

@push('css')
    <style>
        /* Estilo para el botón "Crear" (Azul Sólido) */
        .btn-create {
            background-color: #2f55d4 !important;
            border-color: #2f55d4 !important;
            color: #ffffff !important;
            border-radius: 50px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            box-shadow: none !important;
            transition: opacity 0.3s ease;
        }

        /* Estados interactivos (Hover, Focus, Active) */
        .btn-create:hover,
        .btn-create:focus,
        .btn-create:active,
        .btn-create:visited {
            background-color: #2f55d4 !important;
            border-color: #2f55d4 !important;
            color: #ffffff !important;
            box-shadow: none !important;
            outline: none !important;
            opacity: 0.9;
            transform: none !important;
        }

        /* Diseño de la Tarjeta y Encabezado */
        .card.shadow-lg {
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        .custom-card-header {
            background: linear-gradient(90deg, #5d75e8 0%, #a9b5f5 100%);
            color: #ffffff !important;
            border-radius: 12px 12px 0 0 !important;
            padding: 1rem 1.5rem;
        }

        .custom-card-header h4 {
            color: #ffffff !important;
            font-weight: 600;
            margin-bottom: 0;
        }

        /* Estilo del Botón de Nueva Área */
        .btn-primary {
            background-color: #adb5bd;
            border-color: #adb5bd;
        }

        /* Estilo de la Tabla (Sticky Header mejorado) */
        .table thead .sticky-header {
            position: sticky;
            top: 0;
            background-color: #adb5bd !important;
            color: #ffffff !important;
            z-index: 10;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.8px;
            border-bottom: 3px solid #6c757d;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.03);
        }

        .table td {
            vertical-align: middle;
        }

        /* Estilo de los Badges */
        .badge-lg {
            padding: 0.4em 0.8em;
            font-size: 85%;
        }

        /* Estilo para el Empty State */
        .custom-empty-state {
            background-color: #f8f9fa;
            border: 1px dashed #ced4da;
            border-radius: 8px;
            padding: 40px;
            color: #6c757d;
        }

        .empty-state-icon i {
            font-size: 3.5rem;
            color: #adb5bd;
        }

        /* Estilo de botones de acción para mejor visibilidad */
        .btn-group .btn-icon {
            border-radius: 0.25rem !important;
        }

        .btn-group .btn-icon:hover {
            opacity: 0.8;
        }

        /* Desactivar Botón */
        button[disabled] {
            opacity: 0.5;
            cursor: not-allowed !important;
            pointer-events: none;
        }
    </style>
@endpush
