@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Gestionar Media de la Comunidad</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <!-- Pestañas de Navegación -->
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pendientes-tab" data-toggle="tab" href="#pendientes" role="tab" aria-controls="pendientes" aria-selected="true">
                                    Pendientes de Aprobación 
                                    <span class="badge badge-warning">{{ $videosPendientes->count() + $imagenesPendientes->count() }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="aprobados-tab" data-toggle="tab" href="#aprobados" role="tab" aria-controls="aprobados" aria-selected="false">
                                    Archivos Aprobados
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="pendientes" role="tabpanel" aria-labelledby="pendientes-tab">
                                <div class="mt-3">
                                    <h4><i class="fas fa-video text-warning"></i> Videos Pendientes</h4>
                                    @if ($videosPendientes->isEmpty())
                                        <p class="text-muted">No hay videos pendientes de aprobación.</p>
                                    @else
                                        <div class="row">
                                            @foreach ($videosPendientes as $video)
                                                <div class="col-md-6 col-lg-4 mb-4">
                                                    <div class="card h-100">
                                                        <div class="card-header">
                                                            <strong>{{ $video->titulo }}</strong>
                                                        </div>
                                                        <div class="card-body">
                                                            <video src="{{ asset('storage/' . $video->ruta_archivo) }}" controls width="100%" height="150"></video>
                                                            <p class="mt-2"><strong>Usuario:</strong> {{ $video->nombre }}</p>
                                                            <p><strong>Descripción:</strong> {{ $video->descripcion ?? 'N/A' }}</p>
                                                            <small class="text-muted">Enviado: {{ $video->created_at->format('d/m/Y H:i') }}</small>
                                                        </div>
                                                        <div class="card-footer d-flex justify-content-around">
                                                            <form action="{{ route('admin.media.approve', $video) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i> Aprobar</button>
                                                            </form>
                                                            <form action="{{ route('admin.media.destroy', $video) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este video?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Eliminar</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <hr>

                                    {{-- SECCIÓN DE IMÁGENES PENDIENTES --}}
                                    <h4><i class="fas fa-image text-warning"></i> Imágenes Pendientes</h4>
                                    @if ($imagenesPendientes->isEmpty())
                                        <p class="text-muted">No hay imágenes pendientes de aprobación.</p>
                                    @else
                                        <div class="row">
                                            @foreach ($imagenesPendientes as $imagen)
                                                <div class="col-md-6 col-lg-4 mb-4">
                                                    <div class="card h-100">
                                                        <div class="card-header">
                                                            <strong>{{ $imagen->titulo }}</strong>
                                                        </div>
                                                        <div class="card-body">
                                                            <img src="{{ asset('storage/' . $imagen->ruta_archivo) }}" alt="{{ $imagen->titulo }}" class="img-fluid" style="max-height: 150px; width: 100%; object-fit: cover;">
                                                            <p class="mt-2"><strong>Usuario:</strong> {{ $imagen->nombre }}</p>
                                                            <p><strong>Descripción:</strong> {{ $imagen->descripcion ?? 'N/A' }}</p>
                                                            <small class="text-muted">Enviado: {{ $imagen->created_at->format('d/m/Y H:i') }}</small>
                                                        </div>
                                                        <div class="card-footer d-flex justify-content-around">
                                                            <form action="{{ route('admin.media.approve', $imagen) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i> Aprobar</button>
                                                            </form>
                                                            <form action="{{ route('admin.media.destroy', $imagen) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta imagen?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Eliminar</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Contenido de Pestaña Aprobados -->
                            <div class="tab-pane fade" id="aprobados" role="tabpanel" aria-labelledby="aprobados-tab">
                                <div class="mt-3">
                                    {{-- SECCIÓN DE VIDEOS APROBADOS --}}
                                    <h4><i class="fas fa-video text-success"></i> Videos Aprobados</h4>
                                    @if ($videosAprobados->isEmpty())
                                        <p class="text-muted">No hay videos aprobados.</p>
                                    @else
                                        <div class="row">
                                            @foreach ($videosAprobados as $video)
                                                <div class="col-md-6 col-lg-4 mb-4">
                                                    <div class="card h-100">
                                                        <div class="card-header">
                                                            <strong>{{ $video->titulo }}</strong>
                                                        </div>
                                                        <div class="card-body">
                                                            <video src="{{ asset('storage/' . $video->ruta_archivo) }}" controls width="100%" height="150"></video>
                                                            <p class="mt-2"><strong>Usuario:</strong> {{ $video->nombre }}</p>
                                                            <small class="text-muted">Aprobado: {{ $video->updated_at->format('d/m/Y H:i') }}</small>
                                                        </div>
                                                        <div class="card-footer text-center">
                                                            <form action="{{ route('admin.media.destroy', $video) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este video?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Eliminar</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <hr>

                                    {{-- SECCIÓN DE IMÁGENES APROBADAS --}}
                                    <h4><i class="fas fa-image text-success"></i> Imágenes Aprobadas</h4>
                                    @if ($imagenesAprobadas->isEmpty())
                                        <p class="text-muted">No hay imágenes aprobadas.</p>
                                    @else
                                        <div class="row">
                                            @foreach ($imagenesAprobadas as $imagen)
                                                <div class="col-md-6 col-lg-4 mb-4">
                                                    <div class="card h-100">
                                                        <div class="card-header">
                                                            <strong>{{ $imagen->titulo }}</strong>
                                                        </div>
                                                        <div class="card-body">
                                                            <img src="{{ asset('storage/' . $imagen->ruta_archivo) }}" alt="{{ $imagen->titulo }}" class="img-fluid" style="max-height: 150px; width: 100%; object-fit: cover;">
                                                            <p class="mt-2"><strong>Usuario:</strong> {{ $imagen->nombre }}</p>
                                                            <small class="text-muted">Aprobado: {{ $imagen->updated_at->format('d/m/Y H:i') }}</small>
                                                        </div>
                                                        <div class="card-footer text-center">
                                                            <form action="{{ route('admin.media.destroy', $imagen) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta imagen?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Eliminar</button>
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

@push('scripts')
<script>
    // Script para recordar la pestaña activa después de recargar la página
    $(document).ready(function(){
        $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
            localStorage.setItem('activeTab', $(e.target).attr('href'));
        });
        var activeTab = localStorage.getItem('activeTab');
        if(activeTab){
            $('#myTab a[href="' + activeTab + '"]').tab('show');
        }
    });
</script>
@endpush