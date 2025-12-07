@extends('layouts.app')

@section('title', 'Gestión de Departamentos')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading"><i class="fas fa-map mr-2"></i> Gestión de Departamentos</h3>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Inicio</a></div>
            <div class="breadcrumb-item"><a href="{{ route('limites.index') }}">Límites</a></div>
            <div class="breadcrumb-item">Departamentos</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-list"></i> Listado de Departamentos</h4>
                        <div class="card-header-action d-flex" style="gap:10px; align-items:center;">
                            <input type="text" id="buscador" class="form-control" 
                                placeholder="Buscar..." 
                                style="width: 250px;">

                            <a href="{{ route('limites.departamentos.create') }}" class="btn btn-primary" id="btn-nuevo-departamento">
                                <i class="fas fa-plus"></i> Nuevo Departamento
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert">
                                        <span>&times;</span>
                                    </button>
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <ul class="mb-0 pl-3">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <div class="table-responsive" style="max-height: calc(100vh - 350px); overflow-y: auto;">
                            <table class="table table-striped table-hover" id="table-departamentos">
                                <thead>
                                    <tr class="bg-light">
                                        <th style="position: sticky; top: 0; background: #d3d3d3; z-index: 10;">ID</th>
                                        <th style="position: sticky; top: 0; background: #d3d3d3; z-index: 10;">Nombre</th>
                                        <th style="position: sticky; top: 0; background: #d3d3d3; z-index: 10;">Descripción</th>
                                        <th style="position: sticky; top: 0; background: #d3d3d3; z-index: 10;">Geometría</th>
                                        <th style="position: sticky; top: 0; background: #d3d3d3; z-index: 10;">Tipo</th>
                                        <th style="position: sticky; top: 0; background: #d3d3d3; z-index: 10;">Imagen</th>
                                        <th style="position: sticky; top: 0; background: #d3d3d3; z-index: 10;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($departamentos as $departamento)
                                        <tr>
                                            <td>{{ $departamento->id }}</td>
                                            <td>{{ $departamento->nombre }}</td>
                                            <td>{{ $departamento->descripcion ?? 'N/A' }}</td>
                                            <td>
                                                @if($departamento->geometria)
                                                    <span class="badge badge-info" title="{{ json_encode($departamento->geometria) }}">
                                                        {{ substr(json_encode($departamento->geometria), 0, 30) }}...
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">Sin geometría</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-primary">{{ $departamento->tipo_geometria ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                @if($departamento->media && $departamento->media->count() > 0)
                                                    <img src="{{ asset('storage/' . $departamento->media->first()->archivo) }}"
                                                        class="img-thumbnail" 
                                                        style="width: 60px; height: 60px; object-fit: cover;"
                                                        alt="Imagen de {{ $departamento->nombre }}"
                                                        onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}';">
                                                @else
                                                    <span class="text-muted">Sin imagen</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center" style="gap: 5px;">
                                                    @can('editar-departamento')
                                                        <a href="{{ route('limites.departamentos.edit', $departamento) }}" 
                                                            class="btn btn-sm btn-warning" title="Editar">
                                                            <i class="fas fa-edit"> Editar</i>
                                                        </a>
                                                    @endcan

                                                    
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="fas fa-map empty-state-icon"></i>
                                                    <p class="mb-0">No hay departamentos registrados.</p>
                                                    @can('crear-departamento')
                                                        <a href="{{ route('limites.departamentos.create') }}" class="btn btn-primary mt-3">
                                                            <i class="fas fa-plus"></i> Crear Primer Departamento
                                                        </a>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if(isset($departamentos) && method_exists($departamentos, 'hasPages') && $departamentos->hasPages())
                            <div class="float-right mt-3">
                                {{ $departamentos->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('css')
<style>
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }

    /* ESTILO PARA EL BOTÓN NUEVO DEPARTAMENTO */
    #btn-nuevo-departamento {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
        color: white !important;
        box-shadow: none !important;
        background-image: none !important;
    }

    /* Eliminar el fondo blanco al pasar el mouse (Hover) */
    #btn-nuevo-departamento:hover, 
    #btn-nuevo-departamento:focus, 
    #btn-nuevo-departamento:active {
        background-color: #0b5ed7 !important; 
        border-color: #0a58ca !important;
        color: white !important;
        opacity: 1 !important;
    }

    .empty-state {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 30px;
    }

    .empty-state-icon {
        font-size: 3rem;
        color: #6777ef;
        margin-bottom: 1rem;
    }

    button[disabled] {
        opacity: 0.5;           
        cursor: not-allowed !important;      
        pointer-events: none;     
    }

    button[disabled]:hover {
        background-color: inherit;
        color: inherit;
    }

    .table-responsive {
        border-radius: 8px;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }

    .img-thumbnail {
        cursor: pointer;
        transition: transform 0.2s;
    }

    .img-thumbnail:hover {
        transform: scale(1.1);
    }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const buscador = document.getElementById("buscador");
        const filas = document.querySelectorAll("#table-departamentos tbody tr");

        if (buscador) {
            buscador.addEventListener("keyup", function() {
                const texto = this.value.toLowerCase();

                filas.forEach(fila => {
                    if (fila.querySelectorAll('td').length > 1) {
                        const contenidoFila = fila.textContent.toLowerCase();
                        fila.style.display = contenidoFila.includes(texto) ? "" : "none";
                    }
                });
            });
        }
    });

    function confirmarEliminar(id, nombre) {
        Swal.fire({
            title: '¿Eliminar departamento?',
            html: `¿Está seguro de eliminar el departamento <strong>${nombre}</strong>?<br>Esta acción no se puede deshacer.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash"></i> Sí, eliminar',
            cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-delete-' + id).submit();
            }
        });
    }
</script>
@endpush