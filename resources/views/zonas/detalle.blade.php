@extends('layouts.fullscreen')

@section('title', $zona->nombre)

@section('content')
<div class="zona-detail-view">
    <header class="banner-header">
        <img src="{{ asset('img/imagen1.jpg') }}" 
            alt="Banner de {{ $zona->nombre }}" 
            class="banner-image">
        <div class="banner-overlay">
            <div class="container">
                <h1 class="banner-title"><i class="fas fa-mountain mr-2"></i> {{ $zona->nombre }}</h1>
                <p class="banner-subtitle">{{ $zona->area->area ?? 'Área Protegida' }}</p>
                <a href="{{ route('zonas.index') }}" class="btn btn-outline-light mt-3">
                    <i class="fas fa-arrow-left mr-2"></i> Volver a Zonas
                </a>
            </div>
        </div>
    </header>

    <section class="info-section py-5">
        <div class="container">
            <h2 class="section-title">Datos Esenciales</h2>
            <div class="info-grid-three">

                <div class="info-card bg-white shadow-sm border-primary">
                    <div class="info-icon-container bg-primary">
                        <i class="fas fa-globe-americas"></i>
                    </div>
                    <div class="info-content">
                        <h3 class="info-label">Área Protegida</h3>
                        <p class="info-value">{{ $zona->area->area ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="info-card bg-white shadow-sm border-info">
                    <div class="info-icon-container bg-info">
                        <i class="fas fa-{{ $zona->tipo_coordenada == 'poligono' ? 'draw-polygon' : 'map-marker-alt' }}"></i>
                    </div>
                    <div class="info-content">
                        <h3 class="info-label">Tipo de Geometría</h3>
                        <p class="info-value">{{ ucfirst($zona->tipo_coordenada ?? 'N/A') }}</p>
                    </div>
                </div>

                <div class="info-card bg-white shadow-sm border-{{ $zona->estado ? 'success' : 'secondary' }}">
                    <div class="info-icon-container bg-{{ $zona->estado ? 'success' : 'secondary' }}">
                        <i class="fas fa-toggle-{{ $zona->estado ? 'on' : 'off' }}"></i>
                    </div>
                    <div class="info-content">
                        <h3 class="info-label">Estado</h3>
                        <span class="status-badge status-{{ $zona->estado ? 'active' : 'inactive' }}">
                            {{ $zona->estado ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <hr class="my-0">

    <section class="description-section py-5 bg-light-gray">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="section-title">Detalles y Ubicación</h2>
                </div>
            </div>
            
            <div class="description-wrapper shadow-lg">
                <div id="map-container" class="map-detail-display"></div>
                
                <div class="description-text-container p-4">
                    <h3 class="card-title text-primary mb-3"><i class="fas fa-book-open mr-2"></i> Descripción de la Zona</h3>
                    <p class="description-text">{{ $zona->descripcion ?? 'No hay descripción disponible para esta zona.' }}</p>
                </div>
            </div>
        </div>
    </section>

    @if(isset($zona->datos))
    <section class="additional-data py-5">
        <div class="container">
            <h2 class="section-title">Características Ecológicas y Demográficas</h2>
            
            <div class="data-grid">
                
                @includeWhen(isset($zona->datos->flora_fauna), 'components.data-card', [
                    'label' => 'Flora y Fauna',
                    'value' => $zona->datos->flora_fauna,
                    'icon' => 'fas fa-leaf',
                    'color' => 'success'
                ])
                @includeWhen(isset($zona->datos->extension), 'components.data-card', [
                    'label' => 'Extensión',
                    'value' => ($zona->datos->extension ?? 'N/A') . ' km²',
                    'icon' => 'fas fa-ruler-combined',
                    'color' => 'info'
                ])
                @includeWhen(isset($zona->datos->poblacion), 'components.data-card', [
                    'label' => 'Población Estimada',
                    'value' => $zona->datos->poblacion,
                    'icon' => 'fas fa-users',
                    'color' => 'warning'
                ])
                @includeWhen(isset($zona->datos->provincia), 'components.data-card', [
                    'label' => 'Provincia',
                    'value' => $zona->datos->provincia,
                    'icon' => 'fas fa-map-marker-alt',
                    'color' => 'danger'
                ])
            </div>
        </div>
    </section>
    @endif
    
    <hr class="my-0">

    @if(!empty($zona->imagenes) && count($zona->imagenes) > 0)
    <section class="gallery-section py-5 bg-white">
        <div class="container">
            <h2 class="section-title"><i class="fas fa-camera mr-2"></i> Galería de Imágenes</h2>
            
            <div id="imageCarousel" class="carousel slide carousel-fade" data-ride="carousel">
                <div class="carousel-inner image-carousel-inner">
                    @foreach($zona->imagenes as $index => $imagen)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <a href="{{ asset('storage/' . $imagen->url) }}" data-lightbox="gallery-{{ $zona }}" data-title="{{ $zona->nombre }} - Imagen {{ $index + 1 }}">
                            <img src="{{ asset('storage/' . $imagen->url) }}" class="d-block w-100 carousel-image" alt="Imagen {{ $index + 1 }}">
                        </a>
                    </div>
                    @endforeach
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
        </div>
    </section>
    @endif

    @if(!empty($zona->videos) && count($zona->videos) > 0)
    <section class="video-section py-5 bg-light-gray">
        <div class="container">
            <h2 class="section-title"><i class="fas fa-video mr-2"></i> Material Audiovisual</h2>
            <div id="videoCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner video-carousel-inner">
                    @foreach($zona->videos as $index => $video)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <div class="video-player-container mx-auto">
                            <video controls class="video-player">
                                <source src="{{ asset('storage/' . $video->url) }}" type="video/mp4">
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
    </section>
    @endif

    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-contact-section">
                <h3 class="footer-title">INFORMACIÓN DE CONTACTO</h3>
                <p class="footer-text"><i class="fas fa-map-marker-alt"></i> Av. Aroma N°: O-327 - Plaza San Sebastián Edificio del Organo Ejecutivo</p>
                <p class="footer-text"><i class="fas fa-phone-alt"></i> Teléfonos: 591 4 4500530</p>
                <p class="footer-text"><i class="fas fa-envelope"></i> gobernaciondecochabamba@gobernaciondecochabamba.bo</p>
            </div>

            <div class="footer-logo-section">
                <img src="{{ asset('img/logo3.png') }}" alt="Logo" class="footer-logo">
            </div>

            <div class="footer-social-section">
                <h3 class="footer-title">CANALES DE COMUNICACIÓN</h3>
                <div class="social-icons">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p class="copyright-text">© 2025, Gobierno Autónomo Departamental de Cochabamba.</p>
        </div>
        <a href="https://wa.me/59168774551" target="_blank" class="whatsapp-float">
            <img src="{{ asset('img/wsp.png') }}" alt="WhatsApp">
        </a>
    </footer>
</div>

@endsection

@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    :root {
        --primary-color: #3498db;
        --secondary-color: #2c3e50;
        --success-color: #27ae60;
        --info-color: #2980b9;
        --light-gray: #f8f9fa;
        --dark-text: #333;
        --light-text: #555;
    }

    /* Estilos generales */
    .zona-detail-view {
        font-family: 'Lato', sans-serif;
        color: var(--dark-text);
        line-height: 1.6;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    .bg-light-gray { background-color: var(--light-gray) !important; }
    .text-primary { color: var(--primary-color) !important; }
    .border-primary { border-left: 4px solid var(--primary-color) !important; }
    .border-info { border-left: 4px solid var(--info-color) !important; }
    .border-success { border-left: 4px solid var(--success-color) !important; }
    .border-secondary { border-left: 4px solid var(--secondary-color) !important; }

    /* --- Banner --- */
    .banner-header {
        position: relative;
        height: 400px;
        overflow: hidden;
    }

    .banner-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .banner-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .banner-title {
        color: white;
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.8);
    }
    
    .banner-subtitle {
        color: white;
        font-size: 1.5rem;
        font-weight: 400;
    }

    .btn-outline-light {
        border-color: white;
        color: white;
        background-color: transparent
    }
    .btn-outline-light:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }
    
    /* --- Títulos de Sección --- */
    .section-title {
        color: var(--secondary-color);
        margin-bottom: 40px;
        font-size: 2.2rem;
        font-weight: 700;
        position: relative;
        padding-bottom: 10px;
        text-align: center;
    }

    .section-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 3px;
        background: var(--primary-color);
        border-radius: 2px;
    }

    /* --- Información Básica (Grid) --- */
    .info-grid-three {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        max-width: 1100px;
        margin: 0 auto;
    }

    .info-card {
        display: flex;
        align-items: center;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
    }

    .info-icon-container {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        color: white;
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    
    .bg-primary { background: var(--primary-color); }
    .bg-success { background: var(--success-color); }
    .bg-info { background: var(--info-color); }
    .bg-secondary { background: #7f8c8d; }

    .info-label {
        font-size: 0.9rem;
        color: #7f8c8d;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .info-value {
        font-size: 1.3rem;
        color: var(--secondary-color);
        font-weight: 600;
        margin: 0;
    }

    .status-badge {
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        color: white;
        display: inline-block;
    }

    .status-active { background: var(--success-color); }
    .status-inactive { background: #7f8c8d; }
    
    /* --- Descripción y Mapa --- */
    .description-wrapper {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        min-height: 400px;
    }

    .map-detail-display {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0.2; /* Mapa tenue como fondo */
        z-index: 1;
    }

    .description-text-container {
        position: relative;
        z-index: 2;
        background-color: rgba(255, 255, 255, 0.95);
        border-radius: 12px;
        margin: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        padding: 30px;
    }

    .description-text {
        font-size: 1.1rem;
        line-height: 1.8;
        color: var(--light-text);
        text-align: justify;
    }
    
    /* --- Datos Adicionales (Grid/Data Cards) --- */
    .additional-data {
        background: #ecf0f1; /* Fondo claro para destacar */
    }
    
    .data-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        max-width: 1100px;
        margin: 0 auto;
    }

    .data-card {
        background: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        display: flex;
        align-items: flex-start;
    }

    .data-icon-container {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        color: white;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    
    .data-label {
        font-size: 0.9rem;
        color: #7f8c8d;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .data-value {
        font-size: 1.1rem;
        color: var(--secondary-color);
        margin: 0;
        font-weight: 500;
    }
    
    /* --- Galerías --- */
    .image-carousel-inner, .video-carousel-inner {
        max-width: 900px;
        margin: 0 auto;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .carousel-image {
        width: 100%;
        height: 500px;
        object-fit: cover;
    }
    
    .video-player-container {
        background: #000;
        border-radius: 10px;
        overflow: hidden;
        max-width: 800px;
        margin: 0 auto;
    }

    .video-player {
        width: 100%;
        height: 450px;
        display: block;
    }
    
    /* --- Footer (Mantenido) --- */
    .site-footer {
        background-color: #007dc3;
        color: white;
        padding: 40px 0 0;
        margin-top: 60px;
    }
    
    .footer-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 40px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    .footer-title {
        color: #f1c40f;
        font-size: 1.1rem;
        margin-bottom: 20px;
        font-weight: 700;
    }
    
    .footer-text {
        color: #ecf0f1;
        font-size: 0.95rem;
        margin-bottom: 10px;
        display: flex;
        align-items: flex-start;
    }
    
    .footer-text i {
        margin-right: 10px;
        margin-top: 4px; /* Alineación vertical */
        color: var(--primary-color);
    }
    
    .footer-logo {
        max-width: 150px;
        height: auto;
        display: block;
        margin: 0 auto;
    }
    
    .social-icons {
        display: flex;
        gap: 15px;
        justify-content: center;
    }
    
    .social-icon {
        color: white;
        font-size: 1.5rem;
        transition: color 0.3s;
    }
    
    .social-icon:hover {
        color: #f1c40f;
    }
    
    .footer-bottom {
        background-color: #006bb3;
        padding: 15px 0;
        margin-top: 20px;
        text-align: center;
    }
    
    .copyright-text {
        color: #bdc3c7;
        margin: 0;
        font-size: 0.9rem;
    }
    
    .whatsapp-float {
        position: fixed;
        width: 60px;
        height: 60px;
        bottom: 40px;
        right: 40px;
        background-color: #25d366;
        color: #7f8c8d transparent;
        border-radius: 50px;
        text-align: center;
        box-shadow: 2px 2px 3px #999;
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s;
    }
    
    .whatsapp-float:hover {
        transform: scale(1.1);
    }
    
    .whatsapp-float img {
        width: 35px;
        height: 35px;
    }

    /* --- Responsive --- */
    @media (max-width: 992px) {
        .banner-title { font-size: 3rem; }
        .info-grid-three { grid-template-columns: repeat(2, 1fr); }
        .carousel-image { height: 400px; }
        .video-player { height: 350px; }
        .footer-container { grid-template-columns: 1fr 1fr; }
        .footer-logo-section { grid-column: span 2; }
    }
    
    @media (max-width: 768px) {
        .banner-title { font-size: 2.5rem; }
        .info-grid-three { grid-template-columns: 1fr; }
        .description-text-container { margin: 10px; padding: 20px; }
        .data-grid { grid-template-columns: 1fr; }
        .carousel-image { height: 300px; }
        .video-player { height: 250px; }
        .footer-container { grid-template-columns: 1fr; text-align: center; }
        .footer-contact-section .footer-text { justify-content: center; }
        .footer-text i { margin-right: 5px; }
        .footer-contact-section, .footer-social-section { text-align: center; }
        .footer-social-section .social-icons { justify-content: center; }
    }
    
    @media (max-width: 576px) {
        .banner-header { height: 300px; }
        .banner-title { font-size: 2rem; }
        .carousel-image { height: 250px; }
        .video-player { height: 200px; }
        .whatsapp-float { width: 50px; height: 50px; bottom: 20px; right: 20px; }
        .whatsapp-float img { width: 30px; height: 30px; }
    }
</style>
@endpush

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Configuración de Lightbox (Mantenido)
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'albumLabel': 'Imagen %1 de %2',
        'fadeDuration': 300,
        'imageFadeDuration': 300
    });

    /**
     * Función para generar un color hexadecimal aleatorio.
     * @returns {string} Color hexadecimal (ej: '#a34cff').
     */
    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    $(document).ready(function () {
        $('#imageCarousel').carousel();
        $('#videoCarousel').carousel();

        // --- INICIALIZACIÓN DEL MAPA ---
        var map = L.map('map-container', {
            zoomControl: false,
            dragging: false,
            touchZoom: false,
            scrollWheelZoom: false,
            doubleClickZoom: false,
            center: [-17.3895, -66.1568], 
            zoom: 10
        });

        // Capa base de imágenes satelitales
        L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Esri, i-cubed, USDA, USGS, etc.',
        }).addTo(map);

        // Capa de límites y etiquetas
        L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Labels &copy; Esri'
        }).addTo(map);


        // Función auxiliar para expandir polígonos (Mantenido)
        function expandPolygon(latLngs, factor = 1.05) {
            const center = latLngs.reduce(
                (acc, [lat, lng]) => [acc[0] + lat, acc[1] + lng],
                [0, 0]
            ).map(sum => sum / latLngs.length);

            // Expande los puntos
            return latLngs.map(([lat, lng]) => {
                const latOffset = (lat - center[0]) * factor;
                const lngOffset = (lng - center[1]) * factor;
                return [center[0] + latOffset, center[1] + lngOffset];
            });
        }
        
        // Cargar GeoJSON y Coordenadas de Zona
        fetch('{{ asset("geo/cocha.json") }}')
            .then(response => response.json())
            .then(data => {
                var cochaLayer = L.geoJSON(data, {
                    style: {
                        color: '#3498db',
                        weight: 1,
                        opacity: 0.5,
                        fillColor: '#3498db',
                        fillOpacity: 0.2
                    }
                }).addTo(map);

                // Ajustar límites a Cochabamba inicialmente
                map.fitBounds(cochaLayer.getBounds());

                @if(!empty($zona->coordenadas))
                    var zonaCoords = @json(json_decode($zona->coordenadas));
                    var allZoneLayers = [];
                    var layerBounds = new L.LatLngBounds();

                    zonaCoords.forEach(function (item) {
                        if (item.tipo === 'poligono' && item.coordenadas && item.coordenadas.length > 0) {
                            
                            const randomColor = getRandomColor();
                            
                            var latLngsOriginal = item.coordenadas.map(coord => [coord.lat, coord.lng]);
                            
                            // Crea el polígono con el color aleatorio
                            var zonaPolygon = L.polygon(latLngsOriginal, {
                                color: randomColor,     
                                weight: 3,
                                opacity: 1,
                                fillColor: randomColor,    
                                fillOpacity: 0.4
                            }).addTo(map);
                            allZoneLayers.push(zonaPolygon);
                            layerBounds.extend(zonaPolygon.getBounds());
                            
                        } else if (item.tipo === 'marcador' && item.coordenadas) {

                            var markerColor = getRandomColor(); 
                            var marker = L.marker([item.coordenadas.lat, item.coordenadas.lng], {
                                icon: L.divIcon({className: 'custom-div-icon', html: '<i class="fas fa-map-marker-alt" style="color:' + markerColor + '; font-size: 24px;"></i>'})
                            }).addTo(map);
                            allZoneLayers.push(marker);
                            layerBounds.extend(marker.getLatLng());
                        }
                    });

                    // Centrar el mapa en la zona y Cochabamba
                    if (allZoneLayers.length > 0) {
                        map.fitBounds(layerBounds.pad(0.1));
                    }
                @endif
            })
            .catch(error => {
                console.error('Error cargando el GeoJSON:', error);
            });
    });
</script>
@endpush