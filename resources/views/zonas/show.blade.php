<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ strip_tags($especie->titulo ?? 'Detalle Especies') }} - Especies</title>

    <link rel="stylesheet" href="{{ asset('css/style-public-areas.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <link rel="icon" href="{{ url('img/logo3.png') }}" type="image/png">

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
            height: 60vh; /* Ajustado para que no sea tan alto */
            min-height: 350px;
            background-color: #000;
            overflow: hidden;
        }

        .hero-section img, .hero-section video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.8;
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
            display: flex;
            align-items: flex-end;
            justify-content: center;
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

        /* CARRUSEL Y VISOR */
        .carousel-container {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #dee2e6;
            background-color: #fff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
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

        /* FOOTER */
        .footer-custom {
            background-color: #0077c0; 
            color: white;
            border-top: 5px solid #005c99; 
            padding-top: 3rem;
            padding-bottom: 1rem;
            margin-top: auto;
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
        .whatsapp-float:hover { transform: scale(1.1); }
    </style>
</head>

<body>

    <header class="header-container">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ url('/') }}" class="text-decoration-none d-flex align-items-center">
                <img src="{{ url('img/logo3.png') }}" alt="Logo PGB" height="55" class="bg-white rounded p-1">
            </a>
            
            <nav class="d-none d-lg-block">
                <ul class="nav nav-pills gap-3 m-0">
                    <li class="nav-item"><a href="{{ url('/areas-protegidas#inicio') }}" class="nav-link">INICIO</a></li>
                    <li class="nav-item"><a href="{{ url('/areas-protegidas#areas') }}" class="nav-link">ÁREAS PROTEGIDAS</a></li>
                    <li class="nav-item"><a href="{{ url('/areas-protegidas#especies') }}" class="nav-link active">ESPECIES</a></li>
                    <li class="nav-item"><a href="{{ url('/areas-protegidas#noticias') }}" class="nav-link">NOTICIAS</a></li>
                    <li class="nav-item"><a href="{{ url('/areas-protegidas#conciencia') }}" class="nav-link">CONCIENTIZACIÓN</a></li>
                </ul>
            </nav>
        </div>
    </header>

    {{-- HERO SECTION CON IMAGEN PRINCIPAL --}}
    <section class="hero-section">
        @if($especie->imagenes->count() > 0)
            <img src="{{ asset('storage/' . $especie->imagenes->first()->url) }}" alt="{{ $especie->titulo }}">
        @else
            <img src="{{ asset('img/no-image.jpg') }}" alt="Sin imagen" style="filter: grayscale(1);">
        @endif
        
        <div class="hero-overlay w-100">
            <div class="container text-center">
                <h1 class="display-3 fw-bold text-uppercase mb-2">{{ $especie->titulo }}</h1>
                @if($especie->zona)
                    <p class="h4 mb-0 opacity-90"><i class="fas fa-map-marker-alt me-2"></i> {{ $especie->zona->nombre }}</p>
                @endif
            </div>
        </div>
    </section>

    <main class="container py-5">
        <div class="row g-5">
            
            {{-- COLUMNA IZQUIERDA: INFORMACIÓN Y GALERÍA --}}
            <div class="col-lg-8">
                
                <div class="d-flex flex-wrap gap-2 mb-4 align-items-center p-3 bg-white rounded shadow-sm border">
                    
                    <span class="badge badge-custom bg-secondary-soft">
                        <i class="bi bi-calendar-event me-2"></i>Publicado: {{ $especie->created_at->format('d/m/Y') }}
                    </span>

                    @if(isset($especie->tipo))
                        @php $tipoEspecie = strtolower($especie->tipo); @endphp
                        @if($tipoEspecie === 'emblematica')
                            <span class="badge badge-custom bg-emblematica">
                                <i class="bi bi-star-fill me-2"></i>Tipo: Emblemática
                            </span>
                        @elseif($tipoEspecie === 'vulnerable')
                            <span class="badge badge-custom bg-vulnerable">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>Tipo: Vulnerable
                            </span>
                        @else
                            <span class="badge badge-custom bg-secondary-soft border">
                                Tipo: {{ ucfirst($especie->tipo) }}
                            </span>
                        @endif
                    @endif
                </div>

                <div class="bg-white p-4 rounded-3 shadow-sm mb-5 border-top border-4" style="border-color: #0077c0 !important;">
                    <h4 class="mb-3 fw-bold text-dark border-bottom pb-2">Descripción</h4>
                    <div class="fs-6 text-secondary" style="line-height: 1.8; text-align: justify;">
                        {!! nl2br(e($especie->descripcion)) !!}
                    </div>
                </div>

                @if($especie->imagenes->count() > 0)
                    <h4 class="mb-4 fw-bold text-dark"><i class="bi bi-images me-2 text-primary"></i>Galería de Imágenes</h4>
                    <div class="carousel-container mb-5">
                        <div id="carouselEspecies" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($especie->imagenes as $key => $imagen)
                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $imagen->url) }}" alt="Imagen {{ $key + 1 }}">
                                    </div>
                                @endforeach
                            </div>
                            @if($especie->imagenes->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselEspecies" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselEspecies" data-bs-slide="next">
                                    <span class="carousel-control-next-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                @if($especie->media->count() > 0)
                    <div class="mb-5">
                        <h5 class="mb-3 fw-bold text-uppercase border-bottom pb-2 text-primary">Documentos Adjuntos</h5>
                        <div class="d-flex flex-column gap-3">
                            @foreach($especie->media as $medio)
                                @php
                                    $nombre_archivo = pathinfo($medio->archivo, PATHINFO_BASENAME);
                                    $rutaDoc = str_starts_with($medio->archivo, 'public/') ? str_replace('public/', '', $medio->archivo) : $medio->archivo;
                                    $urlDescarga = asset('storage/' . $rutaDoc);
                                    
                                    $icono = str_contains($medio->tipo, 'pdf') ? 'bi-file-earmark-pdf-fill text-danger' : 'bi-file-earmark-word-fill text-primary';
                                @endphp
                                <div class="file-card p-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center overflow-hidden me-2">
                                        <div class="fs-1 me-3">
                                            <i class="bi {{ $icono }}"></i>
                                        </div>
                                        <div class="text-truncate">
                                            <h6 class="mb-0 text-truncate text-dark fw-bold" title="{{ $nombre_archivo }}">{{ $nombre_archivo }}</h6>
                                            <small class="text-muted text-uppercase">{{ $medio->tipo }}</small>
                                        </div>
                                    </div>
                                    <a href="{{ $urlDescarga }}" target="_blank" class="btn btn-sm btn-light border" title="Descargar">
                                        <i class="bi bi-download text-success"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                {{-- Widget Volver --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <a href="{{ url('/areas-protegidas#especies') }}" class="btn btn-outline-primary w-100 fw-bold">
                            <i class="bi bi-arrow-left me-2"></i> Volver al listado
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer-custom">
        <div class="container">
            <div class="row g-4 justify-content-between">
                <div class="col-lg-4 col-md-6">
                    <h5 class="footer-title">Información de Contacto</h5>
                    <ul class="list-unstyled text-white opacity-90 small">
                        <li class="mb-2"><i class="bi bi-envelope-fill text-warning me-2"></i> gobernaciondecochabamba@gobernaciondecochabamba.bo</li>
                        <li class="mb-2"><i class="bi bi-telephone-fill text-warning me-2"></i> + (591) 71701056</li>
                        <li><i class="bi bi-geo-alt-fill text-warning me-2"></i> Av. Aroma N°: O-327<br>Plaza San Sebastián</li>
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
                Programa Gestión de la Biodiversidad (PGB) © {{ date('Y') }} Todos los derechos reservados.
            </div>
        </div>
        <a href="https://wa.me/59171701056" target="_blank" class="whatsapp-float">
            <img src="{{ asset('img/wsp.png') }}" alt="WhatsApp" width="55" class="shadow rounded-circle">
        </a>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>