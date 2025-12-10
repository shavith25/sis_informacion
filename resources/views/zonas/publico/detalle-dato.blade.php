<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $item->titulo ?? 'Detalle' }} - Detalle Datos</title>

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

        /* HERO VIDEO/IMAGEN */
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

        /* BADGES (ETIQUETAS) */
        .badge-custom {
            font-weight: 600;
            padding: 0.6em 1em;
            border-radius: 50px;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
        }
        
        .bg-emblematica { background-color: #28a745 !important; color: white; }
        .bg-vulnerable { background-color: #ffc107 !important; color: #212529; }
        .bg-info-soft { background-color: #e3f2fd; color: #0077c0; }
        .bg-secondary-soft { background-color: #e9ecef; color: #495057; }

        /* CARRUSEL */
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

        /* TARJETAS DE ARCHIVOS */
        .file-card {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .file-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-color: #0077c0;
        }

        /* --- FOOTER --- */
        .footer-custom {
            background-color: #0077c0; /* AZUL INSTITUCIONAL */
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
            border-bottom: 2px solid rgba(255,255,255,0.3);
            display: inline-block;
            padding-bottom: 0.5rem;
        }

        /* Ajuste de enlaces del footer */
        .footer-custom a {
            color: white;
            text-decoration: none;
            transition: opacity 0.3s;
        }
        .footer-custom a:hover {
            opacity: 0.8;
        }

        /* Iconos del footer en blanco/amarillo para contraste */
        .footer-icon {
            color: #ffc107;
            margin-right: 10px;
            font-size: 1.1rem;
        }

        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            transition: transform 0.3s;
        }

        .whatsapp-float:hover { transform: scale(1.1); }
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
                    <li class="nav-item"><a href="{{ url('/areas-protegidas#inicio') }}" class="nav-link">INICIO</a></li>
                    <li class="nav-item"><a href="{{ url('/areas-protegidas#areas') }}" class="nav-link">ÁREAS PROTEGIDAS</a></li>
                    <li class="nav-item"><a href="{{ url('/areas-protegidas#especies') }}" class="nav-link">ESPECIES</a></li>
                    <li class="nav-item"><a href="{{ url('/areas-protegidas#noticias') }}" class="nav-link">NOTICIAS</a></li>
                    <li class="nav-item"><a href="{{ url('/areas-protegidas#conciencia') }}" class="nav-link">CONCIENTIZACIÓN</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container py-5">
        <div class="row g-5">
            
            <div class="col-lg-8">
                
                <h1 class="display-5 fw-bold mb-2 text-uppercase text-primary">{{ $item->flora_fauna ?? 'Detalle' }}</h1>
                @if($item->extension)
                    <p class="h5 text-muted mb-4">Extensión: {{ $item->extension }}</p>
                @endif
                
                <div class="d-flex flex-wrap gap-2 mb-4 align-items-center p-3 bg-white rounded shadow-sm border">
                    
                    <span class="badge badge-custom bg-secondary-soft">
                        <i class="bi bi-calendar-event me-2"></i>{{ \Carbon\Carbon::parse($item->fecha_publicacion)->format('d/m/Y') }}
                    </span>

                    @if($item->poblacion)
                        <span class="badge badge-custom bg-info-soft">
                            <i class="bi bi-people-fill me-2"></i>{{ ucfirst($item->poblacion) }}
                        </span>
                    @endif
                    
                    @if($item->provincia)
                        <span class="badge badge-custom bg-secondary-soft">
                            <i class="bi bi-geo-alt-fill me-2"></i>{{ ucfirst($item->provincia) }}
                        </span>
                    @endif

                    @php $especieTexto = strtolower($item->especies_peligro ?? ''); @endphp
                    @if($especieTexto)
                        @if(str_contains($especieTexto, 'vulnerable') || str_contains($especieTexto, 'peligro'))
                            <span class="badge badge-custom bg-vulnerable">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ ucfirst($item->especies_peligro) }}
                            </span>
                        @elseif(str_contains($especieTexto, 'emblematica') || str_contains($especieTexto, 'embl'))
                            <span class="badge badge-custom bg-emblematica">
                                <i class="bi bi-star-fill me-2"></i>{{ ucfirst($item->especies_peligro) }}
                            </span>
                        @else
                            <span class="badge badge-custom bg-secondary-soft border">
                                {{ ucfirst($item->especies_peligro) }}
                            </span>
                        @endif
                    @endif
                </div>

                <div class="mb-5">
                    <h4 class="mb-3 fw-bold border-bottom pb-2">Descripción</h4>
                    <p class="fs-6 text-secondary" style="line-height: 1.8; text-align: justify;">
                        {{ $item->descripcion }}
                    </p>
                    @if($item->otros_datos)
                        <div class="alert alert-warning mt-4 d-flex align-items-center">
                            <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                            <div>
                                <strong>Datos Adicionales:</strong> {{ ucfirst($item->otros_datos) }}
                            </div>
                        </div>
                    @endif
                </div>

                @if($item->imagenes->count() > 0)
                    <h4 class="mb-4 fw-bold border-bottom pb-2">Galería Multimedia</h4>
                    <div class="carousel-container mb-5">
                        <div id="carouselDetalle" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($item->imagenes as $key => $imagen)
                                    @php
                                        $rutaImagen = str_replace('\\', '/', $imagen->url ?? $imagen->path);
                                        if(!str_starts_with($rutaImagen, 'storage/')) {
                                            $rutaImagen = 'storage/' . $rutaImagen;
                                        }
                                    @endphp
                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                        <img src="{{ asset($rutaImagen) }}" alt="Imagen {{ $key + 1 }}" class="d-block w-100">
                                    </div>
                                @endforeach
                            </div>
                            
                            @if($item->imagenes->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselDetalle" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselDetalle" data-bs-slide="next">
                                    <span class="carousel-control-next-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                
                @if($item->medios->where('tipo', 'documento')->count() > 0)
                    <div class="mb-5">
                        <h5 class="mb-3 fw-bold text-uppercase border-bottom pb-2 text-primary">Documentos</h5>
                        <div class="d-flex flex-column gap-3">
                            @foreach($item->medios->where('tipo', 'documento') as $medio)
                                @php
                                    $nombre_archivo = pathinfo($medio->path, PATHINFO_BASENAME);
                                    $modalId = "modalDoc" . $medio->id;
                                    
                                    $rutaDoc = str_replace('\\', '/', $medio->path);
                                    if(!str_starts_with($rutaDoc, 'storage/')) {
                                        $rutaDoc = 'storage/' . $rutaDoc;
                                    }
                                @endphp
                                <div class="file-card p-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center overflow-hidden me-2">
                                        <div class="text-danger fs-1 me-3">
                                            <i class="bi bi-file-earmark-pdf-fill"></i>
                                        </div>
                                        <div class="text-truncate">
                                            <h6 class="mb-0 text-truncate text-dark fw-bold" title="{{ $nombre_archivo }}">{{ $nombre_archivo }}</h6>
                                            <small class="text-muted">PDF - Descargar</small>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">
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
                                                <h6 class="modal-title text-truncate w-75 m-0"><i class="bi bi-file-pdf me-2"></i>{{ $nombre_archivo }}</h6>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-0" style="height: 85vh;">
                                                <iframe src="{{ asset($rutaDoc) }}" width="100%" height="100%" style="border:none;"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($item->medios->where('tipo', 'video')->count() > 0)
                    <div class="mb-4">
                        <h5 class="mb-3 fw-bold text-uppercase border-bottom pb-2 text-primary">Videos</h5>
                        @foreach($item->medios->where('tipo', 'video') as $video)
                            @php
                                $rutaVideo = str_replace('\\', '/', $video->path);
                                if(!str_starts_with($rutaVideo, 'storage/')) {
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
                            <i class="bi bi-envelope-fill footer-icon"></i>
                            gobernaciondecochabamba@gobernaciondecochabamba.bo
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-telephone-fill footer-icon"></i>
                            + (591) 71701056
                        </li>
                        <li class="d-flex align-items-start">
                            <i class="bi bi-geo-alt-fill footer-icon mt-1"></i>
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
                        <a href="https://www.facebook.com/GobernacionDeCochabamba" class="btn btn-outline-light rounded p-2" target="_blank"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="https://www.youtube.com/@gobernaciondecochabamba8326" class="btn btn-outline-light rounded p-2" target="_blank"><i class="bi bi-youtube fs-5"></i></a>
                        <a href="https://www.tiktok.com/@gobernaciondecochabamba" class="btn btn-outline-light rounded p-2" target="_blank"><i class="bi bi-tiktok fs-5"></i></a>
                    </div>
                </div>
            </div>
            
            <hr class="border-white mt-5 opacity-25">
            
            <div class="text-center small opacity-75">
                Programa Gestión de la Biodiversidad (PGB) © {{ date('Y') }} Todos los derechos reservados | Gobierno Autónomo Departamental de Cochabamba.
            </div>
        </div>

        <a href="https://wa.me/59171701056" target="_blank" class="whatsapp-float">
            <img src="{{ asset('img/wsp.png') }}" alt="WhatsApp" width="55" class="shadow rounded-circle">
        </a>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>