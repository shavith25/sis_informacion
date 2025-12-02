@extends('layouts.fullscreen')

@section('content')
<div class="fullscreen-detail">
    <!-- Banner Superior -->
    <div class="banner-container">
        <img src="{{ asset('img/imagen1.jpg') }}" alt="Banner de Gobierno Autónomo Departamental de Cochabamba" class="banner-image">
        <div class="banner-overlay">
            <h1 class="banner-title">Áreas protegidas de Cochabamba</h1>
        </div>
    </div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"/>
    <div id="map"></div>
    <div id="zona-modal" class="zona-modal">
        <div style="display: flex; justify-content: space-between; align-items: center; background:#007dc3;color:white; padding: 10px;">
            <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i> Información General
                    </h5>
            
            <button class="close-modal" onclick="cerrarModal()">×</button>  
        </div>
        <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted mb-2"><i class="fas fa-tag me-2"></i> Nombre</h6>
                        <p class="fw-bold" id="zona-nombre"></p>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted mb-2"><i class="fas fa-layer-group me-2"></i> Área</h6>
                        <p class="fw-bold" id="area-nombre"></p>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted mb-2"><i class="fas fa-info-circle me-2"></i> Descripción</h6>
                    
                        <p class="fw-bold" id="zona-descripcion"></p>
                        
                        </p>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted mb-2"><i class="fas fa-power-off me-2"></i> Estado</h6>
                    <span id="area-estado" class="badge bg-primary  text-white p-2">
                    
                        </span>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted mb-2"><i class="fas fa-calendar-alt me-2"></i> Fecha de Registro</h6>
                        <p class="fw-bold" id="area-registro"></p>
                    </div>
                </div>
        <p id="zona-descripcion"></p>
        <h5 style="background-color: #007dc3; color:white; padding: 10px">Galería de Imágenes</h5>
        <div id="zona-imagenes" class="zona-media"></div>
        <h5 style="background-color: #007dc3; color:white; padding: 10px">Galería de Videos</h5>
        <div id="zona-videos" class="zona-media"></div>
    </div>
<<<<<<< HEAD
<<<<<<< Updated upstream
    <div id="chatbot-modal" class="chatbot-modal">
        <div class="chatbot-header">
            <h5>Asistente Virtual</h5>
            <button onclick="cerrarChatbot()">×</button>
        </div>
        <div class="chatbot-body" id="chatbot-body">
            <div class="chatbot-message">Hola 👋 ¿En qué puedo ayudarte?</div>
        </div>
        <div class="chatbot-footer">
            <input type="text" id="chatbot-input" placeholder="Escribe tu mensaje..." />
            <button onclick="enviarMensaje()">Enviar</button>
        </div>
    </div>
=======
>>>>>>> Stashed changes
=======
>>>>>>> parent of 3208fda (Revert "Merge branch 'main' into main-v1")

    <!-- Footer -->

    <footer class="site-footer">
        <div class="footer-container">
            <!-- Sección de Contacto (Izquierda) -->
            <div class="footer-contact-section">
                <h3 class="footer-title">INFORMACIÓN DE CONTACTO</h3>
                <p class="footer-text"><i class="fas fa-map-marker-alt"></i> Av. Aroma N°: O-327 - Plaza San Sebastián Edificio del Organo Ejecutivo</p>
<<<<<<< Updated upstream
                <p class="footer-text"><i class="fas fa-phone-alt"></i> Teléfonos: +591 71701056</p>
=======
                <p class="footer-text"><i class="fas fa-phone-alt"></i> Teléfonos: +591 76870673</p>
>>>>>>> Stashed changes
                <p class="footer-text"><i class="fas fa-envelope"></i>gobernaciondecochabamba@gobernaciondecochabamba.bo</p>
            </div>

            <!-- Logo (Centro) -->
            <div class="footer-logo-section">
                <img src="{{ asset('img/logo3.png') }}" alt="Logo" class="footer-logo">
            </div>

            <!-- Canales de Comunicación (Derecha) -->
            <div class="footer-social-section">
<<<<<<< HEAD
<<<<<<< Updated upstream
                <h3 class="footer-title">CANALES DE COMUNICACIÓN</h3>
=======
                <h3 class="footer-title">REDES SOCIALES</h3>
>>>>>>> Stashed changes
=======
                <h3 class="footer-title">REDES SOCIALES</h3>
>>>>>>> parent of 3208fda (Revert "Merge branch 'main' into main-v1")
                <div class="social-icons">
                    <a href="https://www.facebook.com/GobernacionDeCochabamba/" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.tiktok.com/@gobernaciondecochabamba" class="social-icon"><i class="fab fa-tiktok"></i></a>
                    <a href="https://www.youtube.com/@gobernaciondecochabamba8326" class="social-icon"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p class="copyright-text">© 2025, Gobierno Autónomo Departamental de Cochabamba.</p>
        </div>
