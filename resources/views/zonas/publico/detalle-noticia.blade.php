<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programa Gestión de la Biodiversidad (PGB)</title>
    
    <link rel="stylesheet" href="{{ asset('css/style-public-areas.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

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
        
        /* Ajuste para el video */
        .hero-video video {
            width: 100%;
            height: 60vh;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <header class="header-container">
        <div class="header-content">
            <div class="logo">
                <img src="{{ url('img/logo3.png') }}" alt="Programa Gestión de la Biodiversidad (PGB)" />
            </div>
            <div style="display: flex; align-items: center">
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
        <video autoplay muted loop playsinline>
            <source src="{{ asset('video/173868607267a23e78515b0.mp4') }}" type="video/mp4">
            Tu navegador no soporta el video.
        </video>
    </section>
    <br>

    <main class="container py-5">
        <article class="mb-5">
            
            <h1 class="mb-3">{!! $item->titulo !!}</h1>
            
            @if($item->subtitulo)
                <h4 class="text-muted mb-4">{{ $item->subtitulo }}</h4>
            @endif

            <p class="text-muted">
                <strong>Autor:</strong> {{ $item->autor ?? 'No especificado' }} |
                <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($item->fecha_publicacion)->format('d/m/Y') }}
            </p>

            <div class="mb-4 text-justify">
                {!! $item->descripcion !!} </div>

            @if($item->imagenes->count() == 1)
                <div class="text-center">
                    <img src="{{ asset('storage/' . ($item->imagenes->first()->path ?? $item->imagenes->first()->ruta)) }}"
                        class="img-fluid img-detalle mx-auto d-block" alt="{{ $item->titulo }}">
                </div>
            @elseif($item->imagenes->count() > 1)
                <div id="carouselNoticias" class="carousel slide mb-4" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($item->imagenes as $key => $imagen)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . ($imagen->path ?? $imagen->ruta)) }}"
                                    class="d-block w-100" style="max-height:500px; object-fit:contain;" 
                                    alt="Imagen {{ $key + 1 }}">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselNoticias" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselNoticias" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>
            @endif

        </article>
    </main>

    <footer>
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>INFORMACIÓN DE CONTACTO</h3>
                    <ul>
                        <li>Email: gobernaciondecochabamba@gobernaciondecochabamba.bo</li>
                        <li>Teléfonos: + (591) 71701056</li>
                        <p>Dirección:</p>
                        <li>Av. Aroma N°: O-327 - Plaza San Sebastián Edificio del Organo Ejecutivo</li>
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
                <p>Programa Gestión de la Biodiversidad (PGB) &copy; {{ date('Y') }} Todos los derechos reservados | Gobierno Autónomo Departamental de Cochabamba.</p>
            </div>
        </div>

        <a href="https://wa.me/59171701056" target="_blank" class="whatsapp-float">
            <img src="{{ asset('img/wsp.png') }}" alt="WhatsApp">
        </a>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</body>
</html>