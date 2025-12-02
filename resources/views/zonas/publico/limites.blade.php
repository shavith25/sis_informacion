<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Límites - Programa Gestión de la Biodiversidad (PGB)</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" href="{{ url('img/logo3.png') }}" type="image/png">

    <style>
        :root {
            --primary-blue: #0077c0;
            --hover-blue: #005a94;
            --bg-gray: #f8f9fa;
        }

        body {
            background-color: var(--bg-gray);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
        }

        /* --- HEADER INSTITUCIONAL --- */
        .header-cochabamba {
            background-color: var(--primary-blue);
            padding: 0.8rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand img {
            height: 50px;
        }

        .nav-link {
            color: white !important;
            font-weight: 500;
            font-size: 0.9rem;
            text-transform: uppercase;
            margin-left: 15px;
            transition: opacity 0.3s;
        }

        .nav-link:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        /* Buscador en el header */
        .header-search {
            position: relative;
        }

        .header-search input {
            border-radius: 4px;
            border: none;
            padding: 8px 15px;
            font-size: 0.9rem;
            width: 250px;
        }

        .search-results-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 4px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1050;
            max-height: 300px;
            overflow-y: auto;
            display: none;
        }

        /* --- MAPA --- */
        #map-container {
            position: relative;
            height: 600px;
            width: 100%;
            border-radius: 0;
            overflow: hidden;
            box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.1);
        }

        #map {
            height: 100%;
            width: 100%;
            z-index: 1;
        }

        .map-controls {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn-map-control {
            background: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #333;
            transition: all 0.2s;
        }

        .btn-map-control:hover {
            background: #f0f0f0;
            transform: scale(1.05);
        }

        /* Panel de Capas */
        .layer-panel {
            position: absolute;
            top: 20px;
            left: 70px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            width: 220px;
            display: none;
        }

        .layer-panel.show {
            display: block;
        }

        /* --- TARJETAS --- */
        .card-profesional {
            border: none;
            border-radius: 15px;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.5s ease, box-shadow 0.5s ease;
            overflow: hidden;
            height: 100%;
        }

        .card-profesional:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card-img-wrapper {
            position: relative;
            height: 180px;
            overflow: hidden;
        }

        .card-profesional img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .card-profesional:hover img {
            transform: scale(1.05);
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-title {
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .btn-ver-mapa {
            width: 100%;
            border: 1px solid var(--primary-blue);
            color: var(--primary-blue);
            background: white;
            padding: 8px;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-ver-mapa:hover {
            background: var(--primary-blue);
            color: white;
        }

        /* --- FOOTER --- */
        footer {
            background-color: #0077c0;
            color: white;
            padding: 40px 0 20px;
        }

        .whatsapp-float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 30px;
            right: 30px;
            background-color: #25D366;
            color: #fff;
            border-radius: 50px;
            text-align: center;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
            transition: transform 0.3s;
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
        }
    </style>
</head>

<body>

    <header class="header-cochabamba">
        <nav class="navbar navbar-expand-lg container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ url('img/logo3.png') }}" alt="Gobernación de Cochabamba">
            </a>
            <button class="navbar-toggler text-white border-white" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="fas fa-bars text-white"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/areas-protegidas#inicio') }}">INICIO</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/areas-protegidas#areas') }}">ÁREAS PROTEGIDAS</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/limites') }}">LÍMITES</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/areas-protegidas#especies') }}">ESPECIES</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/areas-protegidas#noticias') }}">NOTICIAS</a></li>
                    <li class="nav-item"><a class="nav-link"
                        href="{{ url('/areas-protegidas#conciencia') }}">CONCIENTIZACIÓN</a>
                    </li>
                </ul>
                <div class="header-search ms-lg-3 mt-3 mt-lg-0">
                    <input type="text" id="search-box" placeholder="Buscar ubicación...">
                    <ul id="search-results" class="list-group search-results-dropdown"></ul>
                </div>
            </div>
        </nav>
    </header>

    <section class="container mt-4 mb-3">
        <div class="card border-0 shadow-sm p-3">
            <h5 class="mb-3 text-secondary"><i class="fas fa-filter me-2"></i>Filtrar Límites</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small text-muted">Departamento</label>
                    <select name="departamento_id" id="departamento_id" class="form-select">
                        <option value="">-- Seleccionar el Departamento --</option>
                        @foreach ($departamentos as $d)
                            <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label small text-muted">Provincia</label>
                    <select name="provincia_id" id="provincia_id" class="form-select" disabled>
                        <option value="">-- Seleccione una provincia --</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label small text-muted">Municipio</label>
                    <select name="municipio_id" id="municipio_id" class="form-select" disabled>
                        <option value="">-- Seleccione un municipio --</option>
                    </select>
                </div>

                <div class="col-12 text-end">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetSeleccion()">
                        <i class="fas fa-undo me-1"></i> Limpiar Filtros
                    </button>
                </div>
            </div>
        </div>
    </section>

    <div id="inicio">
        <div id="map-container">
            <div id="map"></div>
            <div class="map-controls">
                <button class="btn-map-control" onclick="map.zoomIn()" title="Acercar"><i
                    class="fas fa-plus"></i>
                </button>

                <button class="btn-map-control" onclick="map.zoomOut()" title="Alejar"><i
                    class="fas fa-minus"></i>
                </button>

                <button class="btn-map-control" onclick="centerMap()" title="Centrar"><i
                    class="fas fa-compress-arrows-alt"></i>
                </button>

                <button class="btn-map-control" id="layer-button" onclick="toggleLayerPanel()" title="Capas"><i
                    class="fas fa-layer-group"></i>
                </button>
            </div>

            <div id="layer-panel" class="layer-panel">
                <h6 class="mb-3">Mapa Base</h6>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="basemap" id="radioHibrido" value="hibrido"
                        checked>
                    <label class="form-check-label" for="radioHibrido">Satélite Híbrido</label>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="basemap" id="radioOSM" value="osm">
                    <label class="form-check-label" for="radioOSM">OpenStreetMap</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="basemap" id="radioTopo" value="topo">
                    <label class="form-check-label" for="radioTopo">Topográfico</label>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4" id="info-panel" style="display:none;">
        <div class="card shadow-sm p-4 bg-white border-start border-5 border-primary">
            <h3 id="info-titulo" class="text-primary fw-bold"></h3>
            <p id="info-descripcion" class="text-muted"></p>
            <div id="info-media" class="row mt-3"></div>
        </div>
    </div>

    <section class="container mt-5 mb-5">
        <div class="d-flex align-items-center mb-4">
            <i class="fas fa-map-marked-alt fa-2x text-primary me-3"></i>
            <h2 class="mb-0 fw-bold text-dark">Departamentos, Provincias y Municipios</h2>
        </div>
        <h4 class="mb-3 text-primary border-bottom pb-2">Departamentos</h4>
        <div class="row g-4">
            @forelse ($departamentos as $depto)
                <div class="col-md-6 col-lg-4">
                    <div class="card-profesional h-100">
                        <div class="card-img-wrapper">
                            @if ($depto->media->count())
                                <img src="{{ Storage::url($depto->media->first()->archivo) }}"
                                    alt="{{ $depto->nombre }}">
                            @else
                                <img src="{{ asset('img/no-image.jpg') }}" alt="Sin imagen"
                                    style="background-color: #e9ecef;">
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $depto->nombre }}</h5>
                            <p class="card-text text-muted small mb-3">
                                {{ Str::limit($depto->descripcion, 100, '...') }}
                            </p>
                            <button class="btn-ver-mapa" onclick="seleccionarDepartamento({{ $depto->id }})">
                                <i class="fas fa-location-arrow me-2"></i> Ver en Mapa
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light text-center">No hay información.</div>
                </div>
            @endforelse
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row gy-4">
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3 text-white border-bottom pb-2 d-inline-block">CONTACTO</h5>
                    <ul class="list-unstyled text-light">
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> gobernacion@cochabamba.bo</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> + (591) 71701056</li>
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> Av. Aroma N°: O-327</li>
                    </ul>
                </div>

                <div class="col-md-4 text-center">
                    <img src="{{ url('img/logo3.png') }}" alt="Logo" class="img-fluid" style="max-height: 80px;">
                </div>

                <div class="col-md-4 text-md-end">
                    <h5 class="fw-bold mb-3 text-white border-bottom pb-2 d-inline-block">SÍGUENOS</h5>
                    <div class="fs-4">
                        <a href="https://www.facebook.com/GobernacionDeCochabamba" class="text-white me-3"><i class="fab fa-facebook"></i></a>
                        <a href="https://www.youtube.com/@gobernaciondecochabamba8326" class="text-white me-3"><i class="fab fa-youtube"></i></a>
                        <a href="https://www.tiktok.com/@gobernaciondecochabamba" class="text-white"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4 pt-3 border-top border-secondary">
                <small>&copy; {{ date('Y') }} Programa Gestión de la Biodiversidad. Todos los derechos reservados.</small>
            </div>
        </div>
        <a href="https://wa.me/59171701056" target="_blank" class="whatsapp-float">
            <img src="{{ asset('img/wsp.png') }}" alt="WhatsApp" style="width: 65px;">
        </a>
    </footer>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let map;
        let currentLayer;
        let currentGeoLayer = null;

        // Configuración de Capas
        let tileLayers = {
            osm: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'OSM'
            }),
            hibrido: L.layerGroup([
                L.tileLayer(
                    'https://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                        attribution: 'Esri'
                    }),
                L.tileLayer(
                    'https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Transportation/MapServer/tile/{z}/{y}/{x}'
                    )
            ]),
            topo: L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
                attribution: 'OpenTopoMap'
            })
        };

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            map = L.map('map', {
                zoomControl: false,
                minZoom: 5
            }).setView([-17.3895, -66.1568], 13);
            currentLayer = tileLayers.hibrido;
            currentLayer.addTo(map);

            // Listener Cambiar Capa
            document.querySelectorAll('input[name="basemap"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    if (tileLayers[this.value]) {
                        map.removeLayer(currentLayer);
                        currentLayer = tileLayers[this.value];
                        currentLayer.addTo(map);
                    }
                });
            });

            // Buscador
            setupSearch();
        });

        function toggleLayerPanel() {
            document.getElementById('layer-panel').classList.toggle('show');
        }

        function centerMap() {
            map.setView([-17.3895, -66.1568], 13);
        }

        function dibujarGeometria(geojson) {
            if (currentGeoLayer) map.removeLayer(currentGeoLayer);
            if (!geojson) return;

            currentGeoLayer = L.geoJSON(geojson, {
                style: {
                    color: '#0077c0',
                    weight: 3,
                    fillColor: '#0077c0',
                    fillOpacity: 0.15
                }
            }).addTo(map);
            map.fitBounds(currentGeoLayer.getBounds());
        }

        document.getElementById('departamento_id').addEventListener('change', async function() {
            const id = this.value;
            resetSelect('provincia_id');
            resetSelect('municipio_id');

            if (!id) {
                limpiarInfo();
                return;
            }

            try {
                const detalle = await fetch(`/limites/detalle/departamento/${id}`).then(r => r.json());
                mostrarInfo(detalle);
                dibujarGeometria(detalle.geometria);

                document.getElementById('provincia_id').innerHTML = '<option>Cargando...</option>';
                const provincias = await fetch(`/limites/provincias/${id}`).then(r => r.json());
                llenarSelect('provincia_id', provincias);
            } catch (e) {
                console.error("Error cargando provincias:", e);
            }
        });

        document.getElementById('provincia_id').addEventListener('change', async function() {
            const id = this.value;
            resetSelect('municipio_id');
            if (!id) {
                limpiarInfo();
                return;
            }

            try {
                const detalle = await fetch(`/limites/detalle/provincia/${id}`).then(r => r.json());
                mostrarInfo(detalle);
                dibujarGeometria(detalle.geometria);

                document.getElementById('municipio_id').innerHTML = '<option>Cargando...</option>';
                const municipios = await fetch(`/limites/municipios/${id}`).then(r => r.json());
                llenarSelect('municipio_id', municipios);
            } catch (e) {
                console.error("Error cargando municipios:", e);
            }
        });

        document.getElementById('municipio_id').addEventListener('change', async function() {
            const id = this.value;
            if (!id) {
                limpiarInfo();
                return;
            }
            try {
                const detalle = await fetch(`/limites/detalle/municipio/${id}`).then(r => r.json());
                mostrarInfo(detalle);
                dibujarGeometria(detalle.geometria);
            } catch (e) {
                console.error(e);
            }
        });

        // Helpers UI
        function mostrarInfo(data) {
            document.getElementById('info-titulo').textContent = data.nombre;
            document.getElementById('info-descripcion').textContent = data.descripcion || 'Sin descripción disponible.';

            const mediaDiv = document.getElementById('info-media');
            mediaDiv.innerHTML = '';

            if (data.media && data.media.length > 0) {
                data.media.forEach(item => {
                    const col = document.createElement('div');
                    col.className = 'col-md-3 col-6 mb-3';
                    let content = '';
                    if (item.tipo && item.tipo.includes('image')) {
                        content =
                            `<img src="/storage/${item.archivo}" class="img-fluid rounded shadow-sm" style="height:120px; width:100%; object-fit:cover;">`;
                    } else if (item.tipo && item.tipo.includes('video')) {
                        content =
                            `<video src="/storage/${item.archivo}" class="img-fluid rounded shadow-sm" style="height:120px; width:100%; object-fit:cover;" controls></video>`;
                    }
                    col.innerHTML = content;
                    mediaDiv.appendChild(col);
                });
            }
            document.getElementById('info-panel').style.display = 'block';
        }

        function limpiarInfo() {
            if (currentGeoLayer) map.removeLayer(currentGeoLayer);
            document.getElementById('info-panel').style.display = 'none';
        }

        function resetSeleccion() {
            document.getElementById('departamento_id').value = "";
            resetSelect('provincia_id');
            resetSelect('municipio_id');
            limpiarInfo();
            centerMap();
        }

        function resetSelect(id) {
            const sel = document.getElementById(id);
            sel.innerHTML = `<option value="">-- Seleccionar --</option>`;
            sel.disabled = true;
        }

        function llenarSelect(id, data) {
            const sel = document.getElementById(id);
            sel.innerHTML = `<option value="">-- Seleccionar --</option>`; // Limpiar "Cargando..."
            data.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item.nombre;
                sel.appendChild(opt);
            });
            sel.disabled = false;
        }

        function seleccionarDepartamento(id) {
            document.getElementById('departamento_id').value = id;
            document.getElementById('departamento_id').dispatchEvent(new Event('change'));
            document.getElementById('inicio').scrollIntoView({
                behavior: 'smooth'
            });
        }

        // Buscador Photon
        function setupSearch() {
            const box = document.getElementById("search-box");
            const list = document.getElementById("search-results");

            box.addEventListener("input", async function() {
                const q = this.value.trim();
                list.innerHTML = "";
                if (q.length < 3) {
                    list.style.display = "none";
                    return;
                }

                const url =
                    `https://photon.komoot.io/api/?q=${encodeURIComponent(q)}&bbox=-69.6459,-22.898,-57.453,-9.676&limit=5`;
                const res = await fetch(url).then(r => r.json());

                if (!res.features.length) {
                    list.style.display = "none";
                    return;
                }

                res.features.forEach(f => {
                    const li = document.createElement("li");
                    li.className = "list-group-item list-group-item-action cursor-pointer";
                    li.textContent = f.properties.name + (f.properties.city ? `, ${f.properties.city}` :
                        "");
                    li.onclick = () => {
                        const [lng, lat] = f.geometry.coordinates;
                        map.setView([lat, lng], 14);
                        L.marker([lat, lng]).addTo(map).bindPopup(f.properties.name).openPopup();
                        list.style.display = "none";
                        box.value = li.textContent;
                    };
                    list.appendChild(li);
                });
                list.style.display = "block";
            });

            document.addEventListener("click", (e) => {
                if (!list.contains(e.target) && e.target !== box) list.style.display = "none";
            });
        }
    </script>
</body>

</html>
