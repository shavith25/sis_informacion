<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programa Gestión de la Biodiversidad (PGB)</title>

    <link rel="stylesheet" href="{{ asset('css/style-public-areas.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        .map-buttons {
            position: absolute; top: 10px; left: 10px; z-index: 999;
            display: flex; flex-direction: column; gap: 8px;
        }

        .map-buttons button { width: 50px; min-width: 50px; }
        .layer-container {
            display: flex; flex-direction: column; align-items: center;
            margin-top: 10px; width: 100%;
        }

        .layer-button {
            width: 46px; height: 42px; font-size: 20px; line-height: 1; padding: 0;
            display: flex; justify-content: center; align-items: center;
            cursor: pointer; margin-bottom: 4px;
        }

        .layer-panel {
            background: rgba(255, 255, 255); color: rgb(0, 0, 0);
            padding: 12px; border-radius: 6px; width: 180px; margin-bottom: 8px;
            display: none; align-self: flex-start;
        }

        .layer-panel.show { display: block; }
        .main-map { padding: 15px; height: 800px; }
        .section-title {
            margin-top: 3rem; margin-bottom: 1.5rem;
            border-bottom: 2px solid #eee; padding-bottom: 10px;
        }

        #search-results { cursor: pointer; }
        #search-results li:hover { background-color: #f8f9fa; }

        /* --- NUEVOS ESTILOS DEL FOOTER --- */
        footer {
            background-color: #0077c0; /* Azul Institucional */
            color: white;
            padding-top: 50px;
            padding-bottom: 20px;
            font-family: sans-serif;
            position: relative;
        }

        .footer-title {
            font-weight: 700;
            text-transform: uppercase;
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
            padding-bottom: 5px;
            margin-bottom: 20px;
            display: inline-block;
            font-size: 1rem;
        }

        .contact-list {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 0.9rem;
        }

        .contact-list li {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
        }

        .contact-list i {
            color: #ffc107;
            margin-right: 10px;
            font-size: 1.1rem;
            margin-top: 3px;
        }

        .social-box {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 45px;
            height: 45px;
            border: 1px solid white;
            border-radius: 8px;
            color: white;
            margin-right: 10px;
            text-decoration: none;
            font-size: 1.3rem;
            transition: all 0.3s ease;
        }

        .social-box:hover {
            background-color: white;
            color: #0077c0;
        }

        .footer-logo {
            max-height: 80px;
            margin-bottom: 15px;
        }

        .copyright-line {
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            margin-top: 40px;
            padding-top: 20px;
            text-align: center;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.9);
        }

        /* Botón WhatsApp Flotante */
        .whatsapp-float {
            position: fixed;
            bottom: 50px;
            right: 50px;
            z-index: 1050;
            transition: transform 0.3s;
        }

        .whatsapp-float:hover { transform: scale(1.1); }
        .whatsapp-float img { width: 100px; height: 100px; }

        /* Ajuste Responsive Footer */
        @media (max-width: 768px) {
            .footer-col {
                text-align: center;
                margin-bottom: 30px;
            }

            .contact-list li {
                justify-content: center;
            }

            .footer-title {
                display: inline-block;
            }
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
                        <li><a href="#inicio">INICIO</a></li>
                        <li><a href="#areas">ÁREAS PROTEGIDAS</a></li>
                        <li><a href="/limites">LIMITES</a></li>
                        <li><a href="#especies">ESPECIES</a></li>
                        <li><a href="#noticias">NOTICIAS</a></li>
                        <li><a href="#conciencia">CONCIENTIZACIÓN</a></li>
                    </ul>
                </nav>

                <div class="user-actions">
                    <div class="search-bar">
                        <div class="mr-4 position-relative" style="width:250px;">
                            <input type="text" class="form-control" id="search-box"
                                placeholder="Buscar ubicación...">
                            <ul id="search-results" class="list-group position-absolute w-100"
                                style="z-index:1000; max-height:200px; overflow-y:auto; display:none;"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div id="inicio" class="main-map">
        <div style="width: 100%; height: 100%; background-color: #e3f2fd; display: flex; justify-content: center; align-items: center; position: relative;">
            <div id="map" style="width: 100%; height: 100%;">

                <div class="map-buttons">
                    <button class="btn btn-light btn-xl text-dark" onclick="map.zoomIn()">➕</button>
                    <button class="btn btn-light btn-xl text-dark" onclick="map.zoomOut()">➖</button>
                    <button class="btn btn-light btn-xl" onclick="centerMap()" title="Centrar en cochabamba">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="black" viewBox="0 0 16 16">
                            <path d="M8 0a.5.5 0 0 1 .5.5v1.55a6.5 6.5 0 0 1 5.45 5.45H15.5a.5.5 0 0 1 0 1h-1.55a6.5 6.5 0 0 1-5.45 5.45v1.55a.5.5 0 0 1-1 0v-1.55a6.5 6.5 0 0 1-5.45-5.45H.5a.5.5 0 0 1 0-1h1.55a6.5 6.5 0 0 1 5.45-5.45V.5A.5.5 0 0 1 8 0zm0 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8z" />
                        </svg>
                    </button>

                    <div class="layer-container" onmouseleave="hideLayerPanel()">
                        <button id="layer-button" class="btn btn-light btn-xl layer-button" onmouseenter="showLayerPanel()">🗺️</button>
                        <div id="layer-panel" class="layer-panel">
                            <div id="layerAccordion">
                                <div class="card bg-white text-black border-0">
                                    <div class="card-header p-1" id="headingBaseMap">
                                        <h6 class="mb-0">Mapa Base</h6>
                                    </div>
                                    <div class="card-body p-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="basemap" id="radioHibrido" value="hibrido" checked>
                                            <label class="form-check-label" for="radioHibrido">Híbrido</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="basemap" id="radioOSM" value="osm">
                                            <label class="form-check-label" for="radioOSM">OpenStreetMap</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="basemap" id="radioTopo" value="topo">
                                            <label class="form-check-label" for="radioTopo">OpenTopoMap</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="container">
        <section id="areas">
            <h2 class="section-title">Áreas Protegidas Destacadas</h2>
            <div class="card-grid">
                @forelse($zonas as $zona)
                    <div class="card">
                        @if ($zona->imagenes->count())
                            <img src="{{ asset('storage/' . $zona->imagenes->first()->url) }}" alt="{{ $zona->nombre }}">
                        @endif
                        <div class="card-content">
                            <h3>{{ $zona->nombre }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($zona->descripcion, 200, '...') }}</p>
                            <div class="meta">
                                <span>{{ $zona->area ? $zona->area->nombre : 'Área no especificada' }}</span>
                                <span>{{ $zona->tipo_coordenada ?? 'Tipo no definido' }}</span>
                            </div>
                            @php
                                $rand = rand(10000, 99999);
                                $token = dechex($rand) . 'x' . dechex($zona->id ^ $rand);
                            @endphp
                            <a href="{{ route('detalle.show', ['tipo' => 'zona', 'id' => $token]) }}" class="read-more">Explorar →</a>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info w-100 text-center">No hay zonas protegidas disponibles.</div>
                @endforelse
            </div>
        </section>

        <section id="especies">
            <h2 class="section-title">Especies en Peligro Crítico</h2>
            <div class="card-grid">
                @forelse($datos as $dato)
                    <div class="card">
                        @if ($dato->imagenes->count())
                            <img src="{{ asset('storage/' . $dato->imagenes->first()->path) }}" alt="{{ $dato->especies_peligro }}">
                        @endif
                        <div class="card-content">
                            <h3>{{ $dato->especies_peligro }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($dato->flora_fauna ?: $dato->otros_datos, 200, '...') }}</p>
                            <div class="meta">
                                <span>En Peligro Crítico</span>
                                <span>{{ $dato->provincia }}, {{ $dato->zona ? $dato->zona->nombre : 'Bolivia' }}</span>
                            </div>
                            @php
                                $rand = rand(10000, 99999);
                                $token = dechex($rand) . 'x' . dechex($dato->id ^ $rand);
                            @endphp
                            <a href="{{ route('detalle.show', ['tipo' => 'dato', 'id' => $token]) }}" class="read-more">Saber más →</a>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info w-100 text-center">No hay especies registradas.</div>
                @endforelse
            </div>
        </section>

        <section id="noticias">
            <h2 class="section-title">Últimas Noticias</h2>
            <div class="card-grid">
                @forelse($noticias as $noticia)
                    <div class="card">
                        @if ($noticia->imagenes->count())
                            <img src="{{ asset('storage/' . $noticia->imagenes->first()->ruta) }}" alt="{{ $noticia->titulo }}">
                        @endif
                        <div class="card-content">
                            <h3>{!! $noticia->titulo !!}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($noticia->descripcion, 200, '...') }}</p>
                            <div class="meta">
                                <span>{{ $noticia->fecha_publicacion->format('Y-m-d') }}</span>
                                <span>{{ $noticia->subtitulo }}</span>
                            </div>
                            @php
                                $rand = rand(10000, 99999);
                                $token = dechex($rand) . 'x' . dechex($noticia->id ^ $rand);
                            @endphp
                            <a href="{{ route('detalle.show', ['tipo' => 'noticia', 'id' => $token]) }}" class="read-more">Leer más →</a>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info w-100 text-center">No hay noticias disponibles.</div>
                @endforelse
            </div>
        </section>

        <section id="conciencia" class="awareness my-5 text-center p-5 bg-blend-color-burn rounded">
            <h2>Protejamos Nuestra Biodiversidad</h2>
            <p style="text-align: justify; font-size: 1.1rem; line-height: 1.8;">
                Cochabamba es un corazón ecológico que alberga una extraordinaria variedad de vida.
                Tu participación es clave para conservar nuestros bosques, ríos y especies.
            </p>
            <p class="mt-3"><i class="fas fa-map-marker-alt"></i> Cochabamba, Bolivia</p>
            <a href="/concientizacion" class="btn btn-success btn-lg mt-3">Cómo ayudar</a>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 footer-col mb-4 mb-lg-0">
                    <h5 class="footer-title">INFORMACIÓN DE CONTACTO</h5>
                    <ul class="contact-list">
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span>gobernaciondecochabamba@<br>gobernaciondecochabamba.bo</span>
                        </li>
                        <li>
                            <i class="fas fa-phone-alt"></i>
                            <span>+ (591) 71701056</span>
                        </li>
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Av. Aroma N°: O-327<br>Plaza San Sebastián<br>Edificio del Órgano Ejecutivo</span>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-4 footer-col mb-4 mb-lg-0 text-center">
                    <img src="{{ url('img/logo3.png') }}" alt="Cochabamba Unir Trabajar y Crecer" class="img-fluid footer-logo">
                    <h5 class="mt-2 fw-bold text-white">Programa Gestión de la Biodiversidad (PGB)</h5>
                    <p class="small text-white-50 mt-2">
                        Plataforma dedicada a la conservación de la biodiversidad y la prevención de los ecosistemas.
                    </p>
                </div>

                <div class="col-lg-4 footer-col text-lg-end text-center mt-lg-0">
                    <h5 class="footer-title">REDES SOCIALES</h5>
                    <div class="mt-2">
                        <a href="https://www.facebook.com/GobernacionDeCochabamba" target="_blank" class="social-box">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://www.youtube.com/@gobernaciondecochabamba8326" target="_blank" class="social-box">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="https://www.tiktok.com/@gobernaciondecochabamba" target="_blank" class="social-box">
                            <i class="fab fa-tiktok"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="copyright-line">
                Programa Gestión de la Biodiversidad (PGB) © {{ date('Y') }} Todos los derechos reservados | Gobierno Autónomo Departamental de Cochabamba.
            </div>
        </div>

        <a href="https://wa.me/59171701056" target="_blank" class="whatsapp-float">
            <img src="{{ asset('img/wsp.png') }}" alt="WhatsApp">
        </a>
    </footer>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let map;
        let zonas_j = @json($zonas);
        let tileLayers = {
            osm: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: 'OSM' }),
            hibrido: L.layerGroup([
                L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: 'Esri' }),
                L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Transportation/MapServer/tile/{z}/{y}/{x}')
            ]),
            topo: L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', { attribution: 'OpenTopoMap' })
        };
        let currentLayer;

        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            initSearch();
        });

        function initMap() {
            map = L.map('map', { zoomControl: false, minZoom: 9 }).setView([-17.3895, -66.1568], 13);
            const drawnItems = new L.FeatureGroup();
            currentLayer = tileLayers.hibrido;
            currentLayer.addTo(map);
            map.addLayer(drawnItems);

            if (zonas_j) {
                zonas_j.forEach(element => {
                    if (element.historial) {
                        element.historial.forEach(poli => {
                            if (Array.isArray(poli.coordenadas)) {
                                poli.coordenadas.forEach(subpoli => {
                                    if (subpoli.tipo === 'poligono' && Array.isArray(subpoli.coordenadas)) {
                                        const latlngs = subpoli.coordenadas.map(coord => [coord.lat, coord.lng]);
                                        L.polygon(latlngs, {
                                            color: 'yellow', weight: 2, fillColor: 'rgba(0,128,0,0.8)', fillOpacity: 0.5
                                        }).addTo(drawnItems).bindPopup(`Área protegida: ${element.nombre}`);
                                    }
                                });
                            }
                        });
                    }
                });
                if (drawnItems.getLayers().length > 0) map.fitBounds(drawnItems.getBounds());
            }

            document.querySelectorAll('input[name="basemap"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    if (tileLayers[this.value]) {
                        map.removeLayer(currentLayer);
                        currentLayer = tileLayers[this.value];
                        currentLayer.addTo(map);
                    }
                });
            });
        }

        function centerMap() { map.setView([-17.3895, -66.1568], 13); }

        function initSearch() {
            var boliviaBbox = [-69.6459, -22.898, -57.453, -9.676];
            var resultsList = document.getElementById("search-results");
            var searchBox = document.getElementById("search-box");

            searchBox.addEventListener("input", async function() {
                const query = this.value.trim();
                resultsList.innerHTML = "";
                if (query.length < 3) { resultsList.style.display = "none"; return; }
                
                try {
                    const url = `https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&bbox=${boliviaBbox.join(',')}&limit=5`;
                    const res = await fetch(url).then(r => r.json());
                    if (!res.features.length) { resultsList.style.display = "none"; return; }
                    
                    res.features.forEach(f => {
                        const li = document.createElement("li");
                        li.className = "list-group-item list-group-item-action";
                        li.textContent = f.properties.name + (f.properties.city ? `, ${f.properties.city}` : "");
                        li.onclick = () => {
                            const [lng, lat] = f.geometry.coordinates;
                            map.setView([lat, lng], 14);
                            L.marker([lat, lng]).addTo(map).bindPopup(f.properties.name).openPopup();
                            resultsList.style.display = "none"; searchBox.value = li.textContent;
                        };
                        resultsList.appendChild(li);
                    });
                    resultsList.style.display = "block";
                } catch(e) { console.error(e); }
            });
            
            document.addEventListener("click", (e) => {
                if (!resultsList.contains(e.target) && e.target !== searchBox) resultsList.style.display = "none";
            });
        }

        function showLayerPanel() { document.getElementById('layer-panel').classList.add('show'); document.getElementById('layer-button').style.display = 'none'; }
        function hideLayerPanel() { document.getElementById('layer-panel').classList.remove('show'); document.getElementById('layer-button').style.display = 'flex'; }
    </script>
</body>
</html>