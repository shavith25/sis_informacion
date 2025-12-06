@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <style>
        .navbar,
        .main-header,
        nav.navbar {
            background-color: #2f55d4 !important;
            background: linear-gradient(135deg, #2f55d4 0%, #1a3aa8 100%) !important;
            color: #ffffff !important;
            border-bottom: none !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15) !important;
        }

        .navbar .nav-link,
        .navbar .navbar-brand,
        .navbar i,
        .navbar span,
        .navbar .btn,
        .main-header .nav-link,
        .main-header i {
            color: #ffffff !important;
        }

        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.5) !important;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
        }

        /* --- 1. LAYOUT PRINCIPAL --- */
        html,
        body {
            overflow: hidden;
            height: 100%;
            margin: 0;
            background-color: #f8f9fa;
        }

        .container-mapa {
            height: calc(100vh - 250px);
            display: flex;
            flex-direction: column;
            padding: 20px;
            overflow: hidden;
        }

        .header-fixed {
            flex-shrink: 0;
        }

        .content-row {
            flex: 1;
            display: flex;
            min-height: 0;
            margin-top: 1rem;
        }

        /* --- 2. COLUMNAS --- */
        .col-map-container,
        .col-table-container {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .card-map-wrapper {
            flex: 1;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            overflow: hidden;
            position: relative;
        }

        #map {
            height: 100%;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }

        .table-scroll-wrapper {
            flex: 1;
            overflow-y: auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 15px;
        }

        /* --- 3. ESTILOS VISUALES --- */
        .modal-dialog {
            margin: 1.75rem auto;
        }

        /* Forzamos altura máxima en el dialog */
        .modal-dialog.modal-lg {
            max-height: 90vh;
            display: flex;
            align-items: center;
        }

        /* El contenido del modal toma la altura máxima disponible */
        .modal-content {
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .modal-header,
        .modal-footer {
            flex-shrink: 0;
            background-color: #ffffff;
            z-index: 2;
        }

        /* El cuerpo es el único que hace scroll */
        .modal-body {
            flex: 1 1 auto;
            overflow-y: auto;
            padding: 2rem;
            background-color: #fcfcfc;

            /* Estilo scrollbar Firefox */
            scrollbar-width: thin;
            scrollbar-color: #cbd5e0 #f1f1f1;
        }

        /* Estilo scrollbar Webkit (Chrome, Edge, Safari) */
        .modal-body::-webkit-scrollbar {
            width: 12px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background-color: #cbd5e0;
            border-radius: 10px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background-color: #a0aec0;
        }

        .modal-header {
            border-bottom: 1px solid #f0f0f0;
            background-color: #ffffff;
            border-radius: 12px 12px 0 0;
            padding: 1.2rem 2rem;
        }

        .modal-title {
            font-weight: 600;
            color: #2c3e50;
        }

        /* --- 4. FORMULARIO --- */
        .form-label {
            font-weight: 800;
            color: #5a6b7c;
            margin-bottom: 0.4rem;
            font-size: 0.9rem;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #e0e6ed;
            padding: 0.6rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s;
            background-color: #ffffff;
        }

        .form-control:focus {
            border-color: #2f55d4;
            box-shadow: 0 0 0 3px rgba(47, 85, 212, 0.1);
            background-color: #fff;
        }

        .form-section-title {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #8898aa;
            margin-bottom: 1rem;
            margin-top: 1.5rem;
            font-weight: 700;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .form-control[type="file"] {
            padding: 0.5rem;
            background-color: #f8f9fa;
            border: 1px dashed #cbd5e0;
        }

        .form-control[type="file"]:hover {
            border-color: #2f55d4;
        }

        /* --- 5. BOTONES --- */
        .btn {
            border-radius: 6px;
            font-weight: 500;
            padding: 0.5rem 1.2rem;
            transition: all 0.2s;
        }

        .btn-secondary {
            background-color: #e2e6ea;
            border-color: #e2e6ea;
            color: #4a5568;
        }

        .btn-secondary:hover {
            background-color: #ced4da;
            color: #2d3748;
        }

        /* CLASE PERSONALIZADA AZUL (Para usar en cualquier botón) */
        .btn-custom-blue,
        .btn-primary {
            background-color: #2f55d4 !important;
            border-color: #2f55d4 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 6px rgba(47, 85, 212, 0.2);
        }

        .btn-custom-blue:hover,
        .btn-primary:hover {
            background-color: #2442a8 !important;
            border-color: #2442a8 !important;
            transform: translateY(-1px);
            box-shadow: 0 6px 8px rgba(47, 85, 212, 0.25);
        }

        .btn-action-sm {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: none !important;
            text-decoration: none;
            margin-right: 4px;
            transition: transform 0.1s;
        }

        .btn-action-sm:active {
            transform: scale(0.95);
        }

        .btn-action-sm.btn-info {
            background-color: #0dcaf0 !important;
            color: white !important;
        }

        .btn-action-sm i {
            font-size: 14px;
        }

        .preview-container {
            background: white;
            padding: 5px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border: 1px solid #eee;
        }

        .btn-remove-preview {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 24px;
            height: 24px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: #ff4757;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s;
            cursor: pointer;
            z-index: 10;
        }

        .btn-remove-preview:hover {
            transform: scale(1.1);
            background-color: #ff6b81;
        }

        .btn-remove-preview i {
            color: white;
            font-size: 10px;
        }

        .legend-container {
            z-index: 1000;
        }

        .legend-color {
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 4px;
            margin-right: 8px;
        }

        #loader-container {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: none;
            align-items: center;
            justify-content: center;
            background-color: rgba(255, 255, 255, 0.9);
            z-index: 1050;
            backdrop-filter: blur(2px);
        }
    </style>

    <div class="container-mapa">
        <div class="header-fixed">
            <div class="section-header bg-primary text-white p-3 rounded mb-3 shadow-sm align-items-center d-flex"
                style="background: linear-gradient(135deg, #2f55d4 0%, #1a3aa8 100%) !important;">
                <h3 class="page__heading mb-0 fs-4"><i class="fas fa-map-marked-alt me-2"></i> Mapa de Zonas</h3>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-2">
                <div><a href="{{ route('zonas.index') }}" class="btn btn-outline-secondary bg-white"><i
                            class="fas fa-arrow-left me-1"></i> Volver al listado</a>
                </div>

                <div class="legend-container bg-white p-2 rounded shadow-sm border d-flex gap-3 align-items-center">
                    <div class="d-flex align-items-center"><span class="legend-color"
                            style="background-color: #3388ff; opacity: 0.6;"></span><span
                            class="small fw-bold text-secondary">Polígonos</span>
                    </div>

                    <div class="d-flex align-items-center"><span class="legend-color"
                            style="background-color: #ff3333;"></span><span
                            class="small fw-bold text-secondary">Marcadores</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row content-row">
            <div class="col-lg-5 col-md-5 col-map-container mb-3">
                <div class="card-map-wrapper">
                    <div id="map"></div>
                </div>
            </div>
            <div class="col-lg-7 col-md-7 col-table-container mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="text-dark fw-bold mb-0" style="font-weight: 800; color: #343a40 !important;">
                        <i class="fas fa-list-ul me-2 text-primary"></i> Resumen de Zonas
                    </h5>
                </div>
                <div class="table-scroll-wrapper">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="position: sticky; top: 0; background: #f8f9fa; z-index: 10;">
                            <tr>
                                <th class="text-dark fw-bold small text-uppercase">Nombre</th>
                                <th class="text-dark fw-bold small text-uppercase">Área</th>
                                <th class="text-dark fw-bold small text-uppercase">Tipo</th>
                                <th class="text-dark fw-bold small text-uppercase">Estado</th>
                                <th class="text-dark fw-bold small text-uppercase text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($zonas as $zona)
                                @php
                                    $coordenadasData = $zona->ultimoHistorial
                                        ? (is_string($zona->ultimoHistorial->coordenadas)
                                            ? json_decode($zona->ultimoHistorial->coordenadas, true)
                                            : $zona->ultimoHistorial->coordenadas)
                                        : null;
                                    $tipos = $coordenadasData ? array_column($coordenadasData, 'tipo') : [];
                                @endphp
                                <tr class="zona-row" data-zona-id="{{ $zona->id }}" style="cursor: pointer;">
                                    <td class="fw-bold text-dark">{{ $zona->nombre }}</td>
                                    <td class="text-muted small">{{ $zona->area->area ?? 'N/A' }}</td>
                                    <td><span
                                            class="badge bg-light text-dark border">{{ !empty($tipos) ? (in_array('poligono', $tipos) ? 'Polígono' : 'Marcador') : 'Sin datos' }}</span>
                                    </td>
                                    <td><span
                                            class="badge bg-{{ $zona->estado ? 'success' : 'secondary' }} rounded-pill">{{ $zona->estado ? 'Activo' : 'Inactivo' }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('zonas.detalle', $zona) }}" title="Ver Detalle"
                                            class="btn btn-info btn-sm btn-action-sm zona-hover-btn"
                                            data-zona-id="{{ $zona->id }}"><i class="fas fa-eye"></i>
                                        </a>

                                        <button type="button" title="Ver Historial de Datos"
                                            class="btn btn-secondary btn-sm btn-action-sm btn-ver-datos"
                                            data-zona-id="{{ $zona->id }}"><i class="fas fa-history"></i>
                                        </button>

                                        <button type="button" title="Eliminar"
                                            class="btn btn-danger btn-sm btn-action-sm btn-eliminar-zona"
                                            data-id="{{ $zona->id }}" data-nombre="{{ $zona->nombre }}"><i
                                                class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL CREAR/EDITAR DATOS --}}
    <div class="modal fade" id="modalCrearDatos" tabindex="-1" aria-labelledby="modalCrearDatosLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form id="formCrearDatos" method="POST" action="{{ route('datos.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="zona_id" id="form_zona_id" value="">
                <input type="hidden" name="dato_id" id="dato_id" value="">
                <input type="hidden" name="imagenes_eliminar" id="imagenes_eliminar" value="[]">
                <input type="hidden" name="medios_eliminar" id="medios_eliminar" value="[]">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCrearDatosLabel">
                            <i class="fas fa-edit text-primary me-2"></i> Registrar / Editar Datos
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div id="form-loader"
                            style="display:none; position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.8); z-index:10; flex-direction:column; align-items:center; justify-content:center; backdrop-filter:blur(2px);">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p class="mt-3 fw-bold text-muted">Cargando información...</p>
                        </div>

                        <div class="form-section-title mt-0"><i class="fas fa-info-circle me-1"></i> Información General
                        </div>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Flora y Fauna</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-leaf text-success"></i>
                                    </span>
                                    <textarea class="form-control" name="flora_fauna" id="flora_fauna" style="height: 80px;" required
                                        placeholder="Ej: Pinos, Osos..."></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Extensión</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-ruler-combined text-primary"></i>
                                    </span>
                                    <textarea class="form-control" name="extension" id="extension" style="height: 80px;" required
                                        placeholder="Ej: 500 Hectáreas"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Población</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-users text-info"></i>
                                    </span>
                                    <textarea class="form-control" name="poblacion" id="poblacion" style="height: 80px;" required
                                        placeholder="Ej: 1500 habitantes"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12"><label class="form-label">Provincia</label>
                                <div class="input-group"><span class="input-group-text bg-light"><i
                                            class="fas fa-map-marker-alt text-danger"></i></span><input type="text"
                                        class="form-control" name="provincia" id="provincia" required
                                        placeholder="Ej: Cercado"></div>
                            </div>
                        </div>

                        <div class="form-section-title"><i class="fas fa-exclamation-triangle me-1"></i> Estado y Otros
                        </div>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Especies en Peligro</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-paw text-warning"></i>
                                    </span>
                                    <textarea class="form-control" name="especies_peligro" id="especies_peligro" style="height: 80px;" required
                                        placeholder="Ej: Cóndor Andino"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Otros Datos</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-clipboard-list text-secondary"></i>
                                    </span>
                                    <textarea class="form-control" name="otros_datos" id="otros_datos" style="height: 80px;" required
                                        placeholder="Información extra..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-section-title"><i class="fas fa-photo-video me-1"></i> Multimedia</div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label"><i class="far fa-images me-1"></i> Imágenes</label>
                                <input type="file" class="form-control" name="imagenes[]" id="imagenes" multiple
                                    accept="image/*">
                                <div class="mt-2 p-2 bg-light rounded d-flex flex-wrap gap-2 border"
                                    style="min-height: 60px;" id="preview-container-wrapper">
                                    <div id="preview-existing" class="d-flex flex-wrap gap-2"></div>
                                    <div id="preview" class="d-flex flex-wrap gap-2"></div>
                                    <div class="text-muted small w-100 text-center pt-2" id="empty-msg-img">Sin imágenes
                                        seleccionadas
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"><label class="form-label"><i class="fas fa-video me-1"></i>
                                    Videos</label><input type="file" class="form-control" name="videos[]"
                                    id="videos" multiple accept="video/*">
                                <div id="preview-videos-existing" class="d-flex flex-wrap gap-2 mt-2"></div>
                            </div>
                            <div class="col-md-6"><label class="form-label"><i class="far fa-file-pdf me-1"></i>
                                    Documentos</label><input type="file" class="form-control" name="documentos[]"
                                    id="documentos" multiple accept=".pdf,.doc,.docx">
                                <div id="preview-documentos-existing" class="d-flex flex-wrap gap-2 mt-2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            id="btn-cancelar">Cancelar</button>
                        <button type="submit" class="btn btn-custom-blue"><i class="fas fa-save me-1"></i> Guardar
                            Datos</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL VER DATOS --}}
    <div class="modal fade" id="modalVerDatos" tabindex="-1" aria-labelledby="modalVerDatosLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalVerDatosLabel"><i class="fas fa-eye text-info me-2"></i> Detalles de
                        la Zona</h5>
                </div>

                <div class="modal-body">
                    <div id="view-loader"
                        style="display:none; position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.8); z-index:10; flex-direction:column; align-items:center; justify-content:center; backdrop-filter:blur(2px);">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-3 fw-bold text-muted">Cargando detalles...</p>
                    </div>

                    <div id="datos-content">
                        <div class="form-section-title mt-0"><i class="fas fa-info-circle me-1"></i> Información General
                        </div>
                        <p><strong>Flora y Fauna:</strong> <span id="view_flora_fauna"></span></p>
                        <p><strong>Extensión:</strong> <span id="view_extension"></span></p>
                        <p><strong>Población:</strong> <span id="view_poblacion"></span></p>
                        <p><strong>Provincia:</strong> <span id="view_provincia"></span></p>

                        <div class="form-section-title"><i class="fas fa-exclamation-triangle me-1"></i> Estado y Otros
                        </div>
                        <p><strong>Especies en Peligro:</strong> <span id="view_especies_peligro"></span></p>
                        <p><strong>Otros Datos:</strong> <span id="view_otros_datos"></span></p>

                        <div class="form-section-title"><i class="fas fa-photo-video me-1"></i> Multimedia</div>
                        <div id="view_imagenes" class="d-flex flex-wrap gap-2"></div>
                        <div id="view_medios" class="d-flex flex-wrap gap-2 mt-2"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-custom-blue btn-editar-desde-vista">
                        <i class="fas fa-pen me-1"></i> Editar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cochabambaBounds = L.latLngBounds(L.latLng(-18.50, -67.50), L.latLng(-16.00, -64.00));
            const map = L.map('map', {
                maxBounds: cochabambaBounds,
                minZoom: 8,
                zoom: 11
            }).setView([-17.3895, -66.1568], 11);

            L.layerGroup([
                L.tileLayer(
                    'https://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                        attribution: '&copy; Esri'
                    }),
                L.tileLayer(
                    'https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
                        attribution: 'Labels &copy; Esri'
                    })
            ]).addTo(map);

            const polygonLayer = L.layerGroup().addTo(map);
            const viewDataModal = new bootstrap.Modal(document.getElementById('modalVerDatos'));

            const editCreateDataModal = new bootstrap.Modal(document.getElementById('modalCrearDatos'));

            let selectedPolygon = null;
            let selectedMarker = null;
            let currentZona = null;
            let imagenesAEliminar = [];
            let mediosAEliminar = [];
            let filesArray = [];

            function getColorForZona(index) {
                return ['#3388ff', '#ff3333', '#33ff33', '#ff33ff', '#33ffff', '#ffff33', '#ff9933', '#33ff99',
                    '#9933ff', '#ff3399'
                ][index % 10];
            }

            function darkenColor(hex, percent) {
                if (!hex) return '#3388ff';
                hex = hex.replace(/^#/, '');
                let r = parseInt(hex.substring(0, 2), 16),
                    g = parseInt(hex.substring(2, 4), 16),
                    b = parseInt(hex.substring(4, 6), 16);
                r = Math.max(0, Math.floor(r * (100 - percent) / 100));
                g = Math.max(0, Math.floor(g * (100 - percent) / 100));
                b = Math.max(0, Math.floor(b * (100 - percent) / 100));
                return `#${r.toString(16).padStart(2, '0')}${g.toString(16).padStart(2, '0')}${b.toString(16).padStart(2, '0')}`;
            }

            function updateSelectedMarker(latlng) {
                if (selectedMarker) selectedMarker.setLatLng(latlng);
                else selectedMarker = L.marker(latlng, {
                    icon: L.divIcon({
                        className: 'selected-marker',
                        html: '<i class="fas fa-map-marker-alt" style="color:rgb(114, 229, 149); font-size: 32px;"></i>',
                        iconSize: [32, 32],
                        iconAnchor: [16, 32]
                    })
                }).addTo(map);
            }

            function abrirModalVista(zona) {
                currentZona = zona;
                document.getElementById('view-loader').style.display = 'flex';
                document.getElementById('datos-content').style.display = 'none';
                viewDataModal.show();

                const btnEditarDesdeVista = document.querySelector('.btn-editar-desde-vista');
                const datosContent = document.getElementById('datos-content');
                if (!datosContent.querySelector('.form-section-title')) {
                    datosContent.innerHTML = `
                        <div class="form-section-title mt-0"><i class="fas fa-info-circle me-1"></i> Información General</div>
                        <p><strong>Flora y Fauna:</strong> <span id="view_flora_fauna"></span></p>
                        <p><strong>Extensión:</strong> <span id="view_extension"></span></p>
                        <p><strong>Población:</strong> <span id="view_poblacion"></span></p>
                        <p><strong>Provincia:</strong> <span id="view_provincia"></span></p>
                        <div class="form-section-title"><i class="fas fa-exclamation-triangle me-1"></i> Estado y Otros</div>
                        <p><strong>Especies en Peligro:</strong> <span id="view_especies_peligro"></span></p>
                        <p><strong>Otros Datos:</strong> <span id="view_otros_datos"></span></p>
                        <div class="form-section-title"><i class="fas fa-photo-video me-1"></i> Multimedia</div>
                        <div id="view_imagenes" class="d-flex flex-wrap gap-2"></div>`;
                }

                fetch(`/datos/zona/${zona.id}/detalle`)
                    .then(res => {
                        if (res.status === 404) return null;
                        if (!res.ok) throw new Error('Error al obtener datos');
                        return res.json();
                    })
                    .then(data => {
                        document.getElementById('view-loader').style.display = 'none';
                        document.getElementById('datos-content').style.display = 'block';
                        if (data && Object.keys(data).length > 0) {
                            btnEditarDesdeVista.innerHTML = '<i class="fas fa-pen me-1"></i> Editar';
                            btnEditarDesdeVista.dataset.action = 'edit';
                            document.getElementById('view_flora_fauna').textContent = data.flora_fauna || 'N/A';
                            document.getElementById('view_extension').textContent = data.extension || 'N/A';
                            document.getElementById('view_poblacion').textContent = data.poblacion || 'N/A';
                            document.getElementById('view_provincia').textContent = data.provincia || 'N/A';
                            document.getElementById('view_especies_peligro').textContent = data
                                .especies_peligro || 'N/A';
                            document.getElementById('view_otros_datos').textContent = data.otros_datos || 'N/A';

                            const imgContainer = document.getElementById('view_imagenes');
                            imgContainer.innerHTML = '';
                            if (data.imagenes && data.imagenes.length > 0) {
                                data.imagenes.forEach(img => {
                                    imgContainer.innerHTML +=
                                        `<a href="/storage/${img.path}" data-lightbox="zona-${zona.id}"><img src="/storage/${img.path}" class="rounded" style="height:80px; width:80px; object-fit:cover; cursor:pointer;"></a>`;
                                });
                            } else {
                                imgContainer.innerHTML = '<p class="text-muted small">No hay imágenes.</p>';
                            }
                        } else {
                            btnEditarDesdeVista.innerHTML = '<i class="fas fa-plus me-1"></i> Registrar Datos';
                            btnEditarDesdeVista.dataset.action = 'create';
                            document.getElementById('view_imagenes').innerHTML =
                                '<p class="text-muted small w-100">No hay datos registrados para esta zona.</p>';
                            document.getElementById('datos-content').style.display = 'none';
                        }
                    }).catch(err => {
                        document.getElementById('view-loader').style.display = 'none';
                        document.getElementById('datos-content').innerHTML =
                            '<p class="text-center text-danger">Error al cargar los datos.</p>';
                        console.error("Error en fetch:", err);
                    });
            }

            function abrirModalEdicion(zona) {
                currentZona = zona;
                resetForm();
                document.getElementById('form_zona_id').value = zona.id;
                document.getElementById('form-loader').style.display = 'flex';
                editCreateDataModal.show();

                fetch(`/datos/zona/${zona.id}/detalle`)
                    .then(res => {
                        if (!res.ok) {
                            return null;
                        }
                        return res.json();
                    })
                    .then(data => {
                        document.getElementById('form-loader').style.display = 'none';
                        const dato = Array.isArray(data) ? data[0] : data;
                        document.getElementById('modalCrearDatosLabel').innerHTML =
                            '<i class="fas fa-edit text-primary me-2"></i> Editar Datos de Zona';
                        if (dato && !dato.error && Object.keys(dato).length > 0) {
                            document.getElementById('dato_id').value = data.id || '';
                            document.getElementById('flora_fauna').value = data.flora_fauna || '';
                            document.getElementById('extension').value = data.extension || '';
                            document.getElementById('poblacion').value = data.poblacion || '';
                            document.getElementById('provincia').value = data.provincia || '';
                            document.getElementById('especies_peligro').value = data.especies_peligro || '';
                            document.getElementById('otros_datos').value = data.otros_datos || '';

                            if (data.imagenes) {
                                renderExistingFiles(data.imagenes, 'preview-existing', 'img');
                                checkEmptyMsg();
                            }
                            if (data.medios) {
                                const vids = data.medios.filter(m => m.tipo === 'video');
                                const docs = data.medios.filter(m => m.tipo === 'documento');
                                renderExistingFiles(vids, 'preview-videos-existing', 'video');
                                renderExistingFiles(docs, 'preview-documentos-existing', 'doc');
                            }
                        }
                    })
                    .catch(err => {
                        document.getElementById('form-loader').style.display = 'none';
                        console.error("Error en fetch:", err);
                    });
            }

            function abrirModalCreacion(zona) {
                currentZona = zona;
                resetForm();
                document.getElementById('form_zona_id').value = zona.id;
                document.getElementById('modalCrearDatosLabel').innerHTML =
                    '<i class="fas fa-plus-circle text-success me-2"></i> Registrar Nuevos Datos';
                editCreateDataModal.show();
            }

            function handlePolygonClick(polygon, zona, index) {
                if (selectedPolygon) {
                    const originalColor = selectedPolygon.defaultColor || getColorForZona(selectedPolygon.index);
                    selectedPolygon.setStyle({
                        color: originalColor,
                        fillColor: originalColor,
                        fillOpacity: 0.4,
                        weight: 2
                    });
                }
                polygon.index = index;
                const darker = darkenColor(polygon.defaultColor, 30);
                polygon.setStyle({
                    color: darker,
                    fillColor: darker,
                    fillOpacity: 0.7,
                    weight: 4
                });
                selectedPolygon = polygon;
                updateSelectedMarker(polygon.getBounds().getCenter());
                abrirModalVista(zona);
            }

            function drawMapElements(coordenadasData, zona, layerGroup) {
                if (!coordenadasData) return;
                coordenadasData.forEach((item, index) => {
                    const color = getColorForZona(index);
                    if (item.tipo === 'marcador') {
                        const m = L.marker([item.coordenadas.lat, item.coordenadas.lng], {
                            icon: L.divIcon({
                                className: 'custom-marker',
                                html: `<i class="fas fa-map-marker-alt" style="color: ${color}; font-size: 24px;"></i>`
                            })
                        }).addTo(layerGroup);
                        m.zonaId = zona.id;
                        m.on('click', () => {
                            updateSelectedMarker(m.getLatLng());
                            abrirModalVista(zona);
                        });
                    } else if (item.tipo === 'poligono') {
                        const latlngs = item.coordenadas.map(c => [c.lat, c.lng]);
                        const p = L.polygon(latlngs, {
                            color: color,
                            fillColor: color,
                            fillOpacity: 0.4,
                            weight: 2
                        }).addTo(layerGroup);
                        p.zonaId = zona.id;
                        p.defaultColor = color;
                        p.index = index;
                        p.on('click', () => handlePolygonClick(p, zona, index));
                    }
                });
            }

            function resetForm() {
                document.getElementById('formCrearDatos').reset();
                document.getElementById('dato_id').value = '';
                document.getElementById('imagenes_eliminar').value = '[]';
                document.getElementById('medios_eliminar').value = '[]';
                document.getElementById('preview-existing').innerHTML = '';
                document.getElementById('preview-videos-existing').innerHTML = '';
                document.getElementById('preview-documentos-existing').innerHTML = '';
                document.getElementById('preview').innerHTML = '';
                document.getElementById('empty-msg-img').style.display = 'block';
                imagenesAEliminar = [];
                mediosAEliminar = [];
                filesArray = [];
            }

            function checkEmptyMsg() {
                const hasNew = filesArray.length > 0;
                const hasOld = document.getElementById('preview-existing').children.length > 0;
                document.getElementById('empty-msg-img').style.display = (hasNew || hasOld) ? 'none' : 'block';
            }

            function renderExistingFiles(files, containerId, type) {
                const container = document.getElementById(containerId);
                files.forEach(file => {
                    const div = document.createElement('div');
                    div.className = 'position-relative d-inline-block m-1 preview-container';
                    let content = '';
                    if (type === 'img') content =
                        `<img src="/storage/${file.path}" class="rounded" style="height:80px; width:80px; object-fit:cover;">`;
                    else if (type === 'video') content =
                        `<div style="height:80px; width:80px; background:#000; display:flex; align-items:center; justify-content:center; border-radius:6px;"><i class="fas fa-video text-white"></i></div>`;
                    else content =
                        `<div style="height:80px; width:80px; background:#eee; display:flex; align-items:center; justify-content:center; border-radius:6px;"><i class="fas fa-file-pdf text-danger fa-2x"></i></div>`;
                    div.innerHTML =
                        `${content}<button type="button" class="btn-remove-preview btn-remove-existing" data-id="${file.id}" data-type="${type}"><i class="fas fa-times"></i></button>`;
                    container.appendChild(div);
                });
                if (type === 'img') checkEmptyMsg();
            }

            function renderNewPreviews() {
                const preview = document.getElementById("preview");
                preview.innerHTML = "";
                filesArray.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        const div = document.createElement("div");
                        div.className = "position-relative d-inline-block m-1 preview-container";
                        div.innerHTML = `
                            <img src="${ev.target.result}" class="rounded" style="height:80px; width:80px; object-fit:cover;">
                            <button type="button" class="btn-remove-preview btn-remove-new" data-index="${index}"><i class="fas fa-times"></i></button>
                        `;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
                checkEmptyMsg();
            }

            document.addEventListener('click', function(e) {
                if (e.target.id === 'btn-cancelar' || e.target.closest('#btn-cancelar')) {
                    editCreateDataModal.hide();
                    return;
                }

                const btnView = e.target.closest('.btn-ver-datos');
                if (btnView) {
                    const zonaId = parseInt(btnView.dataset.zonaId);
                    const zona = zonasData.find(z => z.id === zonaId);
                    if (zona) abrirModalVista(zona);
                    return;
                }

                const btnEditFromView = e.target.closest('.btn-editar-desde-vista');
                if (btnEditFromView) {
                    const action = btnEditFromView.dataset.action;
                    viewDataModal.hide();
                    if (currentZona) {
                        if (action === 'edit') {
                            abrirModalEdicion(currentZona);
                        } else {
                            abrirModalCreacion(currentZona);
                        }
                    }
                    return;
                }

                const existingBtn = e.target.closest('.btn-remove-existing');
                if (existingBtn) {
                    const id = existingBtn.dataset.id;
                    const type = existingBtn.dataset.type;
                    if (type === 'img') {
                        imagenesAEliminar.push(id);
                        document.getElementById('imagenes_eliminar').value = JSON.stringify(
                            imagenesAEliminar);
                    } else {
                        if (!mediosAEliminar.includes(id)) mediosAEliminar.push(id);
                        document.getElementById('medios_eliminar').value = JSON.stringify(mediosAEliminar);
                    }
                    existingBtn.parentElement.remove();
                    if (type === 'img') checkEmptyMsg();
                    return;
                }
                const newBtn = e.target.closest('.btn-remove-new');
                if (newBtn) {
                    const index = parseInt(newBtn.dataset.index);
                    filesArray.splice(index, 1);
                    updateFileInput();
                    renderNewPreviews();
                    return;
                }
                const deleteBtn = e.target.closest('.btn-eliminar-zona');
                if (deleteBtn) {
                    const id = deleteBtn.dataset.id;
                    const nombre = deleteBtn.dataset.nombre;
                    Swal.fire({
                        title: '¿Eliminar zona?',
                        html: `Eliminar <b>${nombre}</b>?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar',
                        confirmButtonColor: '#d33'
                    }).then(res => {
                        if (res.isConfirmed) {
                            fetch(`/zonas/${id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                    }
                                })
                                .then(r => r.json()).then(d => {
                                    if (d.success) {
                                        deleteBtn.closest('tr').remove();
                                        Swal.fire('Eliminado', '', 'success');
                                    } else Swal.fire('Error', d.message, 'error');
                                });

                        }
                    });
                }
            });

            function updateFileInput() {
                const dt = new DataTransfer();
                filesArray.forEach(file => dt.items.add(file));
                document.getElementById("imagenes").files = dt.files;
            }

            document.getElementById("imagenes").addEventListener("change", function(e) {
                const newFiles = Array.from(e.target.files);
                filesArray = filesArray.concat(newFiles);
                updateFileInput();
                renderNewPreviews();
            });

            const form = document.getElementById('formCrearDatos');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (!form.checkValidity()) {
                    e.stopPropagation();
                    form.classList.add('was-validated');
                    return;
                }
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
                submitBtn.disabled = true;

                fetch('/datos', {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Guardado!',
                                text: 'Los datos se han actualizado correctamente.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            editCreateDataModal.hide();
                        } else {
                            Swal.fire('Error', data.message || 'Error al guardar', 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Error', 'Error de conexión', 'error');
                    })
                    .finally(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
            });

            const zonasData = {!! json_encode(
                $zonas->map(function ($zona) {
                    $ultimoHistorial = $zona->ultimoHistorial;
                    return [
                        'id' => $zona->id,
                        'nombre' => $zona->nombre,
                        'area' => optional($zona->area)->area,
                        'coordenadas' => $ultimoHistorial
                            ? (is_string($ultimoHistorial->coordenadas)
                                ? json_decode($ultimoHistorial->coordenadas, true)
                                : $ultimoHistorial->coordenadas)
                            : null,
                        'estado' => $zona->estado,
                    ];
                }),
            ) !!};

            zonasData.forEach(zona => {
                if (zona.coordenadas) {
                    let coords = zona.coordenadas;
                    if (Array.isArray(coords[0]) && !coords[0].lat) {
                        coords = [{
                            type: 'poligono',
                            coordenadas: zona.coordenadas[0].map(c => ({
                                lat: c[0],
                                lng: c[1]
                            }))
                        }];
                    }
                    drawMapElements(coords, zona, polygonLayer);
                }
            });

            if (polygonLayer.getLayers().length > 0) map.fitBounds(polygonLayer.getBounds().pad(0.2));

            document.querySelectorAll('.zona-hover-btn').forEach(btn => {
                const id = parseInt(btn.dataset.zonaId);
                btn.addEventListener('mouseenter', () => {
                    polygonLayer.eachLayer(layer => {
                        if (layer.zonaId === id) {
                            layer.setStyle({
                                fillOpacity: 0.9,
                                weight: 5,
                                color: '#FFFF00',
                                fillColor: '#FFFF00'
                            });
                            layer.bringToFront();
                        }
                    });
                });
                btn.addEventListener('mouseleave', () => {
                    polygonLayer.eachLayer(layer => {
                        if (layer.zonaId === id) {
                            const originalColor = layer.defaultColor || '#3388ff';
                            layer.setStyle({
                                fillOpacity: 0.4,
                                weight: 2,
                                color: originalColor,
                                fillColor: originalColor
                            });
                        }
                    });
                });
            });

            // Nuevo: Resaltar polígono al pasar el mouse sobre la fila de la tabla
            document.querySelectorAll('.zona-row').forEach(row => {
                const id = parseInt(row.dataset.zonaId);
                row.addEventListener('mouseenter', () => {
                    polygonLayer.eachLayer(layer => {
                        if (layer.zonaId === id) {
                            layer.setStyle({
                                fillOpacity: 0.9,
                                weight: 5,
                                color: '#FFFF00',
                                fillColor: '#FFFF00'
                            });
                            layer.bringToFront();
                        }
                    });
                });
                row.addEventListener('mouseleave', () => {
                    polygonLayer.eachLayer(layer => {
                        if (layer.zonaId === id) {
                            const originalColor = layer.defaultColor || '#3388ff';
                            layer.setStyle({
                                fillOpacity: 0.4,
                                weight: 2,
                                color: originalColor,
                                fillColor: originalColor
                            });
                        }
                    });
                });

                row.addEventListener('click', (e) => {
                    if (e.target.closest('.btn-action-sm')) return;

                    polygonLayer.eachLayer(layer => {
                        if (layer.zonaId === id) {
                            if (layer instanceof L.Polygon) map.fitBounds(layer.getBounds()
                                .pad(0.1));
                            else if (layer instanceof L.Marker) map.setView(layer
                                .getLatLng(), 15);
                        }
                    });
                });
            });

        });
    </script>
@endsection