<<<<<<< HEAD
<<<<<<< Updated upstream
        <a onclick="abrirChatbot()" class="chatbot-float">
            <img src="{{ asset('img/chat-bot.jpg') }}" alt="Chatbot">
        </a>
=======
>>>>>>> parent of 3208fda (Revert "Merge branch 'main' into main-v1")

        <a href="https://wa.me/59171701056" target="_blank" class="whatsapp-float">
=======

        <a href="https://wa.me/59168774551" target="_blank" class="whatsapp-float">
>>>>>>> Stashed changes
            <img src="{{ asset('img/wsp.png') }}" alt="WhatsApp">
        </a>
    </footer>
</div>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
let map;
document.addEventListener('DOMContentLoaded', function () {
    const cochabambaBounds = L.latLngBounds(
        L.latLng(-18.50, -67.50), 
        L.latLng(-16.00, -64.00)  
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
<<<<<<< HEAD
<<<<<<< Updated upstream
=======

>>>>>>> Stashed changes
=======

>>>>>>> parent of 3208fda (Revert "Merge branch 'main' into main-v1")
<script>
    const zonas = @json($zonas);

    document.addEventListener('DOMContentLoaded', function () {
        console.log(zonas,'zonasPastezonasPastezonasPaste')
        zonas.forEach(zona => {
            if (zona.historial && zona.historial.length > 0) {

                const coords = zona.historial[0].coordenadas;
                let coordsPlano = [];
                    if (Array.isArray(coords[0])) {
                            coordsPlano = coords[0];
                    }  else if (coords[0]?.tipo === "poligono" && Array.isArray(coords[0].coordenadas)) {
                        coordsPlano = coords[0].coordenadas.map(p => [p.lat, p.lng]);
                    }
                L.polygon(coordsPlano, { color: 'green' }).addTo(map)
                    .bindPopup(zona.nombre);
            }
        });
    });
</script>
<script>
    const zonasPaste = @json($zonas);
    console.log(zonasPaste,'zonasPastezonasPastezonasPaste')
    document.addEventListener('DOMContentLoaded', function () {
        zonasPaste.forEach(zona => {
            if (zona.historial && zona.historial.length > 0) {
                const coords = zona.historial[0].coordenadas;
                let coordsPlano = [];
                    if (Array.isArray(coords[0])) {
                            coordsPlano = coords[0];
                    }  else if (coords[0]?.tipo === "poligono" && Array.isArray(coords[0].coordenadas)) {
                        coordsPlano = coords[0].coordenadas.map(p => [p.lat, p.lng]);
                    }
                const polygon = L.polygon(coordsPlano, { color: 'green' }).addTo(map);

                polygon.on('click', () => {
                    mostrarModal(zona);
                });
            }
        });
    });

    function mostrarModal(zona) {
        document.getElementById('zona-nombre').textContent = zona.nombre;
        document.getElementById('zona-descripcion').textContent = zona.descripcion;
        document.getElementById('area-nombre').textContent = zona.area.area || 'No especificado';
        document.getElementById('area-estado').textContent = zona.estado || false ? 'Activo' : 'Inactivo';
        
        const fecha = new Date(zona.created_at);
        const opciones = { day: '2-digit', month: '2-digit', year: 'numeric', 
                        hour: '2-digit', minute: '2-digit' };
        document.getElementById('area-registro').textContent = 
            zona.created_at ? fecha.toLocaleString('es-BO', opciones) : 'No especificado';
        console.log(zona,'adsfasdfas')
        const imagenesContainer = document.getElementById('zona-imagenes');
        imagenesContainer.innerHTML = '';
        zona.imagenes.forEach(img => {
            const imgTag = document.createElement('img');
            imgTag.src = 'storage/' + img.url;
            imagenesContainer.appendChild(imgTag);
        });

        const videosContainer = document.getElementById('zona-videos');
        videosContainer.innerHTML = '';
        zona.videos.forEach(video => {
            const videoTag = document.createElement('video');
            videoTag.src = `/storage/${video.url}`;
            videoTag.controls = true;
            videoTag.autoplay = false;
            videoTag.muted = false;
            videoTag.style.width = '300px';
            videoTag.style.height = '180px';
            videoTag.style.marginBottom = '10px';
            videoTag.style.borderRadius = '8px'; 
            videosContainer.appendChild(videoTag);

        });

        document.getElementById('zona-modal').classList.add('active');
    }

    function cerrarModal() {
        document.getElementById('zona-modal').classList.remove('active');
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
<script>
const swiper = new Swiper('.swiper-container', {
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
    },
    breakpoints: {
    768: { slidesPerView: 2 },
    1024: { slidesPerView: 3 }
    }
});
</script>
<<<<<<< HEAD
<<<<<<< Updated upstream
<script>
    function abrirChatbot() {
        document.getElementById('chatbot-modal').style.display = 'flex';
    }

    function cerrarChatbot() {
        document.getElementById('chatbot-modal').style.display = 'none';
    }
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('chatbot-input');
        input.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                enviarMensaje();
            }
        });
    });
    async function enviarMensaje() {
        const input = document.getElementById('chatbot-input');
        const mensaje = input.value.trim();
        if (!mensaje) return;

        const body = document.getElementById('chatbot-body');
        const userMsg = document.createElement('div');
        userMsg.className = 'user-message';
        userMsg.textContent = mensaje;
        body.appendChild(userMsg);
        body.scrollTop = body.scrollHeight;


        input.value = '';

        
        try {
            const contexto = `### CONTEXTO:
                Bolivia tiene 9 departamentos: La Paz, Cochabamba, Santa Cruz, Oruro, Potosí, Chuquisaca, Tarija, Beni y Pando.
                CREADOR DE ESTE CHATBOT : Shavith Leonardo.
                ZONAS ACTIVAS EN COCHABAMBA:  ${@json($zonasActivas)} .

                ### PREGUNTA:
                ${mensaje}

                ### RESPUESTA:`;

            const response = await fetch('http://localhost:11434/api/generate', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                        model: 'gemma3:1b',
                        prompt: contexto,
                        stream: false
                    })
            });
            const data = await response.json();

            const botMsg = document.createElement('div');
            botMsg.className = 'chatbot-message';
            botMsg.textContent = data.response || 'No entendí eso.';
            body.appendChild(botMsg);
            body.scrollTop = body.scrollHeight;
        } catch (error) {
            const errorMsg = document.createElement('div');
            errorMsg.className = 'chatbot-message';
            errorMsg.textContent = 'Error al conectar con el asistente.';
            body.appendChild(errorMsg);
        }
    }
