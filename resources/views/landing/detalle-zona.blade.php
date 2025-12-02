@extends('layouts.landing')

@section('content')
<section class="hero-section" style="background-image: url('{{ asset('img/imagen1.jpg') }}');">
    <div class="hero-overlay"></div>
    <img src="{{ asset('img/imagen1.png') }}" alt="Logo Áreas Protegidas" class="hero-logo">
</section>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <section class="basic-info-section mb-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header text-white" style="background-color: #2c3e50;">
                        <h3 class="mb-0"><i class="fas fa-info-circle mr-2"></i> Información Básica</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="icon-container bg-primary-light rounded-circle p-3 mr-3">
                                        <i class="fas fa-map-marked-alt fa-lg text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="info-label mb-1">Área Protegida</h6>
                                        <p class="info-value mb-0 font-weight-bold">{{ $zona->area->area ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="icon-container bg-primary-light rounded-circle p-3 mr-3">
                                        <i class="fas fa-layer-group fa-lg text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="info-label mb-1">Tipo de Coordenadas</h6>
                                        <p class="info-value mb-0 font-weight-bold">{{ $zona->tipo_coordenada ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="icon-container bg-primary-light rounded-circle p-3 mr-3">
                                        <i class="fas fa-toggle-{{ $zona->estado ? 'on text-success' : 'off text-secondary' }} fa-lg"></i>
                                    </div>
                                    <div>
                                        <h6 class="info-label mb-1">Estado</h6>
                                        <span class="badge badge-{{ $zona->estado ? 'success' : 'secondary' }} px-3 py-1">
                                            {{ $zona->estado ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="description-section mb-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="section-title mb-4">Descripción</h2>
                        <div class="description-content">
                            <p class="lead text-justify">{{ $zona->descripcion }}</p>
                        </div>
                    </div>
                </div>
            </section>

            @if($zona->datos)
            <section class="important-data-section mb-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header text-white" style="background-color: #2c3e50;">
                        <h3 class="mb-0"><i class="fas fa-chart-bar mr-2"></i> Datos Importantes</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="data-card p-4 rounded-lg border">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-container bg-info-light rounded-circle p-2 mr-3">
                                            <i class="fas fa-ruler-combined fa-lg text-info"></i>
                                        </div>
                                        <h5 class="mb-0">Extensión</h5>
                                    </div>
                                    <p class="data-value mb-0 font-weight-bold text-dark">{{ $zona->datos->extension ?? 'N/A' }} km²</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="data-card p-4 rounded-lg border">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-container bg-info-light rounded-circle p-2 mr-3">
                                            <i class="fas fa-users fa-lg text-info"></i>
                                        </div>
                                        <h5 class="mb-0">Población</h5>
                                    </div>
                                    <p class="data-value mb-0 font-weight-bold text-dark">{{ $zona->datos->poblacion ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="data-card p-4 rounded-lg border">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-container bg-info-light rounded-circle p-2 mr-3">
                                            <i class="fas fa-leaf fa-lg text-info"></i>
                                        </div>
                                        <h5 class="mb-0">Flora y Fauna</h5>
                                    </div>
                                    <p class="data-value mb-0 font-weight-bold text-dark">{{ $zona->datos->flora_fauna ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="data-card p-4 rounded-lg border">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-container bg-info-light rounded-circle p-2 mr-3">
                                            <i class="fas fa-map-marker-alt fa-lg text-info"></i>
                                        </div>
                                        <h5 class="mb-0">Provincia</h5>
                                    </div>
                                    <p class="data-value mb-0 font-weight-bold text-dark">{{ $zona->datos->provincia ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            @endif

            @if($zona->imagenes && count($zona->imagenes) > 0)
            <section class="gallery-section mb-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header text-white" style="background-color: #2c3e50;">
                        <h3 class="mb-0"><i class="fas fa-images mr-2"></i> Galería de Imágenes</h3>
                    </div>
                    <div class="card-body">
                        <div id="imageCarousel" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                                @if(count($zona->imagenes) > 0)
                                    @foreach($zona->imagenes as $index => $imagen)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <a href="{{ asset('storage/' . $imagen) }}" data-lightbox="gallery" data-title="{{ $zona->nombre }}">
                                            <img src="{{ asset('storage/' . $imagen) }}" class="d-block w-100 rounded" alt="Imagen {{ $index + 1 }}" style="height: 400px; object-fit: cover;">
                                        </a>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="carousel-item active">
                                        <a href="{{ asset('img/logo.png') }}" data-lightbox="gallery" data-title="{{ $zona->nombre }}">
                                            <img src="{{ asset('img/logo.png') }}" class="d-block w-100 rounded" alt="Imagen por defecto" style="height: 400px; object-fit: contain; background: #f8f9fa;">
                                        </a>
                                    </div>
                                @endif
                            </div>
                            @if(count($zona->imagenes) > 1)
                            <a class="carousel-control-prev" href="#imageCarousel" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Anterior</span>
                            </a>
                            <a class="carousel-control-next" href="#imageCarousel" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Siguiente</span>
                            </a>
                            @endif
                        </div>
                        <div class="row mt-3">
                            @if(count($zona->imagenes) > 0)
                                @foreach($zona->imagenes as $index => $imagen)
                                <div class="col-4 col-md-2 mb-2">
                                    <a href="#" data-target="#imageCarousel" data-slide-to="{{ $index }}" class="d-block">
                                        <img src="{{ asset('storage/' . $imagen) }}" class="img-fluid rounded" alt="Miniatura {{ $index + 1 }}" style="height: 60px; width: 100%; object-fit: cover;">
                                    </a>
                                </div>
                                @endforeach
                            @else
                                <div class="col-4 col-md-2 mb-2">
                                    <a href="#" data-target="#imageCarousel" data-slide-to="0" class="d-block">
                                        <img src="{{ asset('img/logo.png') }}" class="img-fluid rounded" alt="Imagen por defecto" style="height: 60px; width: 100%; object-fit: cover;">
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
            @endif

            @if($zona->videos && count($zona->videos) > 0)
            <section class="videos-section mb-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header text-white" style="background-color: #2c3e50;">
                        <h3 class="mb-0"><i class="fas fa-video mr-2"></i> Videos</h3>
                    </div>
                    <div class="card-body">
                        <div id="videoCarousel" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($zona->videos as $index => $video)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <div class="ratio ratio-16x9">
                                        <video controls class="rounded">
                                            <source src="{{ asset('storage/' . $video) }}" type="video/mp4">
                                            Tu navegador no soporta el elemento de video.
                                        </video>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @if(count($zona->videos) > 1)
                            <a class="carousel-control-prev" href="#videoCarousel" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Anterior</span>
                            </a>
                            <a class="carousel-control-next" href="#videoCarousel" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Siguiente</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="position-sticky" style="top: 20px;">
                <div class="card shadow-sm mb-4">
                    <div class="card-header text-white" style="background-color: #2c3e50;">
                        <h5 class="mb-0"><i class="fas fa-map-marked-alt mr-2"></i> Ubicación</h5>
                    </div>
                    <div class="card-body p-0">
                        <div id="map" style="height: 300px; width: 100%;"></div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary btn-block" id="descargarMapa">
                            <i class="fas fa-download mr-2"></i> Descargar Mapa
                        </button>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header text-white" style="background-color: #2c3e50;">
                        <h5 class="mb-0"><i class="fas fa-info-circle mr-2"></i> Información Adicional</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-calendar-alt mr-2 text-primary"></i> Actualización</span>
                                <span class="badge bg-light text-dark">{{ $zona->updated_at->format('d/m/Y') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-id-card mr-2 text-primary"></i> Código</span>
                                <span class="badge bg-light text-dark">AP-{{ str_pad($zona->id, 3, '0', STR_PAD_LEFT) }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .section-title {
        font-size: 1.75rem;
        font-weight: 600;
        color: #2c3e50;
        position: relative;
        padding-bottom: 10px;
        margin-bottom: 1.5rem;
    }

    .section-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 3px;
        background: #2c3e50;
    }

    .icon-container {
        transition: all 0.3s ease;
    }

    .bg-custom-header {
        background-color: #2c3e50 !important;
    }

    .bg-primary-light {
        background-color: rgba(52, 152, 219, 0.1);
    }

    .bg-info-light {
        background-color: rgba(23, 162, 184, 0.1);
    }

    .info-label {
        font-size: 0.85rem;
        color: #7f8c8d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 1.1rem;
        color: #2c3e50;
    }

    .description-content {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #555;
    }

    .description-content p:first-letter {
        float: left;
        font-size: 3.5rem;
        line-height: 0.8;
        margin: 0.15em 0.1em 0 0;
        color: #2c3e50;
        font-weight: bold;
    }

    .data-card {
        transition: all 0.3s ease;
        height: 100%;
    }

    .data-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .data-value {
        font-size: 1.2rem;
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 5%;
        background: rgba(0, 0, 0, 0.3);
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        width: 2rem;
        height: 2rem;
    }

    @media (max-width: 768px) {
        .description-content p:first-letter {
            font-size: 2.5rem;
        }

        .section-title {
            font-size: 1.5rem;
        }

        .position-sticky {
            position: relative !important;
            top: 0 !important;
        }
    }
</style>
@endpush

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const map = L.map('map').setView([-17.3895, -66.1568], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    const coordenadas = @json($coordenadas);
    const bounds = L.latLngBounds();

    coordenadas.forEach(item => {
        if (item.tipo === 'poligono' && item.coordenadas && item.coordenadas.length > 0) {
            const latlngs = item.coordenadas.map(coord => [coord.lat, coord.lng]);

            const polygon = L.polygon(latlngs, {
                color: '#e74c3c',
                weight: 2,
                opacity: 1,
                fillColor: '#e74c3c',
                fillOpacity: 0.5
            }).addTo(map)
            .bindPopup(`<b>{{ $zona->nombre }}</b><br>Área protegida`);

            bounds.extend(polygon.getBounds());
        }
    });

    if (Object.keys(bounds._southWest).length > 0) {
        map.fitBounds(bounds, {padding: [50, 50]});
    }

    document.getElementById('descargarMapa').addEventListener('click', function(e) {
        e.preventDefault();
        html2canvas(document.querySelector('#map')).then(canvas => {
            const link = document.createElement('a');
            link.download = 'mapa-' + @json($zona->nombre).toLowerCase().replace(/ /g, '-') + '.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    });

    $('.carousel').carousel({
        interval: 5000,
        pause: 'hover'
    });
});
</script>
@endpush
