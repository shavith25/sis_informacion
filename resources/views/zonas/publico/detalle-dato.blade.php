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
            
            <h1 class="mb-3">{{ $item->flora_fauna }}</h1>
            @if($item->extension)
                <h4 class="text-muted mb-4">{{ $item->extension }}</h4>
            @endif

            
            <p class="text-muted">
                <strong>Poblacion:</strong> {{ ucfirst($item->poblacion ?? 'No especificado') }} |
                <strong>Provincia:</strong> {{ ucfirst($item->provincia ?? 'No especificado') }} |
                <strong>Especie en peligro:</strong> {{ ucfirst($item->especies_peligro ?? 'No especificado') }} |
                <strong>Otros:</strong> {{ ucfirst($item->otros_datos ?? 'No especificado') }} |
                <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($item->fecha_publicacion)->format('d/m/Y') }}
            </p>

            <div class="mb-4">
                <p>{{ $item->descripcion }}</p>
            </div>

            
            @if($item->imagenes->count() == 1)
            <div class="text-center mb-4">
                @php
                    $imagenObj = $item->imagenes->first();

                    $ruta = $imagenObj->path;

                    $ruta = str_replace('\\', '/', $ruta);

                    $altText = $imagenObj->descripcion ?? 'Imagen del detalle';
                @endphp

                <img src="{{ asset('storage/' . $ruta) }}" 
                    class="img-fluid rounded shadow-sm" 
                    style="max-height: 500px; width: auto; object-fit: contain;"
                    alt="{{ $altText }}">
            </div>
            @elseif($item->imagenes->count() > 1)
                <div id="carouselNoticias" class="carousel slide mb-4" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($item->imagenes as $key => $imagen)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $imagen->url) }}">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselNoticias" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselNoticias" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>
            @endif
                @if($item->medios->count() > 0)
                    <div class="mt-4">
                        <h5>Archivos relacionados</h5>

                        <div class="row">
                            @foreach($item->medios as $medio)
                                @if($medio->tipo === 'video')
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <video controls class="w-100 rounded">
                                            <source src="{{ asset('storage/' . $medio->path) }}" type="video/mp4">
                                            Tu navegador no soporta el video.
                                        </video>
                                    </div>

                                    <h2>Visualizar el Documento Subido y luego Descargar </h2>

                                @elseif($medio->tipo === 'documento')
                                    @php
                                        $nombre_archivo = pathinfo($medio->path, PATHINFO_BASENAME);
                                        $fecha_formateada = \Carbon\Carbon::parse($item->fecha_publicacion)->format('d/m/Y');
                                    @endphp
                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                        <div class="card h-100 shadow-sm border-0">
                                            <div class="card-body d-flex flex-column">
                                                <h5 class="card-title text-primary">
                                                    <i class="bi bi-file-earmark-pdf-fill me-2"></i> Documento
                                                </h5>
                                                <p class="card-text text-muted small mb-3">
                                                    Nombre: {{ $nombre_archivo }}
                                                </p>
                                                <div class="mt-auto">
                                                    <button type="button" class="btn btn-primary w-100" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#documentoModal{{ $medio->id }}">
                                                        <i class="bi bi-eye"></i> Visualizar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="documentoModal{{ $medio->id }}" tabindex="-1" aria-labelledby="documentoModalLabel{{ $medio->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header bg-light">
                                                    <h5 class="modal-title" id="documentoModalLabel{{ $medio->id }}">
                                                        <i class="bi bi-file-earmark-pdf-fill me-2 text-danger"></i> {{ $nombre_archivo }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                </div>
                                                <div class="modal-body p-0">
                                                    <div class="alert alert-info rounded-0 mb-0 d-flex justify-content-between align-items-center small p-2">
                                                        <span>Ítem Relacionado: {{ $item->flora_fauna }}</span>
                                                        <span>Fecha de Publicación: {{ $fecha_formateada }}</span>
                                                    </div>

                                                    <iframe src="{{ asset('storage/' . $medio->path) }}" 
                                                        style="width: 100%; height: 75vh;" 
                                                        frameborder="0">
                                                        Tu navegador no soporta la previsualización.
                                                    </iframe>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="bi bi-x-circle"></i> Cerrar Visualizador
                                                    </button>
                                                    <a href="{{ asset('storage/' . $medio->path) }}" class="btn btn-success" download="{{ $nombre_archivo }}">
                                                        <i class="bi bi-download"></i> Descargar Documento
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
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
                <p>Programa Gestión de la Biodiversidad (PGB) &copy; {{ date('Y') }} Todos los derechos reservados | Gobierno Autónomo Departamental de Cochabamba .</p>
            </div>
        </div>

        <a href="https://wa.me/59171701056" target="_blank" class="whatsapp-float">
            <img src="{{ asset('img/wsp.png') }}" alt="WhatsApp">
        </a>
    </footer>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</body>
</html>
