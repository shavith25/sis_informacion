<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $item->titulo ?? 'Detalle' }} - PGB</title>

    <link rel="stylesheet" href="{{ asset('css/style-public-areas.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #333;
        }

        /* --- HEADER INSTITUCIONAL --- */
        .header-container {
            background-color: #0077c0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 3px solid #005c99;
            padding: 10px 0;
        }

        .nav-link {
            font-weight: 700;
            color: white !important;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            transition: opacity 0.3s;
        }

        .nav-link:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        /* HERO SECTION (Banner) */
        .hero-section {
            position: relative;
            height: 70vh;
            min-height: 400px;
            background-color: #000;
            overflow: hidden;
        }

        .hero-section video,
        .hero-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.9;
        }

        .hero-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.9), transparent);
            padding: 5rem 0 2rem;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
        }

        /* BADGES (ETIQUETAS) */
        .badge-custom {
            font-weight: 600;
            padding: 0.6em 1em;
            border-radius: 50px;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
        }

        .bg-active {
            background-color: #28a745 !important;
            color: white;
        }

        .bg-inactive {
            background-color: #6c757d !important;
            color: white;
        }

        .bg-info-soft {
            background-color: #e3f2fd;
            color: #0077c0;
            border: 1px solid #b6d4fe;
        }

        .bg-secondary-soft {
            background-color: #e9ecef;
            color: #495057;
            border: 1px solid #dee2e6;
        }

        .bg-emblematica {
            background-color: #28a745 !important;
            color: white;
        }

        .bg-vulnerable {
            background-color: #ffc107 !important;
            color: #212529;
        }

        /* CARRUSEL Y VISOR */
        .carousel-container {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #dee2e6;
            background-color: #fff;
        }

        .carousel-item {
            height: 500px;
            background-color: #f8f9fa;
            position: relative;
        }

        .carousel-item img {
            height: 100%;
            width: 100%;
            object-fit: contain;
            object-position: center;
        }

        /* TARJETAS DE ARCHIVOS Y ESPECIES */
        .file-card {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .file-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-color: #0077c0;
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        /* FOOTER AZUL */
        .footer-custom {
            background-color: #0077c0;
            color: white;
            border-top: 5px solid #005c99;
            padding-top: 3rem;
            padding-bottom: 1rem;
        }

        .footer-title {
            color: #fff;
            font-weight: 800;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            text-transform: uppercase;
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
            display: inline-block;
            padding-bottom: 0.5rem;
        }

        .footer-custom a {
            color: white;
            text-decoration: none;
            transition: opacity 0.3s;
        }

        .footer-custom a:hover {
            opacity: 0.8;
        }

        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            transition: transform 0.3s;
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
        }

        /* Mapa */
        #map {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid #dee2e6;
        }
    </style>
</head>

