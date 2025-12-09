<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Límites - Gestión de Biodiversidad</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link rel="icon" href="{{ url('img/logo3.png') }}" type="image/png">

    <style>
        :root {
            --primary: #0077c0;
            --primary-dark: #005a94;
            --accent: #2ecc71;
            --text-dark: #2c3e50;
            --bg-light: #f4f6f8;
            --glass-bg: rgba(255, 255, 255, 0.95);
            --glass-border: 1px solid rgba(255, 255, 255, 0.3);
            --shadow-soft: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* --- NAVBAR --- */
        .navbar-custom {
            background: linear-gradient(90deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 0.8rem 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            z-index: 1030;
        }

        .navbar-brand img {
            height: 45px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            transition: all 0.3s;
            padding: 8px 16px !important;
            border-radius: 4px;
        }

        .nav-link:hover {
            color: #fff !important;
            background: rgba(255,255,255,0.15);
            transform: translateY(-1px);
        }

        /* --- SEARCH BAR --- */
        .search-wrapper { position: relative; }
        .search-input {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            border-radius: 20px;
            padding: 6px 15px 6px 35px;
            font-size: 0.9rem;
            width: 220px;
            transition: all 0.3s;
        }

        .search-input:focus {
            background: #fff;
            color: #333;
            width: 280px;
            outline: none;
            box-shadow: 0 0 10px rgba(255,255,255,0.2);
        }

        .search-input::placeholder { color: rgba(255,255,255,0.7); }
        .search-icon {
            position: absolute;
            left: 12px; top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.7);
            font-size: 0.9rem;
            pointer-events: none;
        }
        
        .search-input:focus + .search-icon { color: var(--primary); }

        /* --- MAPA --- */
        .map-wrapper {
            position: relative;
            height: 80vh;
            width: 100%;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        #map { height: 100%; width: 100%; z-index: 1; }

        /* --- PANEL FLOTANTE DE FILTROS --- */
        .floating-filter-panel {
            position: absolute;
            top: 20px; left: 20px;
            width: 350px;
            background: var(--glass-bg);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: var(--glass-border);
            border-radius: 18px;
            padding: 40px;
            z-index: 1000;
            box-shadow: var(--shadow-soft);
            transition: transform 0.3s ease;
        }

        .filter-header {
            display: flex; align-items: center;
            margin-bottom: 15px;
            color: var(--primary);
            font-weight: 700;
            font-size: 1.1rem;
            border-bottom: 3px solid rgba(0,0,0,0.05);
            padding-bottom: 10px;
        }

        .form-floating > .form-select {
            height: 65px; min-height: 65px;
            border-radius: 8px; border: 1px solid #e0e0e0;
            background-color: #fff; font-size: 0.9rem;
        }

        .form-floating > label { padding: 12px 12px; }
        .btn-reset {
            width: 100%; border-radius: 8px; font-weight: 600;
            background-color: #fff; border: 1px solid #dc3545; color: #dc3545;
            transition: all 0.3s;
        }

        .btn-reset:hover { background-color: #dc3545; color: #fff; }

        /* --- CONTROLES DE MAPA --- */
        .custom-map-controls {
            position: absolute; bottom: 30px; right: 20px;
            display: flex; flex-direction: column; gap: 10px; z-index: 1000;
        }

        .map-btn {
            width: 45px; height: 45px;
            background: white; border: none; border-radius: 50%;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            color: #555; font-size: 1.1rem;
            transition: all 0.2s;
            display: flex; align-items: center; justify-content: center;
        }

        .map-btn:hover {
            background: var(--primary); color: white; transform: scale(1.7);
        }

        .layers-popover {
            position: absolute; top: 20px; right: 20px;
            background: white; border-radius: 10px; padding: 15px;
            box-shadow: var(--shadow-soft); z-index: 1000;
            width: 200px; display: none; border-left: 4px solid var(--primary);
        }

        .layers-popover.active { display: block; animation: fadeIn 0.3s; }

        /* --- TARJETA INFO FLOTANTE (Overlay) --- */
        .info-card-overlay {
            position: absolute; bottom: 30px; left: 20px;
            width: 350px; max-height: 400px; overflow-y: auto;
            background: white; border-radius: 12px;
            box-shadow: var(--shadow-soft); z-index: 1000;
            display: none; animation: slideUp 0.4s ease-out;
        }
        
        .info-card-header {
            background: var(--primary); color: white; padding: 15px;
            position: sticky; top: 0;
        }

        .info-card-body { padding: 15px; }

        /* --- ESTILOS DE PESTAÑAS --- */
        .nav-pills .nav-link {
            color: #555; background-color: var(--primary);
            transition: all 0.3s ease; border: 1px solid #eeeeee;
        }

        .nav-pills .nav-link:hover {
            transform: translateY(-2px); background-color: #61a4e7;
        }

        .nav-pills .nav-link.active {
            background-color: var(--primary); color: white !important;
            box-shadow: 0 4px 10px rgba(0, 119, 192, 0.3); border-color: var(--primary);
        }

        /* --- CARDS EXPLORACIÓN --- */
        .dept-card {
            border: none; border-radius: 15px; background: #ffffff;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            transition: all 0.3s ease; overflow: hidden; height: 100%;
        }

        .dept-card:hover {
            transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,75,192,0.15);
        }

        .dept-img-container {
            height: 180px; overflow: hidden; position: relative;
        }

        .dept-img-container img {
            width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;
        }

        .dept-card:hover .dept-img-container img { transform: scale(1.1); }
        .dept-body { padding: 1.5rem; }
        
        .section-title {
            font-weight: 800; color: var(--text-dark);
            position: relative; display: inline-block; margin-bottom: 2rem;
        }

        .section-title::after {
            content: ''; position: absolute; bottom: -10px; left: 0;
            width: 50px; height: 4px; background: var(--accent); border-radius: 2px;
        }

        .btn-outline-custom {
            color: var(--primary); border: 2px solid var(--primary);
            border-radius: 50px; padding: 8px 20px; font-weight: 600;
            transition: all 0.3s; background: transparent; width: 100%;
        }

        .btn-outline-custom:hover {
            background: var(--primary); color: white;
            box-shadow: 0 5px 15px rgba(0,119,192,0.3);
        }

        /* --- FOOTER (Estilo Imagen) --- */
        footer {
            background-color: var(--primary); 
            color: white;
            padding-top: 60px;
            padding-bottom: 20px;
            font-size: 0.9rem;
            position: relative;
        }

        .footer-title {
            font-weight: 700;
            text-transform: uppercase;
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
            padding-bottom: 8px;
            margin-bottom: 20px;
            display: inline-block;
            font-size: 1rem;
        }

        .contact-list li {
            margin-bottom: 12px;
            display: flex;
            align-items: flex-start;
        }

        .contact-list i {
            margin-right: 10px;
            color: #ffc107; 
            font-size: 1.1rem;
            margin-top: 2px;
        }

        .social-box {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            border: 1px solid white;
            border-radius: 6px;
            color: white;
            margin-right: 10px;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 1.2rem;
        }

        .social-box:hover {
            background-color: white;
            color: var(--primary);
            transform: translateY(-3px);
        }

        .footer-logo {
            max-height: 80px;
            margin-bottom: 20px;
        }

        .copyright-line {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: 40px;
            padding-top: 20px;
            text-align: center;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Botón Flotante WhatsApp */
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #25d366;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            z-index: 1050;
            transition: transform 0.3s;
            text-decoration: none;
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
            color: white;
        }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        @media (max-width: 768px) {
            .map-wrapper { height: 60vh; }
            .floating-filter-panel { 
                position: relative; top: 0; left: 0; width: 100%; border-radius: 0;
            }

            .info-card-overlay { width: 90%; left: 5%; bottom: 80px; }
            .footer-col { margin-bottom: 30px; text-align: center; }
            .contact-list li { justify-content: center; }
            .footer-title { display: inline-block; }
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-custom navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ url('img/logo3.png') }}" alt="Gobernación">
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/areas-protegidas#inicio') }}">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#">Límites</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/areas-protegidas#areas') }}">Áreas Protegidas</a></li>
                    <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                        <div class="search-wrapper">
                            <input type="text" id="search-box" class="search-input" placeholder="Buscar zonas...">
                            <i class="fas fa-search search-icon"></i>
                            <ul id="search-results" class="list-group position-absolute w-100 mt-1 shadow-lg" style="display:none; z-index:1050;"></ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="position-relative">
        
        <div class="map-wrapper" id="inicio">
            <div id="map"></div>
        </div>

        <div class="floating-filter-panel">
            <div class="filter-header">
                <i class="bi bi-funnel-fill me-2"></i> Filtrar Territorio
            </div>
            <div class="vstack gap-3">
                <div class="form-floating">
                    <select class="form-select" id="departamento_id">
                        <option value="">Seleccionar...</option>
                        @foreach ($departamentos as $d)
                            <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                        @endforeach
                    </select>
                    <label for="departamento_id">Departamento</label>
                </div>
                <div class="form-floating">
                    <select class="form-select" id="provincia_id" disabled>
                        <option value="">Esperando selección...</option>
                    </select>
                    <label for="provincia_id">Provincia</label>
                </div>
                <div class="form-floating">
                    <select class="form-select" id="municipio_id" disabled>
                        <option value="">Esperando selección...</option>
                    </select>
                    <label for="municipio_id">Municipio</label>
                </div>
                <button class="btn btn-reset mt-2" onclick="resetSeleccion()">
                    <i class="bi bi-arrow-counterclockwise"></i> Restablecer Filtros
                </button>
            </div>
        </div>

        <div class="custom-map-controls">
            <button class="map-btn" onclick="toggleLayers()" title="Capas"><i class="fas fa-layer-group"></i></button>
            <button class="map-btn" onclick="centerMap()" title="Centrar"><i class="fas fa-crosshairs"></i></button>
            <button class="map-btn" onclick="map.zoomIn()" title="Acercar"><i class="fas fa-plus"></i></button>
            <button class="map-btn" onclick="map.zoomOut()" title="Alejar"><i class="fas fa-minus"></i></button>
        </div>

        <div id="layers-popover" class="layers-popover">
            <h6 class="fw-bold mb-3 small text-uppercase text-muted">Tipo de Mapa</h6>
            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="basemap" id="radioHibrido" value="hibrido" checked>
                <label class="form-check-label" for="radioHibrido">Satélite</label>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="basemap" id="radioOSM" value="osm">
                <label class="form-check-label" for="radioOSM">Calles (OSM)</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="basemap" id="radioTopo" value="topo">
                <label class="form-check-label" for="radioTopo">Topográfico</label>
            </div>
        </div>

        <div id="info-overlay" class="info-card-overlay">
            <div class="info-card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold" id="overlay-title">Título</h5>
                <button type="button" class="btn-close btn-close-white" onclick="cerrarOverlay()"></button>
            </div>
            <div class="info-card-body">
                <p class="text-muted small" id="overlay-desc">Descripción...</p>
                <div id="overlay-media" class="row g-2"></div>
            </div>
        </div>
    </div>

    <section class="container py-5">
        <div class="text-center mb-5">
            <h2 class="section-title">Exploración Territorial</h2>
            <p class="text-muted">Navega a través de las diferentes divisiones administrativas.</p>
        </div>

        <ul class="nav nav-pills justify-content-center mb-5 gap-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-pill px-4 py-2" id="pills-dept-tab" 
                    data-bs-toggle="pill" data-bs-target="#pills-dept" type="button" role="tab">
                    <i class="fas fa-map me-2"></i> Departamentos
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill px-4 py-2" id="pills-prov-tab" 
                    data-bs-toggle="pill" data-bs-target="#pills-prov" type="button" role="tab">
                    <i class="fas fa-map-signs me-2"></i> Provincias
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill px-4 py-2" id="pills-muni-tab" 
                    data-bs-toggle="pill" data-bs-target="#pills-muni" type="button" role="tab">
                    <i class="fas fa-city me-2"></i> Municipios
                </button>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">
            
            <div class="tab-pane fade show active" id="pills-dept" role="tabpanel">
                <div class="row g-4">
                    @forelse ($departamentos as $depto)
                        <div class="col-md-6 col-lg-4">
                            <div class="dept-card">
                                <div class="dept-img-container">
                                    @if ($depto->media->count())
                                        <img src="{{ Storage::url($depto->media->first()->archivo) }}" alt="{{ $depto->nombre }}">
                                    @else
                                        <img src="{{ asset('img/no-image.jpg') }}" alt="Sin imagen" style="filter: grayscale(100%); opacity: 0.5;">
                                    @endif
                                    <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                        <h5 class="text-white mb-0 fw-bold">{{ $depto->nombre }}</h5>
                                        <small class="text-white-50">Departamento</small>
                                    </div>
                                </div>
                                <div class="dept-body">
                                    <p class="text-muted small mb-4" style="height: 40px; overflow: hidden;">
                                        {{ Str::limit($depto->descripcion, 90, '...') }}
                                    </p>
                                    <button class="btn-outline-custom" onclick="seleccionarDepartamento({{ $depto->id }})">
                                        <i class="bi bi-geo-alt-fill me-2"></i> Ver Territorio
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 text-muted">No hay departamentos disponibles.</div>
                    @endforelse
                </div>
            </div>

            <div class="tab-pane fade" id="pills-prov" role="tabpanel">
                <div class="row g-4">
                    @if(isset($provincias))
                        @forelse ($provincias as $prov)
                            <div class="col-md-6 col-lg-4">
                                <div class="dept-card">
                                    <div class="dept-img-container">
                                        @if ($prov->media->count())
                                            <img src="{{ Storage::url($prov->media->first()->archivo) }}" alt="{{ $prov->nombre }}">
                                        @else
                                            <img src="{{ asset('img/no-image.jpg') }}" alt="Sin imagen" style="filter: grayscale(100%); opacity: 0.5;">
                                        @endif
                                        <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                            <h5 class="text-white mb-0 fw-bold">{{ $prov->nombre }}</h5>
                                            <small class="text-white-50">Provincia</small>
                                        </div>
                                    </div>
                                    <div class="dept-body">
                                        <p class="text-muted small mb-4" style="height: 40px; overflow: hidden;">
                                            {{ Str::limit($prov->descripcion, 90, '...') }}
                                        </p>
                                        <button class="btn-outline-custom" onclick="seleccionarProvincia({{ $prov->id }})">
                                            <i class="bi bi-geo-alt-fill me-2"></i> Ver Territorio
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5 text-muted">No hay provincias cargadas.</div>
                        @endforelse
                    @else
                        <div class="col-12 text-center py-5 text-muted">Datos de provincias no disponibles.</div>
                    @endif
                </div>
            </div>

            <div class="tab-pane fade" id="pills-muni" role="tabpanel">
                <div class="row g-4">
                    @if(isset($municipios))
                        @forelse ($municipios as $muni)
                            <div class="col-md-6 col-lg-4">
                                <div class="dept-card">
                                    <div class="dept-img-container">
                                        @if ($muni->media->count())
                                            <img src="{{ Storage::url($muni->media->first()->archivo) }}" alt="{{ $muni->nombre }}">
                                        @else
                                            <img src="{{ asset('img/no-image.jpg') }}" alt="Sin imagen" style="filter: grayscale(100%); opacity: 0.5;">
                                        @endif
                                        <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                            <h5 class="text-white mb-0 fw-bold">{{ $muni->nombre }}</h5>
                                            <small class="text-white-50">Municipio</small>
                                        </div>
                                    </div>
                                    <div class="dept-body">
                                        <p class="text-muted small mb-4" style="height: 40px; overflow: hidden;">
                                            {{ Str::limit($muni->descripcion, 90, '...') }}
                                        </p>
                                        <button class="btn-outline-custom" onclick="seleccionarMunicipio({{ $muni->id }})">
                                            <i class="bi bi-geo-alt-fill me-2"></i> Ver Territorio
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5 text-muted">No hay municipios cargados.</div>
                        @endforelse
                    @else
                        <div class="col-12 text-center py-5 text-muted">Datos de municipios no disponibles.</div>
                    @endif
                </div>
            </div>

        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 footer-col mb-4 mb-lg-0">
                    <h5 class="footer-title">INFORMACIÓN DE CONTACTO</h5>
                    <ul class="list-unstyled contact-list">
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

                <div class="col-lg-4 footer-col mb-4 mb-lg-0 text-center d-flex flex-column justify-content-center">
                    <img src="{{ url('img/logo3.png') }}" alt="Cochabamba Unir Trabajar y Crecer" class="img-fluid footer-logo mx-auto">
                </div>

                <div class="col-lg-4 footer-col text-lg-end text-center">
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
            <i class="fab fa-whatsapp"></i>
        </a>
    </footer>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let map;
        let currentLayer;
        let currentGeoLayer = null;

        const tileLayers = {
            osm: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: 'OSM' }),
            hibrido: L.layerGroup([
                L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: 'Esri' }),
                L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Transportation/MapServer/tile/{z}/{y}/{x}')
            ]),
            topo: L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', { attribution: 'OpenTopoMap' })
        };

        document.addEventListener('DOMContentLoaded', function() {
            map = L.map('map', { zoomControl: false, minZoom: 5 }).setView([-17.3895, -66.1568], 13);
            currentLayer = tileLayers.hibrido;
            currentLayer.addTo(map);

            document.querySelectorAll('input[name="basemap"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    if (tileLayers[this.value]) {
                        map.removeLayer(currentLayer);
                        currentLayer = tileLayers[this.value];
                        currentLayer.addTo(map);
                    }
                });
            });

            setupSearch();
        });

        function toggleLayers() { document.getElementById('layers-popover').classList.toggle('active'); }
        function centerMap() { map.setView([-17.3895, -66.1568], 13); }
        function cerrarOverlay() { document.getElementById('info-overlay').style.display = 'none'; }

        function dibujarGeometria(geojson) {
            if (currentGeoLayer) map.removeLayer(currentGeoLayer);
            if (!geojson) return;
            currentGeoLayer = L.geoJSON(geojson, {
                style: { color: '#0077c0', weight: 4, fillColor: '#0077c0', fillOpacity: 0.5 }
            }).addTo(map);
            map.fitBounds(currentGeoLayer.getBounds(), { padding: [50, 50] });
        }

        // Filtros Dropdown
        document.getElementById('departamento_id').addEventListener('change', async function() {
            const id = this.value;
            resetSelect('provincia_id'); resetSelect('municipio_id');
            if (!id) { cerrarOverlay(); return; }
            try {
                const detalle = await fetch(`/limites/detalle/departamento/${id}`).then(r => r.json());
                mostrarOverlay(detalle); dibujarGeometria(detalle.geometria);
                document.getElementById('provincia_id').innerHTML = '<option>Cargando...</option>';
                const provincias = await fetch(`/limites/provincias/${id}`).then(r => r.json());
                llenarSelect('provincia_id', provincias);
            } catch (e) { console.error(e); }
        });

        document.getElementById('provincia_id').addEventListener('change', async function() {
            const id = this.value;
            resetSelect('municipio_id');
            if (!id) { cerrarOverlay(); return; }
            try {
                const detalle = await fetch(`/limites/detalle/provincia/${id}`).then(r => r.json());
                mostrarOverlay(detalle); dibujarGeometria(detalle.geometria);
                document.getElementById('municipio_id').innerHTML = '<option>Cargando...</option>';
                const municipios = await fetch(`/limites/municipios/${id}`).then(r => r.json());
                llenarSelect('municipio_id', municipios);
            } catch (e) { console.error(e); }
        });

        document.getElementById('municipio_id').addEventListener('change', async function() {
            const id = this.value;
            if (!id) { cerrarOverlay(); return; }
            try {
                const detalle = await fetch(`/limites/detalle/municipio/${id}`).then(r => r.json());
                mostrarOverlay(detalle); dibujarGeometria(detalle.geometria);
            } catch (e) { console.error(e); }
        });

        // Helpers
        function mostrarOverlay(data) {
            document.getElementById('overlay-title').textContent = data.nombre;
            document.getElementById('overlay-desc').textContent = data.descripcion || 'Información no disponible.';
            const mediaDiv = document.getElementById('overlay-media'); mediaDiv.innerHTML = '';
            if (data.media && data.media.length > 0) {
                data.media.forEach(item => {
                    const col = document.createElement('div'); col.className = 'col-4';
                    let content = '';
                    if (item.tipo && item.tipo.includes('image')) {
                        content = `<img src="/storage/${item.archivo}" class="img-fluid rounded border" style="height:80px; width:100%; object-fit:cover; cursor:pointer">`;
                    }
                    col.innerHTML = content; mediaDiv.appendChild(col);
                });
            }
            document.getElementById('info-overlay').style.display = 'block';
        }

        function resetSeleccion() {
            document.getElementById('departamento_id').value = "";
            resetSelect('provincia_id'); resetSelect('municipio_id');
            cerrarOverlay();
            if (currentGeoLayer) map.removeLayer(currentGeoLayer);
            centerMap();
        }

        function resetSelect(id) {
            const sel = document.getElementById(id);
            sel.innerHTML = `<option value="">Seleccionar...</option>`; sel.disabled = true;
        }

        function llenarSelect(id, data) {
            const sel = document.getElementById(id);
            sel.innerHTML = `<option value="">Seleccionar...</option>`;
            data.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id; opt.textContent = item.nombre;
                sel.appendChild(opt);
            });
            sel.disabled = false;
        }

        function seleccionarDepartamento(id) {
            document.getElementById('departamento_id').value = id;
            document.getElementById('departamento_id').dispatchEvent(new Event('change'));
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // --- FUNCIONES PARA LAS NUEVAS TABS ---
        async function seleccionarProvincia(id) {
            try {
                const detalle = await fetch(`/limites/detalle/provincia/${id}`).then(r => r.json());
                mostrarOverlay(detalle);
                dibujarGeometria(detalle.geometria);
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
                const sel = document.getElementById('provincia_id');
                sel.innerHTML = `<option value="${id}" selected>${detalle.nombre}</option>`;
                sel.disabled = false;
            } catch (e) { console.error(e); }
        }

        async function seleccionarMunicipio(id) {
            try {
                const detalle = await fetch(`/limites/detalle/municipio/${id}`).then(r => r.json());
                mostrarOverlay(detalle);
                dibujarGeometria(detalle.geometria);
                window.scrollTo({ top: 0, behavior: 'smooth' });

                const sel = document.getElementById('municipio_id');
                sel.innerHTML = `<option value="${id}" selected>${detalle.nombre}</option>`;
                sel.disabled = false;
            } catch (e) { console.error(e); }
        }

        // Buscador
        function setupSearch() {
            const box = document.getElementById("search-box");
            const list = document.getElementById("search-results");
            box.addEventListener("input", async function() {
                const q = this.value.trim();
                list.innerHTML = "";
                if (q.length < 3) { list.style.display = "none"; return; }
                const url = `https://photon.komoot.io/api/?q=${encodeURIComponent(q)}&bbox=-69.6459,-22.898,-57.453,-9.676&limit=5`;
                try {
                    const res = await fetch(url).then(r => r.json());
                    if (!res.features.length) { list.style.display = "none"; return; }
                    res.features.forEach(f => {
                        const li = document.createElement("li");
                        li.className = "list-group-item list-group-item-action small";
                        li.style.cursor = "pointer";
                        li.innerHTML = `<strong>${f.properties.name}</strong> <span class="text-muted ms-1">${f.properties.city || ''}</span>`;
                        li.onclick = () => {
                            const [lng, lat] = f.geometry.coordinates;
                            map.setView([lat, lng], 14);
                            L.marker([lat, lng]).addTo(map).bindPopup(f.properties.name).openPopup();
                            list.style.display = "none";
                            box.value = f.properties.name;
                        };
                        list.appendChild(li);
                    });
                    list.style.display = "block";
                } catch(e) { console.error(e); }
            });
            document.addEventListener("click", (e) => {
                if (!list.contains(e.target) && e.target !== box) list.style.display = "none";
            });
        }
    </script>
</body>
</html>