</script>
=======

>>>>>>> Stashed changes
=======

>>>>>>> parent of 3208fda (Revert "Merge branch 'main' into main-v1")
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">

<style>
#map {
    width: 100%;
    height: calc(100vh - 365px);
}
.banner-description {
    margin-top: 10px;
    font-size: 1.1rem;
    color: #8d8d8d;
    max-width: 600px;
    line-height: 1.4;
}

    /* Estilos generales */
    .fullscreen-detail {
        font-family: 'Lato', sans-serif;
        color: #333;
        line-height: 1.6;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .section-title {
        color: #2c3e50;
        margin-bottom: 30px;
        font-size: 2rem;
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
        width: 80px;
        height: 3px;
        background: #3498db;
    }

    /* Banner */
    .banner-container {
        position: relative;
        height: 100px;
        overflow: hidden;
        /* margin-bottom: 40px; */
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
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .banner-title {
        color: white;
        font-size: 3rem;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }


    #map-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0.3;
        z-index: 1;
    }

    /* Colores para los iconos */
    .bg-primary { background: #3498db; }
    .bg-success { background: #27ae60; }
    .bg-info { background: #2980b9; }
    .bg-warning { background: #f39c12; }
    .bg-danger { background: #e74c3c; }
    .bg-secondary { background: #7f8c8d; }


    .video-player {
        width: 100%;
        display: block;
        outline: none;
        max-height: 400px;
    }

    /* Footer Styles */
    .site-footer {
        background-color: #007dc3;
        color: white;
        padding: 21px 0 0;
        position: absolute;
        bottom: 0;
        width: 100%;
        /* margin-top: 60px; */
    }

    .footer-container {
        max-width: 1300px;
        margin: 0 auto;
        padding: 0 20px;
        display: grid;
        bottom: 0px;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 40px;
    }

    .footer-contact-section {
        text-align: left;
    }

    .footer-contact-section .footer-text {
        display: flex;
        align-items: flex-start;
        text-align: left;
        justify-content: flex-start;
    }

    .footer-logo-section {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .footer-logo {
        max-width: 100%;
        height: auto;
        max-height: 120px;
        object-fit: contain;
    }

    .footer-title {
        font-size: 1.3rem;
        margin-bottom: 20px;
        color: white;
        text-align: center;
        font-weight: 600;
    }

    .footer-text {
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        color: white;
        text-align: left;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .footer-text i {
        margin-right: 10px;
        color: white;
        min-width: 20px;
        text-align: center;
    }

    .footer-social-section {
        text-align: center;
    }

    .social-icons {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .social-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: white;
        color: #007dc3;
        transition: all 0.3s ease;
        font-size: 1.1rem;
    }

    .social-icon:hover {
        background-color: #f0f0f0;
        transform: translateY(-3px);
    }

    .whatsapp-float {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 70px;
        height: 70px;
        z-index: 100;
        transition: all 0.3s ease;
    }

    .whatsapp-float:hover {
        transform: scale(1.1);
    }

    .whatsapp-float img {
        width: 100%;
        height: auto;
    }

    .footer-bottom {
        background-color: rgba(0, 0, 0, 0.1);
        padding: 20px 0;
        text-align: center;
        
    }

    .copyright-text {
        margin: 0;
        font-size: 0.9rem;
        color: white;
    }

    /* Responsive Footer */
    @media (max-width: 992px) {
        .footer-container {
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .footer-logo-section {
            grid-column: span 2;
            order: -1;
            margin-bottom: 20px;
        }

        .footer-logo {
            max-height: 100px;
        }
    }

    @media (max-width: 768px) {
        .footer-container {
            grid-template-columns: 1fr;
            gap: 30px;
        }
        .footer-logo-section {
            grid-column: span 1;
        }

        .footer-contact-section,
        .footer-social-section {
            text-align: center;
        }

        .footer-contact-section .footer-text {
            justify-content: center;
            text-align: center;
        }

        .footer-text {
            justify-content: center;
        }

        .whatsapp-float {
            width: 60px;
            height: 60px;
            bottom: 20px;
            right: 20px;
        }
    }

    @media (max-width: 576px) {
        .footer-logo {
            max-height: 80px;
        }

        .footer-title {
            font-size: 1.2rem;
        }

        .footer-text {
            font-size: 0.9rem;
        }

        .whatsapp-float {
            width: 50px;
            height: 50px;
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .banner-title {
            font-size: 2.2rem;
        }

        .section-title {
            font-size: 1.7rem;
        }

        .footer-container {
            grid-template-columns: 1fr;
            text-align: center;
            gap: 30px;
        }

        .footer-contact-section,
        .footer-social-section {
            text-align: center;
        }

        .footer-contact-section .footer-text,
        .footer-title {
            text-align: center;
            text-align-last: center;
        }

        .footer-text {
            justify-content: center;
        }

        .social-icons {
            justify-content: center;
        }
    }

</style>
<style>
.zona-modal {
    position: fixed;
    top: 0;
    right: 0;
    width: 400px;
    height: 100%;
    background: white;
    box-shadow: -2px 0 10px rgba(0,0,0,0.3);
    /* padding: 20px; */
    overflow-y: auto;
    z-index: 1000;
    display: none;
}
.zona-modal.active {
    display: block;
}
.close-modal {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: white;
}
.zona-media img, .zona-media iframe {
    width: 100%;
    margin-bottom: 10px;
}
<<<<<<< HEAD
<<<<<<< Updated upstream
.chatbot-float {
    position: fixed;
    bottom: 100px;
    right: 43px;
    z-index: 1000;
    background-color: white;
    border-radius: 50%;
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    padding: 8px;
    transition: transform 0.3s ease;
}

.chatbot-float:hover {
    transform: scale(1.1);
}

.chatbot-float img {
    width: 30px;
    height: 30px;
}
.chatbot-modal {
    position: fixed;
    bottom: 160px;
    right: 20px;
    width: 350px;
    max-height: 400px;
    min-height: 300px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    display: none;
    flex-direction: column;
    overflow: hidden;
    z-index: 1001;
}

.chatbot-header {
    background: #007dc3;
    color: white;
    padding: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chatbot-body {
    padding: 10px;
    height: 300px;
    overflow-y: auto;
    background-color: #f5f5f5;
    display: flex;
    flex-direction: column;
    gap: 8px;
}


.chatbot-footer {
    display: flex;
    padding: 10px;
    border-top: 1px solid #ccc;
}

.chatbot-footer input {
    flex: 1;
    padding: 5px;
    font-size: 14px;
}

.chatbot-footer button {
    margin-left: 5px;
    background: #007dc3;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
}
.chatbot-message,
.user-message {
    max-width: 70%;
    padding: 10px 14px;
    border-radius: 12px;
    font-size: 14px;
    line-height: 1.4;
    word-wrap: break-word;
}

.chatbot-message {
    align-self: flex-start;
    background-color: #e0e0e0;
    color: #333;
}
=======
>>>>>>> Stashed changes
=======
>>>>>>> parent of 3208fda (Revert "Merge branch 'main' into main-v1")

.user-message {
    align-self: flex-end;
    background-color: #4a90e2;
    color: white;
}

</style>

@endsection
