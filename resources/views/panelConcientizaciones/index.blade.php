@extends('layouts.app')

@section('title', 'Panel de Concientización')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"><i class="fas fa-video text-primary mr-2"></i> Panel de Concientización</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('home') }}">Inicio</a></div>
                <div class="breadcrumb-item">Concientización</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-film text-primary mr-2"></i> Listado de Videos</h4>
                            <div class="card-header-action">
                                <a href="{{ route('panelConcientizaciones.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Nuevo Video
                                </a>
                            </div>
                        </div>

                        <div class="card-body">

                            <div class="table-responsive" style="height: calc(100vh - 450px)">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr class="bg-light text-white">
                                            <th>Título</th>
                                            <th>Descripción</th>
                                            <th>Categoría</th>
                                            <th>Video</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($concientizaciones as $item)
                                            <tr>
                                                <td>{{ $item->titulo }}</td>
                                                <td>{{ Str::limit($item->descripcion, 100) }}</td>
                                                <td>
                                                    <span
                                                        class="badge badge-info text-uppercase">{{ $item->categoria }}</span>
                                                </td>
                                                <td>
                                                    @if ($item->video_path)
                                                        <video width="120" height="80" controls>
                                                            <source src="{{ asset('storage/' . $item->video_path) }}"
                                                                type="video/mp4">
                                                            Tu navegador no soporta videos.
                                                        </video>
                                                    @else
                                                        <span class="text-muted">Sin video</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center" style="gap: 5px;">
                                                        <a href="{{ route('panelConcientizaciones.edit', $item) }}"
                                                            class="btn btn-sm btn-warning">
                                                            <i class="fas fa-edit"> Editar</i>
                                                        </a>

                                                        <form
                                                            action="{{ route('panelConcientizaciones.destroy', $item) }}"
                                                            method="POST" id="form-delete-{{ $item->id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-sm btn-danger"
                                                                onclick="confirmarEliminar({{ $item->id }}, 'video')">
                                                                <i class="fas fa-trash"> Borrar</i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No hay videos registrados.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="float-right mt-3">
                                {{ $concientizaciones->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('css')
    <style>
        .btn-primary {
            background-color: #2f55d4 !important;
            border-color: #2f55d4 !important;
            box-shadow: none !important;
            color: #fff !important;
            text-decoration: none !important;
        }

        /* Efecto al pasar el mouse */
        .btn-primary:hover {
            background-color: #2442a8 !important;
            border-color: #2442a8 !important;
            box-shadow: none !important;
        }

        .table th {
            position: sticky;
            top: 0;
            background: rgb(211, 211, 211);
            z-index: 10;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }

        .badge-info {
            background-color: #36b9cc;
            padding: 5px 8px;
        }

        video {
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmarEliminar(id, tipo) {
            Swal.fire({
                title: `¿Eliminar ${tipo}?`,
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-delete-' + id).submit();
                }
            });
        }
    </script>
@endpush
