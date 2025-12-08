<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programa Gestión de la Biodiversidad (PGB)</title>
    <link rel="stylesheet" href="{{ asset('css/style-public-areas.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
</head>
<style>
    .img-detalle {
        max-height: 400px;
        max-width: 800px;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 2rem;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        width: 40px;
        height: 40px;
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 5%;
    }
</style>

<body>
    <header class="header-container">
        <div class="header-content">
            <div class="logo">
                <img src="{{ url('img/logo3.png') }}" alt="Programa Gestión de la Biodiversidad (PGB)" />

            </div>
            <div style="display: flex;align-items: center">
                <nav class="nav-container">
                    <ul>
                        <li><a href="{{ url('/areas-protegidas#inicio') }}">INICIO</a></li>
                        <li><a href="{{ url('/areas-protegidas#areas') }}">ÁREAS PROTEGIDAS</a></li>
                        <li><a href="{{ url('/areas-protegidas#especies') }}">ESPECIES</a></li>
                        <li><a href="{{ url('/areas-protegidas#noticias') }}">NOTICIAS</a></li>
                        <li><a href="{{ url('/areas-protegidas#conciencia') }}">CONCIENTIZACIÓN</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <section class="hero-video position-relative">
        <video autoplay muted loop playsinline class="w-100" style="height: 60vh; object-fit: cover;">
            <source src="{{ asset('video/173868607267a23e78515b0.mp4') }}" type="video/mp4">
            Tu navegador no soporta el video.
        </video>
    </section>
    <br>

    <main class="container py-5">

        <article class="mb-5">
            <h1 class="mb-3">{{ $item->nombre }}</h1>

            <p class="text-muted">
                <strong>Área:</strong> {{ $item->area->area ?? 'No especificado' }} |
                <strong>Estado:</strong> <span
                    class="badge bg-{{ $item->estado ? 'success' : 'secondary' }}">{{ $item->estado ? 'Activo' : 'Inactivo' }}</span>
                |
                <strong>Fecha de registro:</strong> {{ $item->created_at->format('d/m/Y') }}
            </p>

            <p>{{ $item->descripcion ?? 'No hay descripción disponible.' }}</p>

            @if ($item->ultimoHistorial)
                <div class="mt-3">
                    <small class="text-muted">
                        Última actualización: {{ $item->ultimoHistorial->created_at->format('d/m/Y H:i') }} |
                        Tipo: {{ ucfirst($item->ultimoHistorial->tipo_coordenada) }} |
                        Elementos: {{ count($item->ultimoHistorial->coordenadas) }}
                    </small>
                </div>
            @endif
        </article>
        <section class="mb-5">
            <h2 class="mb-3">Mapa de la Zona</h2>
            <div id="map" style="height: 500px; width: 100%; border-radius: 8px;"></div>
        </section>

        @if (isset($item->imagenes) && count($item->imagenes) > 0)
            <section class="mb-5">
                <h2 class="mb-3">Imágenes</h2>
                <div id="carouselImagenes" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($item->imagenes as $index => $imagen)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $imagen->url) }}" class="d-block w-100"
                                    style="max-height:820px; object-fit:cover;" alt="Imagen de {{ $item->nombre }}">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselImagenes"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselImagenes"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>
            </section>
        @endif
        @if (isset($item->videos) && count($item->videos) > 0)
            <section class="mb-5">
                <h2 class="mb-3">Videos</h2>
                <div class="row g-3">
                    @foreach ($item->videos as $video)
                        <div class="col-md-6">
                            <div class="ratio ratio-16x9">
                                <video controls class="rounded shadow-sm bg-dark w-100 h-100">
                                    <source src="{{ asset('storage/' . $video->url) }}" type="video/mp4">
                                    Tu navegador no soporta el elemento de video.
                                </video>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="mb-5">
            <h2 class="mb-5">Especies</h2>
            <div class="card-grid">
                @forelse($item->especies as $especie)
                    <div class="card h-100 shadow-sm border-0">
                        @if ($especie->imagenes->count())
                            <div class="card-img-top-container"
                                style="height: 220px; overflow: hidden; background-color: #f8f9fa;">
                                <img src="{{ asset('storage/' . $especie->imagenes->first()->url) }}"
                                    alt="{{ $especie->titulo }}" class="w-100 h-100" style="object-fit: cover;">
                            </div>
                        @endif
                        <div class="card-content p-3">
                            <h3 class="h5 font-weight-bold text-uppercase">{!! $especie->titulo !!}</h3>
                            <p class="text-muted small">
                                {{ \Illuminate\Support\Str::limit($especie->descripcion, 100, '...') }}</p>

                            <div class="col-md-3 text-md-left mb-2 mb-md-0">
                                @if (strtolower($especie->tipo) === 'emblematica')
                                    <span class="badge rounded-pill text-white px-3 py-2 fs-6"
                                        style="background-color: #28a745; font-size: 0.9rem !important;">
                                        Tipo: Emblemática
                                    </span>
                                @elseif(strtolower($especie->tipo) === 'vulnerable')
                                    <span class="badge rounded-pill text-dark px-3 py-2 fs-6"
                                        style="background-color: #ffc107; font-size: 0.9rem !important;">
                                        Tipo: Vulnerable
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-secondary text-white px-3 py-2 fs-6"
                                        style="font-size: 1rem !important;">
                                        Tipo: {{ ucfirst($especie->tipo) }}
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('detalle.show', ['tipo' => 'especie', 'id' => $especie]) }}"
                                class="read-more text-decoration-none fw-bold text-primary">
                                Saber más →
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info w-100 text-center">
                        No hay especies registradas para esta zona.
                    </div>
                @endforelse
            </div>
        </section>

    </main>

    <footer>
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>INFORMACIÓN DE CONTACTO</h3>
                    <ul>
                        <li>Email: gobernaciondecochabamba@gobernaciondecochabamba.bo</li>
                        <li>Telefonos: + (591) 71701056</li>
                        <p>Dirección:</p>
                        <li>Av. Aroma N°: O-327 - Plaza San Sebastián Edicifio del Organo Ejecutivo</li>
                    </ul>
                </div>
                <div class="footer-section">
                    <div class="logo">
                        <img src="{{ url('img/logo3.png') }}" alt="Programa Gestión de la Biodiversidad (PGB)" />

                    </div>
                </div>
                <div class="footer-section newsletter">
                    <h3>REDES SOCIALES</h3>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/GobernacionDeCochabamba/" target="_blank">
                            <i class="bi bi-facebook"></i>
                        </a> <br>
                        <a href="https://www.youtube.com/@gobernaciondecochabamba8326" target="_blank">
                            <i class="bi bi-youtube"></i>
                        </a> <br>
                        <a href="https://www.tiktok.com/@gobernaciondecochabamba" target="_blank">
                            <i class="bi bi-tiktok"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="copyright">
                <p>Programa Gestión de la Biodiversidad (PGB) &copy; {{ date('Y') }} Todos los derechos reservados
                    | Gobierno Autónomo Departamental de Cochabamba .</p>
            </div>
        </div>

        <a href="https://wa.me/59171701056" target="_blank" class="whatsapp-float">
            <img src="{{ asset('img/wsp.png') }}" alt="WhatsApp">
        </a>
    </footer>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</body>

