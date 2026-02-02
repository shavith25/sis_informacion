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
                        <li><a href="{{ url('/areas-protegidas#inicio') }}"><i class="bi bi-house-fill"></i> INICIO</a></li>
                        <li><a href="{{ url('/areas-protegidas#areas') }}"><i class="fa-solid fa-tree me-2"></i> ÁREAS PROTEGIDAS</a></li>
                        <li><a href="{{ url('/areas-protegidas#especies') }}"><i class="fa-solid fa-leaf"></i> ESPECIES</a></li>
                        <li><a href="{{ url('/areas-protegidas#noticias') }}"><i class="fa-solid fa-bullhorn"></i> NOTICIAS</a></li>
                        <li><a href="{{ url('/areas-protegidas#conciencia') }}"><i class="fa-solid fa-hand-holding-heart"></i> CONCIENTIZACIÓN</a></li>
                    </ul>
                </nav>
                </div>
            </div>
    </header>

    <br>

    <main class="container">
        <h1>{{ $item->nombre ?? $item->especies_peligro ?? $item->titulo }}</h1>
        <p>{{ $item->descripcion ?? $item->flora_fauna ?? $item->descripcion }}</p>

    @if($item->imagenes->count() == 1)
    <div class="text-center">
        <img src="{{ asset('storage/' . ($item->imagenes->first()->path ?? $item->imagenes->first()->url)) }}" 
            class="img-fluid img-detalle mx-auto d-block">

    </div>
        @elseif($item->imagenes->count() > 1)
            <div id="carouselItemImages" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($item->imagenes as $key => $imagen)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . ($item->imagenes->first()->path ?? $item->imagenes->first()->url)) }}" 
                            class="img-fluid img-detalle mx-auto d-block">

                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselItemImages" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselItemImages" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
            </div>
        @endif

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
                <p>© Programa Gestión de la Biodiversidad (PGB) &copy; {{ date('Y') }} Todos los derechos reservados | Gobierno Autónomo Departamental de Cochabamba .</p>
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
    document.addEventListener('DOMContentLoaded', function () {
    const cochabambaBounds = L.latLngBounds(
        L.latLng(-18.50, -67.50), // suroeste (frontera con Oruro y Potosí)
        L.latLng(-16.00, -64.00)  // noreste (frontera con Beni y Santa Cruz)
    );

    map = L.map('map', {

        minZoom: 9             
    }).setView([-17.3895, -66.1568], 13); 
    L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '&copy; Esri, i-cubed, USDA, USGS, etc.',
    }).addTo(map);

    L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
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
});
</script>

</html>
