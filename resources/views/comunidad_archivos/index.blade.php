@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading"><i class="fas fa-photo-video text-primary mr-2"></i> Gestionar Media de la Comunidad</h3>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">             
                <div class="card card-fixed-scroll mb-0">             
                    <div class="card-body scroll-content">
                        <ul class="nav nav-tabs sticky-top bg-white pt-2" id="mediaTabs" role="tablist" style="z-index: 10;">
                            <li class="nav-item">
                                <a class="nav-link" id="pendientes-tab" data-toggle="tab" href="#pendientes" role="tab" aria-controls="pendientes" aria-selected="true">
                                    Pendientes de Aprobación 
                                    <span class="badge badge-warning ml-1">{{ $videosPendientes->count() + $imagenesPendientes->count() }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="aprobados-tab" data-toggle="tab" href="#aprobados" role="tab" aria-controls="aprobados" aria-selected="false">
                                    Archivos Aprobados
                                    <span class="badge badge-success ml-1">{{ $videosAprobados->count() + $imagenesAprobadas->count() }}</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content" id="mediaTabsContent">
                            
                            <div class="tab-pane fade show active" id="pendientes" role="tabpanel" aria-labelledby="pendientes-tab">
                                <div class="mt-4">
                                    
                                    {{-- VIDEOS PENDIENTES --}}
                                    <h5 class="text-warning mb-3"><i class="fas fa-video mr-2"></i> Videos Pendientes</h5>
                                    
                                    @if ($videosPendientes->isEmpty())
                                        <div class="alert alert-light border">No hay videos pendientes.</div>
                                    @else
                                        <div class="row">
                                            @foreach ($videosPendientes as $video)
                                                <div class="col-md-6 col-lg-4 mb-4">
                                                    <div class="card h-100 border shadow-sm">
                                                        <div class="card-header bg-light"> 
                                                            Titulo: <strong class="text-truncate w-100" title="{{ $video->titulo }}">{{ $video->titulo }}</strong>
                                                        </div>

                                                        <div class="card-body p-2">
                                                            <div class="ratio ratio-16x9 bg-dark rounded">
                                                                <video src="{{ asset('storage/' . $video->ruta_archivo) }}" controls class="w-100 h-100" style="object-fit: contain;"></video>
                                                            </div>

                                                            <div class="mt-2 px-2">
                                                                <small class="d-block text-muted"><i class="fas fa-user"></i> Por: {{ $video->nombre }}</small>
                                                                <small class="d-block text-muted"><i class="far fa-clock"></i> Fecha: {{ $video->created_at->format('d/m/Y') }}</small>
                                                                <p class="mt-2 small text-truncate">{{ $video->descripcion }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer bg-white d-flex justify-content-between">
                                                            <button type="button" class="btn btn-success btn-sm btn-approve" 
                                                                    data-id="{{ $video->id }}" 
                                                                    data-url="{{ route('admin.media.approve', $video->id) }}">
                                                                <i class="fas fa-check"></i> Aprobar
                                                            </button>
                                                            
                                                            <button type="button" class="btn btn-danger btn-sm btn-delete" data-form-id="delete-video-pending-{{ $video->id }}">
                                                                <i class="fas fa-trash"></i> Eliminar
                                                            </button>
                                                            <form id="delete-video-pending-{{ $video->id }}" action="{{ route('admin.media.destroy', $video->id) }}" method="POST" style="display: none;">
                                                                @csrf @method('DELETE')
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <hr>

                                    {{-- IMÁGENES PENDIENTES --}}
                                    <h5 class="text-warning mb-3"><i class="fas fa-image mr-2"></i> Imágenes Pendientes</h5>
                                    
                                    @if ($imagenesPendientes->isEmpty())
                                        <div class="alert alert-light border">No hay imágenes pendientes.</div>
                                    @else
                                        <div class="row">
                                            @foreach ($imagenesPendientes as $imagen)
                                                <div class="col-md-6 col-lg-4 mb-4">
                                                    <div class="card h-100 border shadow-sm">
                                                        <div class="card-header bg-light">
                                                            Título: <strong class="text-truncate w-100" title="{{ $imagen->titulo }}">{{ $imagen->titulo }}</strong>
                                                        </div>

                                                        <div class="card-body p-2">
                                                            <div style="height: 200px; overflow: hidden; border-radius: 5px; cursor: pointer;" 
                                                                onclick="showImage('{{ asset('storage/' . $imagen->ruta_archivo) }}', '{{ $imagen->titulo }}')"
                                                                title="Clic para ampliar">
                                                                <img src="{{ asset('storage/' . $imagen->ruta_archivo) }}" class="w-100 h-100" style="object-fit: cover; transition: transform 0.3s;">
                                                            </div>

                                                            <div class="mt-2 px-2">
                                                                <small class="d-block text-muted"><i class="fas fa-user"></i> Por: {{ $imagen->nombre }}</small>
                                                                <small class="d-block text-muted"><i class="far fa-clock"></i> Fecha: {{ $imagen->created_at->format('d/m/Y') }}</small>
                                                                <p class="mt-2 small text-truncate">{{ $imagen->descripcion }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer bg-white d-flex justify-content-between">
                                                            <button type="button" class="btn btn-success btn-sm btn-approve" 
                                                                    data-id="{{ $imagen->id }}" 
                                                                    data-url="{{ route('admin.media.approve', $imagen->id) }}">
                                                                <i class="fas fa-check"></i> Aprobar
                                                            </button>

                                                            <button type="button" class="btn btn-danger btn-sm btn-delete" data-form-id="delete-image-pending-{{ $imagen->id }}">
                                                                <i class="fas fa-trash"></i> Eliminar
                                                            </button>

                                                            <form id="delete-image-pending-{{ $imagen->id }}" action="{{ route('admin.media.destroy', $imagen->id) }}" method="POST" style="display: none;">
                                                                @csrf @method('DELETE')
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="tab-pane fade" id="aprobados" role="tabpanel" aria-labelledby="aprobados-tab">
                                <div class="mt-4">
                                    
                                    {{-- VIDEOS APROBADOS --}}
                                    <h5 class="text-success mb-3"><i class="fas fa-video mr-2"></i> Videos Aprobados</h5>
                                    @if ($videosAprobados->isEmpty())
                                        <div class="alert alert-light border">No hay videos aprobados.</div>
                                    @else
                                        <div class="row">
                                            @foreach ($videosAprobados as $video)
                                                <div class="col-md-6 col-lg-4 mb-4">
                                                    <div class="card h-100 border border-success shadow-sm">
                                                        <div class="card-header bg-white text-success">
                                                            <strong class="text-truncate w-100"> Título: {{ $video->titulo }}</strong>
                                                        </div>

                                                        <div class="card-body p-2">
                                                            <div class="ratio ratio-16x9 bg-dark rounded">
                                                                <video src="{{ asset('storage/' . $video->ruta_archivo) }}" controls class="w-100 h-100" style="object-fit: contain;"></video>
                                                            </div>
                                                            <p class="mt-2 small text-muted px-2">Por: {{ $video->nombre }}</p>
                                                        </div>

                                                        <div class="card-footer bg-white text-right">
                                                            <button type="button" class="btn btn-outline-danger btn-sm btn-delete" data-form-id="delete-video-approved-{{ $video->id }}">
                                                                <i class="fas fa-trash"></i> Eliminar
                                                            </button>

                                                            <form id="delete-video-approved-{{ $video->id }}" action="{{ route('admin.media.destroy', $video->id) }}" method="POST" style="display: none;">
                                                                @csrf @method('DELETE')
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <hr>

                                    {{-- IMÁGENES APROBADAS --}}
                                    <h5 class="text-success mb-3"><i class="fas fa-image mr-2"></i> Imágenes Aprobadas</h5>
                                    @if ($imagenesAprobadas->isEmpty())
                                        <div class="alert alert-light border">No hay imágenes aprobadas.</div>
                                    @else
                                        <div class="row">
                                            @foreach ($imagenesAprobadas as $imagen)
                                                <div class="col-md-6 col-lg-4 mb-4">
                                                    <div class="card h-100 border border-success shadow-sm">
                                                        <div class="card-header bg-white text-success">
                                                            <strong class="text-truncate w-100"> Título: {{ $imagen->titulo }}</strong>
                                                        </div>
                                                        <div class="card-body p-2">                                                                                         
                                                            <div style="height: 200px; overflow: hidden; border-radius: 5px; cursor: pointer;" 
                                                                onclick="showImage('{{ asset('storage/' . $imagen->ruta_archivo) }}', '{{ $imagen->titulo }}')"
                                                                title="Clic para ampliar">
                                                                <img src="{{ asset('storage/' . $imagen->ruta_archivo) }}" class="w-100 h-100" style="object-fit: cover; transition: transform 0.3s;">
                                                            </div>

                                                            <p class="mt-2 small text-muted px-2">Por: {{ $imagen->nombre }}</p>
                                                        </div>
                                                        <div class="card-footer bg-white text-right">
                                                            <button type="button" class="btn btn-outline-danger btn-sm btn-delete" data-form-id="delete-image-approved-{{ $imagen->id }}">
                                                                <i class="fas fa-trash"></i> Eliminar
                                                            </button>
                                                            <form id="delete-image-approved-{{ $imagen->id }}" action="{{ route('admin.media.destroy', $imagen->id) }}" method="POST" style="display: none;">
                                                                @csrf @method('DELETE')
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                </div>
                            </div>

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
    .card-fixed-scroll {
        height: calc(100vh - 240px); 
        display: flex; flex-direction: column; overflow: hidden;
        border-radius: 8px; border: none; box-shadow: 0 0 15px rgba(0,0,0,0.05);
    }
    .scroll-content {
        flex: 1; overflow-y: auto; overflow-x: hidden; padding: 20px; background-color: #fff;
    }
    .scroll-content::-webkit-scrollbar { width: 8px; }
    .scroll-content::-webkit-scrollbar-track { background: #f8f9fa; }
    .scroll-content::-webkit-scrollbar-thumb { background: #cdd3d8; border-radius: 4px; }
    .scroll-content::-webkit-scrollbar-thumb:hover { background: #6777ef; }
    .nav-tabs.sticky-top { top: 0; background: white; border-bottom: 1px solid #dee2e6; }
    
    /* Efecto hover en imágenes */
    .card-body img:hover {
        transform: scale(1.05);
    }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function () {
                const formId = this.getAttribute('data-form-id');
                Swal.fire({
                    title: '¿Eliminar permanentemente?',
                    text: "Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(formId).submit();
                    }
                });
            });
        });

        // --- LÓGICA PARA APROBAR (AJAX) ---
        document.querySelectorAll('.btn-approve').forEach(button => {
            button.addEventListener('click', function () {
                const url = this.getAttribute('data-url');
                const card = this.closest('.col-md-6'); 

                const originalContent = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                this.disabled = true;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-HTTP-Method-Override': 'PATCH'
                    },
                    body: JSON.stringify({})
                })
                .then(response => {
                    if (!response.ok) throw new Error('Error en la respuesta');
                    return response.json();
                })
                .then(data => {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Archivo aprobado',
                        showConfirmButton: false,
                        timer: 4000,
                        toast: true
                    });
                    card.style.transition = "all 0.5s ease";
                    card.style.transform = "scale(0.9)";
                    card.style.opacity = "0";
                    setTimeout(() => { card.remove(); }, 500);
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'No se pudo aprobar.', 'error');
                    this.innerHTML = originalContent;
                    this.disabled = false;
                });
            });
        });
    });
</script>
@endpush