<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ strip_tags($item->titulo ?? 'Detalle') }} - Programa Gestión de la Biodiversidad (PGB)</title>

    <link rel="icon" href="{{ url('img/logo3.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/style-public-areas.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        .cochabamba-header {
            background-color: #0077c0;
            padding: 0.5rem 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .cochabamba-nav .nav-link {
            color: white !important;
            text-transform: uppercase;
            font-weight: 600;
            font-size: 0.95rem;
            margin-left: 15px;
            letter-spacing: 0.5px;
            transition: opacity 0.3s;
        }

        .cochabamba-nav .nav-link:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        /* Ajuste del logo en el header */
        .navbar-brand img {
            max-height: 55px;
            width: auto;
        }

        /* --- ESTILOS GENERALES PREVIOS --- */
        .img-detalle {
            max-height: 400px;
            max-width: 100%;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 2rem;
            width: 100%;
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

        .whatsapp-float {
            position: fixed;
            width: 100px;
            height: 40px;
            bottom: 70px;
            right: 20px;
            z-index: 1000;
        }

        .whatsapp-float img {
            width: 100%;
            height: auto;
        }

        /* Video hero responsivo */
        .hero-video video {
            height: 40vh;
            min-height: 200px;
            object-fit: cover;
            width: 100%;
        }

        /* Footer responsivo */
        .bg-gradient-to-r {
            background: linear-gradient(to right, #597ae7, #597ae7);
        }

        .hover\:bg-opacity-20:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .duration-300 {
            transition-duration: 300ms;
        }

        footer {
            overflow-x: hidden;
        }

        @media (max-width: 576px) {
            .footer-section img {
                height: 70px !important;
            }

            .social-icons a {
                width: 45px !important;
                height: 45px !important;
                padding: 0.75rem !important;
            }

            .modal-dialog {
                margin: 1rem;
                max-width: calc(100% - 2rem);
            }

            .modal-body iframe {
                height: 60vh !important;
            }

            .whatsapp-float {
                width: 50px;
                height: 35px;
                bottom: 15px;
                right: 15px;
            }
        }

        .card-title {
            font-size: 1rem;
        }

        .card-text small {
            font-size: 0.85rem;
        }

        body {
            overflow-x: hidden;
        }
    </style>
</head>

<body>

    <header class="cochabamba-header">
        <nav class="navbar navbar-expand-lg navbar-dark container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ url('img/logo3.png') }}" alt="Gobernación de Cochabamba">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto cochabamba-nav align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/areas-protegidas#inicio') }}">INICIO</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/areas-protegidas#areas') }}">ÁREAS PROTEGIDAS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/areas-protegidas#especies') }}">ESPECIES</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/areas-protegidas#noticias') }}">NOTICIAS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/areas-protegidas#conciencia') }}">CONCIENTIZACIÓN</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <section class="hero-video position-relative">
        <video autoplay muted loop playsinline class="w-100"
            style="height: 65vh; min-height: 200px; object-fit: cover;">
            <source src="{{ asset('video/173868607267a23e78515b0.mp4') }}" type="video/mp4">
            Tu navegador no soporta el video.
        </video>
    </section>

    <br>

    <main class="container py-4">
        <article class="mb-5">
            <h1 class="mb-3">{!! $item->titulo !!}</h1>

            @if ($item->subtitulo)
                <h4 class="text-muted mb-4">{{ $item->subtitulo }}</h4>
            @endif

            <p class="text-muted">
                <strong>Especie:</strong>
                @if (strtolower($item->tipo) === 'emblematica')
                    <span class="badge bg-success">Emblemática</span>
                @elseif(strtolower($item->tipo) === 'vulnerable')
                    <span class="badge bg-warning text-dark">Vulnerable</span>
                @else
                    <span class="badge bg-secondary">{{ ucfirst($item->tipo ?? 'No especificado') }}</span>
                @endif |
                <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($item->fecha_publicacion)->format('d/m/Y') }}
            </p>

            @if ($item->nombre_cientifico)
                <p class="text-muted">
                    <strong>Nombre Científico:</strong> <em>{{ $item->nombre_cientifico }}</em>
                </p>
            @endif

            <div class="mb-4 text-justify">
                {!! $item->descripcion !!}
            </div>

            @if ($item->habitat)
                <h4 class="mt-4">Hábitat</h4>
                <p>{!! $item->habitat !!}</p>
            @endif

            @if ($item->amenazas)
                <h4 class="mt-4">Amenazas</h4>
                <p>{!! $item->amenazas !!}</p>
            @endif

            @if ($item->distribucion)
                <h4 class="mt-4">Distribución</h4>
                <p>{!! $item->distribucion !!}</p>
            @endif

            @if ($item->imagenes->count() == 1)
                <div class="text-center">
                    <img src="{{ asset('storage/' . ($item->imagenes->first()->path ?? $item->imagenes->first()->url)) }}"
                        class="img-fluid img-detalle mx-auto d-block" alt="{{ $item->titulo }}">
                </div>
            @elseif($item->imagenes->count() > 1)
                <section class="mb-5">
                    <h2 class="mb-3">Imágenes</h2>
                    <div id="carouselImagenes" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($item->imagenes as $index => $imagen)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . ($imagen->path ?? $imagen->url)) }}"
                                        class="d-block w-100" style="max-height:820px; object-fit:cover;"
                                        alt="Imagen de {{ $item->titulo }}">
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

            @if ($item->media->isNotEmpty())
                <h2 class="mt-5 mb-3">Archivos Relacionados</h2>
                <div class="row g-4">
                    @foreach ($item->media as $key => $medio)
                        @php
                            $nombre_archivo = pathinfo($medio->archivo, PATHINFO_BASENAME);
                            $fecha_formateada = \Carbon\Carbon::parse($item->fecha_publicacion)->format('d/m/Y');
                            $modal_id = 'documentoModal' . ($medio->id ?? $key);
                        @endphp
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-primary">
                                        <i class="bi bi-file-earmark-pdf-fill me-2"></i> Documento
                                    </h5>
                                    <p class="card-text text-muted small mb-3">
                                        <span> Nombre: {{ $nombre_archivo }}</span>
                                        <span> Fecha de Publicación: {{ $fecha_formateada }}</span>
                                    </p>
                                    <div class="mt-auto">
                                        <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal"
                                            data-bs-target="#{{ $modal_id }}">
                                            <i class="bi bi-eye"></i> Visualizar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="{{ $modal_id }}" tabindex="-1"
                            aria-labelledby="{{ $modal_id }}Label" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title" id="{{ $modal_id }}Label">
                                            <i class="bi bi-file-earmark-pdf-fill me-2 text-danger"></i>
                                            {{ $nombre_archivo }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Cerrar"></button>
                                    </div>
                                    <div class="modal-body p-0">
                                        <div
                                            class="alert alert-info rounded-0 mb-0 d-flex justify-content-between align-items-center small p-2">
                                            <span>Ítem Relacionado: {{ strip_tags($item->titulo) }}</span>
                                            <span>Fecha de Publicación: {{ $fecha_formateada }}</span>
                                        </div>
                                        <iframe src="{{ asset('storage/' . $medio->archivo) }}"
                                            style="width: 100%; height: 75vh;" frameborder="0">
                                            Tu navegador no soporta la previsualización.
                                        </iframe>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="bi bi-x-circle"></i> Cerrar
                                        </button>
                                        <a href="{{ asset('storage/' . $medio->archivo) }}" class="btn btn-success"
                                            download="{{ $nombre_archivo }}">
                                            <i class="bi bi-download"></i> Descargar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($item->media->where('tipo', 'video')->isNotEmpty())
                <section class="mt-5">
                    <h2 class="mb-3">Videos</h2>
                    <div class="row g-3">
                        @foreach ($item->media->where('tipo', 'video') as $video)
                            <div class="col-md-6 col-12">
                                <div class="ratio ratio-16x9">
                                    <video controls class="rounded shadow-sm bg-dark w-100 h-100">
                                        <source src="{{ asset('storage/' . $video->ruta) }}" type="video/mp4">
                                        Tu navegador no soporta el elemento de video.
                                    </video>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </article>
    </main>

    <footer class="footer-custom text-white py-5 px-4">
        <div class="container">
            <div class="row align-items-center justify-content-center g-4">

                <div class="col-md-6 col-12 text-center text-md-start">
                    <h3 class="fw-bold mb-3">INFORMACIÓN DE CONTACTO</h3>
                    <ul class="list-unstyled small mb-2">
                        <li><i class="bi bi-envelope me-2"></i>Email:
                            gobernaciondecochabamba@gobernaciondecochabamba.bo</li>
                        <li><i class="bi bi-telephone me-2"></i>Teléfonos: + (591) 71701056</li>
                        <li><i class="bi bi-geo-alt me-2"></i><strong>Dirección:</strong><br>Av. Aroma N°: O-327 -
                            Plaza San Sebastián<br>Edificio del Órgano Ejecutivo</li>
                    </ul>
                </div>

                <div class="col-md-2 col-12 text-center">
                    <div class="d-flex justify-content-center">
                        <img src="{{ url('img/logo3.png') }}" alt="Programa Gestión de la Biodiversidad (PGB)"
                            height="70" class="rounded shadow-sm">
                    </div>
                </div>

                <div class="col-md-4 col-12 text-center text-md-end">
                    <h3 class="fw-bold mb-3">REDES SOCIALES</h3>
                    <div class="social-icons d-flex justify-content-center justify-content-md-end gap-3">
                        <a href="https://www.facebook.com/GobernacionDeCochabamba/" target="_blank"
                            class="text-white bg-white bg-opacity-10 rounded-circle p-3 d-flex align-items-center justify-content-center text-decoration-none hover:bg-opacity-20 transition-all duration-300"
                            style="width: 50px; height: 50px;">
                            <i class="bi bi-facebook fs-4"></i>
                        </a>
                        <a href="https://www.youtube.com/@gobernaciondecochabamba8326" target="_blank"
                            class="text-white bg-white bg-opacity-10 rounded-circle p-3 d-flex align-items-center justify-content-center text-decoration-none hover:bg-opacity-20 transition-all duration-300"
                            style="width: 50px; height: 50px;">
                            <i class="bi bi-youtube fs-4"></i>
                        </a>
                        <a href="https://www.tiktok.com/@gobernaciondecochabamba" target="_blank"
                            class="text-white bg-white bg-opacity-10 rounded-circle p-3 d-flex align-items-center justify-content-center text-decoration-none hover:bg-opacity-20 transition-all duration-300"
                            style="width: 50px; height: 50px;">
                            <i class="bi bi-tiktok fs-4"></i>
                        </a>
                    </div>
                </div>

            </div>

            <br>

            <div class="copyright text-center">
                <p class="mb-0 small">
                    Programa Gestión de la Biodiversidad (PGB) &copy; {{ date('Y') }} Todos los derechos reservados
                    | Gobierno Autónomo Departamental de Cochabamba.
                </p>
            </div>
        </div>
    </footer>

    @if (file_exists(public_path('img/wsp.png')))
        <a href="https://wa.me/59171701056" target="_blank" class="whatsapp-float">
            <img src="{{ asset('img/wsp.png') }}" alt="WhatsApp">
        </a>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

</body>

</html>