<body>

    <header class="header-container">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ url('/') }}" class="logo">
                <img src="{{ url('img/logo3.png') }}" alt="Logo PGB" height="55">
            </a>

            <nav class="d-none d-lg-block">
                <ul class="nav nav-pills gap-3 m-0">
                    <li class="nav-item"><a href="{{ url('/areas-protegidas#inicio') }}" class="nav-link">INICIO</a>
                    </li>
                    <li class="nav-item"><a href="{{ url('/areas-protegidas#areas') }}" class="nav-link">ÁREAS
                            PROTEGIDAS</a></li>
                    <li class="nav-item"><a href="{{ url('/areas-protegidas#especies') }}" class="nav-link">ESPECIES</a>
                    </li>
                    <li class="nav-item"><a href="{{ url('/areas-protegidas#noticias') }}" class="nav-link">NOTICIAS</a>
                    </li>
                    <li class="nav-item"><a href="{{ url('/areas-protegidas#conciencia') }}"
                            class="nav-link">CONCIENTIZACIÓN</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero-section">
        <video autoplay muted loop playsinline poster="{{ asset('img/poster-placeholder.jpg') }}">
            <source src="{{ asset('video/173868607267a23e78515b0.mp4') }}" type="video/mp4">
            Tu navegador no soporta el video.
        </video>
        <div class="hero-overlay"></div>
    </section>

    <br>

    <div class="container">
        <h1 class="display-4 fw-bold mb-2 text-uppercase">{{ $item->nombre ?? 'Zona Protegida' }}</h1>
        @if ($item->area)
            <p class="h4 mb-0 opacity-90">{{ $item->area->area }}</p>
        @endif
    </div>

    <main class="container py-5">
        <div class="row g-5">

            <div class="col-lg-8">

                <div class="d-flex flex-wrap gap-2 mb-4 align-items-center p-3 bg-white rounded shadow-sm border">

                    <span class="badge badge-custom bg-secondary-soft">
                        <i
                            class="bi bi-calendar-event me-2"></i>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                    </span>

                    <span class="badge badge-custom {{ $item->estado ? 'bg-active' : 'bg-inactive' }}">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ $item->estado ? 'Activo' : 'Inactivo' }}
                    </span>

                    @if ($item->ultimoHistorial)
                        <span class="badge badge-custom bg-info-soft">
                            <i
                                class="bi bi-layers-fill me-2"></i>{{ ucfirst($item->ultimoHistorial->tipo_coordenada) }}
                        </span>
                    @endif
                </div>

                <div class="bg-white p-4 rounded-3 shadow-sm mb-5 border-top border-4"
                    style="border-color: #0077c0 !important;">
                    <h4 class="mb-3 fw-bold text-dark border-bottom pb-2">Descripción General</h4>
                    <div class="fs-6 text-secondary" style="line-height: 1.8; text-align: justify;">
                        {!! $item->descripcion !!}
                    </div>
                </div>

                <div class="mb-5">
                    <h4 class="mb-3 fw-bold text-dark"><i class="bi bi-map-fill me-2 text-primary"></i>Ubicación
                        Geográfica</h4>
                    <div id="map" style="height: 500px; width: 100%;"></div>
                </div>

                @if ($item->imagenes->count() > 0)
                    <h4 class="mb-4 fw-bold text-dark"><i class="bi bi-images me-2 text-primary"></i>Galería de Imágenes
                    </h4>
                    <div class="carousel-container mb-5">
                        @if ($item->imagenes->count() == 1)
                            @php
                                $img = $item->imagenes->first();
                                $ruta = str_replace('\\', '/', $img->url ?? $img->path);
                                if (!str_starts_with($ruta, 'storage/')) {
                                    $ruta = 'storage/' . $ruta;
                                }
                            @endphp
                            <div class="carousel-item active">
                                <img src="{{ asset($ruta) }}" alt="Imagen zona">
                            </div>
                        @else
                            <div id="carouselZonas" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach ($item->imagenes as $key => $imagen)
                                        @php
                                            $rutaImagen = str_replace('\\', '/', $imagen->url ?? $imagen->path);
                                            if (!str_starts_with($rutaImagen, 'storage/')) {
                                                $rutaImagen = 'storage/' . $rutaImagen;
                                            }
                                        @endphp
                                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                            <img src="{{ asset($rutaImagen) }}" alt="Imagen {{ $key + 1 }}">
                                        </div>
                                    @endforeach
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselZonas"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon bg-dark rounded-circle p-2"
                                        aria-hidden="true"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselZonas"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon bg-dark rounded-circle p-2"
                                        aria-hidden="true"></span>
                                </button>
                            </div>
                        @endif
                    </div>
                @endif

                @if (isset($item->especies) && $item->especies->count() > 0)
                    <h4 class="mb-4 fw-bold text-dark border-bottom pb-2">Especies en esta Zona</h4>
                    <div class="row g-4">
                        @foreach ($item->especies as $especie)
                            <div class="col-md-6">
                                <div class="card h-100 shadow-sm border-0">
                                    @if ($especie->imagenes->count())
                                        @php
                                            $imgEsp = $especie->imagenes->first();
                                            $rutaEsp = str_replace('\\', '/', $imgEsp->url ?? $imgEsp->path);
                                            if (!str_starts_with($rutaEsp, 'storage/')) {
                                                $rutaEsp = 'storage/' . $rutaEsp;
                                            }
                                        @endphp
                                        <div style="height: 200px; overflow: hidden; background-color:#f8f9fa;">
                                            <img src="{{ asset($rutaEsp) }}" class="card-img-top w-100 h-100"
                                                style="object-fit: cover;" alt="{{ $especie->titulo }}">
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold text-uppercase">{!! $especie->titulo !!}</h5>
                                        <p class="card-text text-muted small">
                                            {{ Str::limit($especie->descripcion, 80) }}</p>

                                        <div class="mb-3">
                                            @php $tipoEsp = strtolower($especie->tipo); @endphp
                                            @if ($tipoEsp === 'emblematica')
                                                <span class="badge bg-emblematica">Emblemática</span>
                                            @elseif($tipoEsp === 'vulnerable')
                                                <span class="badge bg-vulnerable">Vulnerable</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($especie->tipo) }}</span>
                                            @endif
                                        </div>

                                        <a href="{{ route('detalle.show', ['tipo' => 'especie', 'id' => Illuminate\Support\Facades\Crypt::encrypt($especie->id)]) }}"
                                            class="btn btn-outline-primary btn-sm rounded-pill w-100">
                                            Ver Detalle <i class="bi bi-arrow-right-short"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                @php
                    $medios = collect();
                    if (isset($item->media)) {
                        $medios = $medios->merge($item->media);
                    }
                    if (isset($item->datos->medios)) {
                        $medios = $medios->merge($item->datos->medios);
                    }
                    $medios = $medios->unique('id');
                @endphp

                @if ($medios->where('tipo', 'documento')->count() > 0)
                    <div class="mb-5">
                        <h5 class="mb-3 fw-bold text-uppercase border-bottom pb-2 text-primary">Documentos Adjuntos
                        </h5>
                        <div class="d-flex flex-column gap-3">
                            @foreach ($medios->where('tipo', 'documento') as $medio)
                                @php
                                    $path = $medio->path ?? $medio->archivo; // Puede llamarse path o archivo según el modelo
                                    $nombre_archivo = pathinfo($path, PATHINFO_BASENAME);
                                    $modalId = 'modalDoc' . $medio->id;

                                    $rutaDoc = str_replace('\\', '/', $path);
                                    if (!str_starts_with($rutaDoc, 'storage/')) {
                                        $rutaDoc = 'storage/' . $rutaDoc;
                                    }
                                @endphp
                                <div class="file-card p-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center overflow-hidden me-2">
                                        <div class="text-danger fs-1 me-3">
                                            <i class="bi bi-file-earmark-pdf-fill"></i>
                                        </div>
                                        <div class="text-truncate">
                                            <h6 class="mb-0 text-truncate text-dark fw-bold"
                                                title="{{ $nombre_archivo }}">{{ $nombre_archivo }}</h6>
                                            <small class="text-muted">PDF - Descargar</small>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#{{ $modalId }}">
                                                    <i class="bi bi-eye me-2 text-primary"></i>Ver
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ asset($rutaDoc) }}" download>
                                                    <i class="bi bi-download me-2 text-success"></i>Descargar
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-centered">
                                        <div class="modal-content h-100 border-0 rounded-3 overflow-hidden">
                                            <div class="modal-header bg-primary text-white py-2">
                                                <h6 class="modal-title text-truncate w-75 m-0"><i
                                                        class="bi bi-file-pdf me-2"></i>{{ $nombre_archivo }}</h6>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-0" style="height: 85vh;">
                                                <iframe src="{{ asset($rutaDoc) }}" width="100%" height="100%"
                                                    style="border:none;"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (isset($item->videos) && $item->videos->count() > 0)
                    <div class="mb-4">
                        <h5 class="mb-3 fw-bold text-uppercase border-bottom pb-2 text-primary">Videos</h5>
                        @foreach ($item->videos as $video)
                            @php
                                $rutaVideo = str_replace('\\', '/', $video->url ?? $video->path);
                                if (!str_starts_with($rutaVideo, 'storage/')) {
                                    $rutaVideo = 'storage/' . $rutaVideo;
                                }
                            @endphp
                            <div class="mb-3 rounded-3 overflow-hidden shadow-sm border border-light">
                                <video controls class="w-100 d-block bg-black" style="max-height: 200px;">
                                    <source src="{{ asset($rutaVideo) }}" type="video/mp4">
                                    Tu navegador no soporta el video.
                                </video>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </main>

    <footer class="footer-custom">
        <div class="container">
            <div class="row g-4 justify-content-between">

                <div class="col-lg-4 col-md-6">
                    <h5 class="footer-title">Información de Contacto</h5>
                    <ul class="list-unstyled text-white opacity-90 small">
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-envelope-fill text-warning me-2"></i>
                            gobernaciondecochabamba@gobernaciondecochabamba.bo
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-telephone-fill text-warning me-2"></i>
                            + (591) 71701056
                        </li>
                        <li class="d-flex align-items-start">
                            <i class="bi bi-geo-alt-fill text-warning me-2 mt-1"></i>
                            <span>Av. Aroma N°: O-327<br>Plaza San Sebastián<br>Edificio del Órgano Ejecutivo</span>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-12 text-center my-auto">
                    <img src="{{ url('img/logo3.png') }}" alt="Logo Footer" height="80" class="mb-3">
                </div>

                <div class="col-lg-3 col-md-6 text-md-end">
                    <h5 class="footer-title">Redes Sociales</h5>
                    <div class="d-flex justify-content-md-end gap-3">
                        <a href="https://www.facebook.com/GobernacionDeCochabamba"
                            class="btn btn-outline-light rounded p-2" target="_blank"><i
                                class="bi bi-facebook fs-5"></i></a>
                        <a href="https://www.youtube.com/@gobernaciondecochabamba8326"
                            class="btn btn-outline-light rounded p-2" target="_blank"><i
                                class="bi bi-youtube fs-5"></i></a>
                        <a href="https://www.tiktok.com/@gobernaciondecochabamba"
                            class="btn btn-outline-light rounded p-2" target="_blank"><i
                                class="bi bi-tiktok fs-5"></i></a>
                    </div>
                </div>
            </div>

            <hr class="border-white mt-5 opacity-25">

            <div class="text-center small opacity-75">
                Programa Gestión de la Biodiversidad (PGB) © {{ date('Y') }} Todos los derechos reservados |
                Gobierno Autónomo Departamental de Cochabamba.
            </div>
        </div>

        <a href="https://wa.me/59171701056" target="_blank" class="whatsapp-float">
            <img src="{{ asset('img/wsp.png') }}" alt="WhatsApp" width="55" class="shadow rounded-circle">
        </a>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        let map;
        let item = @json($item);

        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar el mapa solo si hay contenedor
            if (document.getElementById('map')) {
                const cochabambaBounds = L.latLngBounds(
                    L.latLng(-18.50, -67.50), // suroeste
                    L.latLng(-16.00, -64.00) // noreste
                );

                map = L.map('map', {
                    minZoom: 9
                }).setView([-17.3895, -66.1568], 13);

                L.tileLayer(
                    'https://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                        attribution: '&copy; Esri',
                    }).addTo(map);

                L.tileLayer(
                    'https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
                        attribution: 'Labels &copy; Esri'
                    }).addTo(map);

                const drawnItems = new L.FeatureGroup();
                map.addLayer(drawnItems);

                if (item.ultimo_historial && item.ultimo_historial.coordenadas) {
                    // Verificar si coordenadas es string (JSON) o ya es array/objeto
                    let coords = item.ultimo_historial.coordenadas;
                    // En Laravel 10+ con casting 'array' ya viene como objeto, pero por seguridad:
                    if (typeof coords === 'string') {
                        try {
                            coords = JSON.parse(coords);
                        } catch (e) {}
                    }

                    if (Array.isArray(coords)) {
                        coords.forEach(poli => {
                            if (poli.tipo === 'poligono' && Array.isArray(poli.coordenadas)) {
                                const latlngs = poli.coordenadas.map(coord => [coord.lat, coord.lng]);
                                const polygon = L.polygon(latlngs, {
                                    color: '#00FF00',
                                    weight: 3,
                                    opacity: 1,
                                    fillColor: '#32CD32',
                                    fillOpacity: 0.2
                                }).addTo(drawnItems);
                                polygon.bindPopup("Área protegida: " + (item.nombre || 'Zona'));
                            }
                        });

                        if (drawnItems.getLayers().length > 0) {
                            map.fitBounds(drawnItems.getBounds());
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>