<script>
    let map;
    let item = @json($item);
    document.addEventListener('DOMContentLoaded', function() {
        const cochabambaBounds = L.latLngBounds(
            L.latLng(-18.50, -67.50), // suroeste (frontera con Oruro y Potosí)
            L.latLng(-16.00, -64.00) // noreste (frontera con Beni y Santa Cruz)
        );

        map = L.map('map', {

            minZoom: 9
        }).setView([-17.3895, -66.1568], 13);
        L.tileLayer(
            'https://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: '&copy; Esri, i-cubed, USDA, USGS, etc.',
            }).addTo(map);

        L.tileLayer(
            'https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Labels &copy; Esri'
            }).addTo(map);

        function estaEnCochabamba(lat, lng) {
            return lat >= cochabambaBounds.getSouth() &&
                lat <= cochabambaBounds.getNorth() &&
                lng >= cochabambaBounds.getWest() &&
                lng <= cochabambaBounds.getEast();
        }
        const drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);
        if (item.ultimo_historial && item.ultimo_historial.coordenadas) {
            item.ultimo_historial.coordenadas.forEach(poli => {
                if (poli.tipo === 'poligono' && Array.isArray(poli.coordenadas)) {
                    const latlngs = poli.coordenadas.map(coord => [coord.lat, coord.lng]);

                    const polygon = L.polygon(latlngs, {
                        color: '#00FF00',
                        weight: 3,
                        opacity: 1,
                        fillColor: '#32CD32',
                        fillOpacity: 0.2
                    }).addTo(drawnItems);

                    polygon.bindPopup("Área protegida");
                }
            });
            if (drawnItems.getLayers().length > 0) {
                map.fitBounds(drawnItems.getBounds());
            }
        }
    });
</script>

</html>
