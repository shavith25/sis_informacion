<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programa Gestión de la Biodiversidad (PGB)</title>

    <!-- Estilos Externos -->
    <link rel="stylesheet" href="{{ asset('css/style-public-areas.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos Internos (Específicos de esta vista) -->
    <style>
        .map-buttons {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 999;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .map-buttons button {
            width: 50px;
            min-width: 50px;
        }

        /* Control de Capas */
        .layer-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 10px;
            width: 100%;
        }

        .layer-button {
            width: 46px;
            height: 42px;
            font-size: 20px;
            line-height: 1;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            margin-bottom: 4px;
        }

        .layer-panel {
            background: rgba(255, 255, 255);
            color: rgb(0, 0, 0);
            padding: 12px;
            border-radius: 6px;
            width: 180px;
            margin-bottom: 8px;
            display: none;
            align-self: flex-start;
        }

        .layer-panel.show {
            display: block;
        }

        .layer-panel.hidden {
            opacity: 0;
            transform: scaleY(0);
            pointer-events: none;
            transform-origin: top;
        }

        /* Ajustes Generales */
        .main-map {
            padding: 15px;
            height: 850px;
        }

        .section-title {
            margin-top: 3rem;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        /* Buscador */
        #search-results {
            cursor: pointer;
        }

        #search-results li:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>

    <!-- HEADER Y NAVEGACIÓN -->
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

                <!-- Buscador -->
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

    <!-- SECCIÓN 1: MAPA INTERACTIVO -->
    <div id="inicio" class="main-map">
        <div
            style="width: 100%; height: 100%; background-color: #e3f2fd; display: flex; justify-content: center; align-items: center; position: relative;">
            <div id="map" style="width: 100%; height: 100%;">

                <div class="map-buttons">
                    <button class="btn btn-light btn-xl text-dark" onclick="map.zoomIn()">➕</button>
                    <button class="btn btn-light btn-xl text-dark" onclick="map.zoomOut()">➖</button>
                    <button class="btn btn-light btn-xl" onclick="centerMap()" title="Centrar en cochabamba">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="black"
                            viewBox="0 0 16 16">
                            <path
                                d="M8 0a.5.5 0 0 1 .5.5v1.55a6.5 6.5 0 0 1 5.45 5.45H15.5a.5.5 0 0 1 0 1h-1.55a6.5 6.5 0 0 1-5.45 5.45v1.55a.5.5 0 0 1-1 0v-1.55a6.5 6.5 0 0 1-5.45-5.45H.5a.5.5 0 0 1 0-1h1.55a6.5 6.5 0 0 1 5.45-5.45V.5A.5.5 0 0 1 8 0zm0 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8z" />
                        </svg>
                    </button>

                    <!-- Selector de Capas -->
                    <div class="layer-container" onmouseleave="hideLayerPanel()">
                        <button id="layer-button" class="btn btn-light btn-xl layer-button"
                            onmouseenter="showLayerPanel()">🗺️</button>
                        <div id="layer-panel" class="layer-panel">
                            <div id="layerAccordion">
                                <div class="card bg-white text-black border-0">
                                    <div class="card-header p-1" id="headingBaseMap">
                                        <h6 class="mb-0">Mapa Base</h6>
                                    </div>
                                    <div class="card-body p-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="basemap"
                                                id="radioHibrido" value="hibrido" checked>
                                            <label class="form-check-label" for="radioHibrido">Híbrido</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="basemap" id="radioOSM"
                                                value="osm">
                                            <label class="form-check-label" for="radioOSM">OpenStreetMap</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="basemap" id="radioTopo"
                                                value="topo">
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

    <!--CONTENIDO PRINCIPAL-->
    <main class="container">
        <section id="areas">
            <h2 class="section-title">Áreas Protegidas Destacadas</h2>
            <div class="card-grid">
                @forelse($zonas as $zona)
                    <div class="card">
                        @if ($zona->imagenes->count())
                            <img src="{{ asset('storage/' . $zona->imagenes->first()->url) }}"
                                alt="{{ $zona->nombre }}">
                        @endif
                        <div class="card-content">
                            <h3>{{ $zona->nombre }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($zona->descripcion, 200, '...') }}</p>
                            <div class="meta">
                                <span>{{ $zona->area ? $zona->area->nombre : 'Área no especificada' }}</span>
                                <span>{{ $zona->tipo_coordenada ?? 'Tipo no definido' }}</span>
                            </div>
                            <a href="{{ route('detalle.show', ['tipo' => 'zona', 'id' => $zona->id]) }}"
                                class="read-more">Explorar →</a>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info w-100 text-center">
                        No hay zonas protegidas disponibles en este momento.
                    </div>
                @endforelse
            </div>
        </section>

        <!-- SECCIÓN 3: ESPECIES EN PELIGRO (DATOS) -->
        <section id="especies">
            <h2 class="section-title">Especies en Peligro Crítico</h2>
            <div class="card-grid">
                @forelse($datos as $dato)
                    <div class="card">
                        @if ($dato->imagenes->count())
                            <img src="{{ asset('storage/' . $dato->imagenes->first()->path) }}"
                                alt="{{ $dato->especies_peligro }}">
                        @endif
                        <div class="card-content">
                            <h3>{{ $dato->especies_peligro }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($dato->flora_fauna ?: $dato->otros_datos, 200, '...') }}
                            </p>
                            <div class="meta">
                                <span>En Peligro Crítico</span>
                                <span>{{ $dato->provincia }},
                                    {{ $dato->zona ? $dato->zona->nombre : 'Bolivia' }}</span>
                            </div>
                            <a href="{{ route('detalle.show', ['tipo' => 'dato', 'id' => $dato->id]) }}"
                                class="read-more">Saber más →</a>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info w-100 text-center">
                        No hay especies en peligro crítico registradas.
                    </div>
                @endforelse
            </div>
        </section>

        <!-- SECCIÓN 4: ESPECIES EMBLEMÁTICAS -->
        <section id="especies-emblematicas">
            <h2 class="section-title">Especies Emblemáticas</h2>
            <div class="card-grid">
                @php
                    $emblematicas = $especies->where('tipo', 'emblematica');
                @endphp
                @forelse($emblematicas as $especie)
                    <div class="card">
                        @if ($especie->imagenes->count())
                            <img src="{{ asset('storage/' . $especie->imagenes->first()->url) }}"
                                alt="{!! $especie->titulo !!}">
                        @endif
                        <div class="card-content">
                            <h3>{!! $especie->titulo !!}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($especie->descripcion, 200, '...') }}</p>
                            <div class="meta">
                                <span>Tipo: Emblemática</span>
                            </div>
                            <a href="{{ route('detalle.show', ['tipo' => 'especie', 'id' => $especie->id]) }}"
                                class="read-more">Saber más →</a>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info w-100 text-center">
                        No hay especies emblemáticas registradas.
                    </div>
                @endforelse
            </div>
        </section>

        <!-- SECCIÓN 5: ESPECIES VULNERABLES -->
        <section id="especies-vulnerables">
            <h2 class="section-title">Especies Vulnerables</h2>
            <div class="card-grid">
                @php
                    $vulnerables = $especies->where('tipo', 'vulnerable');
                @endphp
                @forelse($vulnerables as $especie)
                    <div class="card">
                        @if ($especie->imagenes->count())
                            <img src="{{ asset('storage/' . $especie->imagenes->first()->url) }}"
                                alt="{{ $especie->titulo }}">
                        @endif
                        <div class="card-content">
                            <h3>{!! $especie->titulo !!}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($especie->descripcion, 200, '...') }}</p>
                            <div class="meta">
                                <span>Tipo: Vulnerable</span>
                            </div>
                            <a href="{{ route('detalle.show', ['tipo' => 'especie', 'id' => $especie->id]) }}"
                                class="read-more">Saber más →</a>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info w-100 text-center">
                        No hay especies vulnerables registradas.
                    </div>
                @endforelse
            </div>
        </section>

        <!-- SECCIÓN 6: NOTICIAS -->
        <section id="noticias">
            <h2 class="section-title">Últimas Noticias</h2>
            <div class="card-grid">
                @forelse($noticias as $noticia)
                    <div class="card">
                        @if ($noticia->imagenes->count())
                            <img src="{{ asset('storage/' . $noticia->imagenes->first()->ruta) }}"
                                alt="{{ $noticia->titulo }}">
                        @endif
                        <div class="card-content">
                            <h3>{!! $noticia->titulo !!}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($noticia->descripcion, 200, '...') }}</p>
                            <div class="meta">
                                <span>{{ $noticia->fecha_publicacion->format('Y-m-d') }}</span>
                                <span>{{ $noticia->subtitulo }}</span>
                            </div>
                            <a href="{{ route('detalle.show', ['tipo' => 'noticia', 'id' => $noticia->id]) }}"
                                class="read-more">Leer más →</a>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info w-100 text-center">
                        No hay noticias disponibles en este momento.
                    </div>
                @endforelse
            </div>
        </section>

        <!-- SECCIÓN 7: CONCIENTIZACIÓN -->
        <section id="conciencia" class="awareness my-5 text-center p-5 bg-blend-color-burn rounded">
            <h2>Protejamos Nuestra Biodiversidad</h2>
            <p style="text-align: justify; font-size: 1.1rem; line-height: 1.8;">
                Cochabamba es un corazón ecológico que alberga una extraordinaria variedad de vida.
                Desde las cumbres andinas hasta los bosques tropicales, nuestros ecosistemas sostienen
                especies únicas de flora y fauna. Esta biodiversidad es el pilar de nuestro bienestar,
                regulando el clima, purificando el agua y manteniendo el equilibrio natural.
            </p>
            <p class="mt-3"><i class="fas fa-map-marker-alt"></i> Cochabamba, Bolivia</p>
            <a href="/concientizacion" class="btn btn-success btn-lg mt-3">Cómo ayudar</a>
        </section>

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
                <div class="footer-section">
                    <h3>Programa Gestión de la Biodiversidad (PGB)</h3>
                    <p>Plataforma dedicada a la conservación de la biodiversidad y la prevención de los ecosistemas.</p>
                </div>
                <div class="footer-section">
                    <h3>REDES SOCIALES</h3>
                    <div class="social-icons d-flex gap-2">
                        <a href="https://www.facebook.com/GobernacionDeCochabamba/" target="_blank"><i
                                class="bi bi-facebook"></i></a>
                        <a href="https://www.youtube.com/@gobernaciondecochabamba8326" target="_blank"><i
                                class="bi bi-youtube"></i></a>
                        <a href="https://www.tiktok.com/@gobernaciondecochabamba" target="_blank"><i
                                class="bi bi-tiktok"></i></a>
                    </div>
                </div>
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
            osm: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }),
            hibrido: L.layerGroup([
                L.tileLayer(
                    'https://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                        attribution: '&copy; Esri, i-cubed, USDA, USGS, etc.'
                    }),
                L.tileLayer(
                    'https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Transportation/MapServer/tile/{z}/{y}/{x}', {
                        attribution: 'Labels © Esri'
                    })
            ]),
            topo: L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
                attribution: 'Map data: &copy; OpenTopoMap (CC-BY-SA)'
            })
        };
        let currentLayer;

        // --- Inicialización del DOM ---
        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            initSearch();
        });

        // --- Funciones del Mapa ---
        function initMap() {
            const cochabambaBounds = L.latLngBounds(
                L.latLng(-18.50, -67.50),
                L.latLng(-16.00, -64.00)
            );

            map = L.map('map', {
                zoomControl: false,
                minZoom: 9
            }).setView([-17.3895, -66.1568], 13);

            const drawnItems = new L.FeatureGroup();
            currentLayer = tileLayers.hibrido;
            currentLayer.addTo(map);
            map.addLayer(drawnItems);

            // Dibujar polígonos de zonas
            if (zonas_j) {
                zonas_j.forEach(element => {
                    if (element.historial) {
                        element.historial.forEach(poli => {
                            if (Array.isArray(poli.coordenadas)) {
                                poli.coordenadas.forEach(subpoli => {
                                    if (subpoli.tipo === 'poligono' && Array.isArray(subpoli
                                            .coordenadas)) {
                                        const latlngs = subpoli.coordenadas.map(coord => [coord.lat,
                                            coord.lng
                                        ]);
                                        const polygon = L.polygon(latlngs, {
                                            color: 'yellow',
                                            weight: 2,
                                            fillColor: 'rgba(0,128,0,0.8)',
                                            fillOpacity: 0.5
                                        }).addTo(drawnItems);
                                        polygon.bindPopup(`Área protegida: ${element.nombre}`);
                                    }
                                });
                            }
                        });
                    }
                });

                if (drawnItems.getLayers().length > 0) {
                    map.fitBounds(drawnItems.getBounds());
                }
            }

            // Evento cambio de capa
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

        function centerMap() {
            map.setView([-17.3895, -66.1568], 13);
        }

        // --- Funciones del Buscador (Photon API) ---
        function initSearch() {
            var boliviaBbox = [-69.6459, -22.898, -57.453, -9.676];
            var resultsList = document.getElementById("search-results");
            var searchBox = document.getElementById("search-box");

            async function buscarSugerencias(query) {
                const url =
                    `https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&bbox=${boliviaBbox.join(',')}&limit=5`;
                try {
                    const response = await fetch(url);
                    const data = await response.json();
                    return data.features;
                } catch (error) {
                    console.error("Error buscando:", error);
                    return [];
                }
            }

            searchBox.addEventListener("input", async function() {
                const query = this.value.trim();
                resultsList.innerHTML = "";

                if (query.length < 3) {
                    resultsList.style.display = "none";
                    return;
                }

                const features = await buscarSugerencias(query);

                if (!features || features.length === 0) {
                    resultsList.style.display = "none";
                    return;
                }

                features.forEach((f) => {
                    const li = document.createElement("li");
                    li.className = "list-group-item list-group-item-action";
                    li.textContent = f.properties.name +
                        (f.properties.state ? ", " + f.properties.state : "") +
                        (f.properties.country ? ", " + f.properties.country : "");

                    li.addEventListener("click", () => {
                        const [lng, lat] = f.geometry.coordinates;
                        map.setView([lat, lng], 14);
                        L.marker([lat, lng]).addTo(map)
                            .bindPopup(f.properties.name || "Ubicación").openPopup();
                        resultsList.style.display = "none";
                        searchBox.value = li.textContent;
                    });
                    resultsList.appendChild(li);
                });
                resultsList.style.display = "block";
            });

            document.addEventListener("click", function(e) {
                if (!resultsList.contains(e.target) && e.target !== searchBox) {
                    resultsList.style.display = "none";
                }
            });
        }

        // --- Funciones de UI (Capas) ---
        function showLayerPanel() {
            const panel = document.getElementById('layer-panel');
            const button = document.getElementById('layer-button');
            panel.classList.add('show');
            button.style.display = 'none';
        }

        function hideLayerPanel() {
            const panel = document.getElementById('layer-panel');
            const button = document.getElementById('layer-button');
            panel.classList.remove('show');
            button.style.display = 'flex';
        }
    </script>
</body>

</html>
