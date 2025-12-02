@extends('layouts.app')

@section('content')
<div class="container">
    <div class="section-header">
        <h3 class="page__heading text-white mb-3">
            Detalle de Zona: {{ $zona->nombre }}
        </h3>
    </div>

    <!-- Modal para descripción completa -->
    <div class="modal fade" id="descripcionModal" tabindex="-1" role="dialog" aria-labelledby="descripcionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="descripcionModalLabel">Descripción Completa</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="descripcionCompleta">{{ $zona->descripcion ?? 'No hay descripción disponible' }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <div style="overflow-x: hidden; overflow-y:auto; height: calc(100vh - 200px);">

    
        <div class="row">
            <!-- Columna de información -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i> Información General
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="text-muted mb-2"><i class="fas fa-tag me-2"></i> Nombre</h6>
                            <p class="fw-bold">{{ $zona->nombre }}</p>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-muted mb-2"><i class="fas fa-layer-group me-2"></i> Área</h6>
                            <p class="fw-bold">{{ $zona->area->area ?? 'No especificado' }}</p>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-muted mb-2"><i class="fas fa-info-circle me-2"></i> Descripción</h6>
                            @php
                                $descripcion = $zona->descripcion ?? 'No hay descripción disponible';
                                $descripcionCorta = \Illuminate\Support\Str::limit($descripcion, 100, '...');
                            @endphp
                            <p class="fw-bold">
                                {{ $descripcionCorta }}
                                @if(strlen($descripcion) > 100)
                                    <a href="#" class="text-primary" data-toggle="modal" data-target="#descripcionModal">Leer todo</a>
                                @endif
                            </p>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-muted mb-2"><i class="fas fa-power-off me-2"></i> Estado</h6>
                            <span class="badge bg-{{ $zona->estado ? 'success' : 'secondary' }} text-white p-2">
                                {{ $zona->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-muted mb-2"><i class="fas fa-calendar-alt me-2"></i> Fecha de Registro</h6>
                            <p class="fw-bold">{{ $zona->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <!-- Mostrar información del último historial -->
                        @if($zona->ultimoHistorial)
                        <div class="mb-3">
                            <h6 class="text-muted mb-2"><i class="fas fa-map-marked-alt me-2"></i> Última Actualización</h6>
                            <p class="fw-bold">{{ $zona->ultimoHistorial->created_at->format('d/m/Y H:i') }}</p>
                            <p class="small text-muted">
                                Tipo: {{ ucfirst($zona->ultimoHistorial->tipo_coordenada) }}<br>
                                Elementos: {{ count($zona->ultimoHistorial->coordenadas) }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Columna del mapa -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-map me-2"></i> Mapa de la Zona
                        </h5>
                        <div>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-light dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                                <i class="fas fa-download me-1"></i> Exportar
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#" id="export-image"><i class="fas fa-image me-2"></i> Imagen PNG</a></li>
                                <li><a class="dropdown-item" href="#" id="export-kml"><i class="fas fa-file-code me-2"></i> KML</a></li>
                                <li><a class="dropdown-item" href="#" id="export-kmz"><i class="fas fa-file-archive me-2"></i> KMZ</a></li>
                                <li><a class="dropdown-item" href="#" id="export-shp"><i class="fas fa-map me-2"></i> SHP</a></li>
                            </ul>
                        </div>


                        </div>
                    </div>
                    <div class="card-body p-0 position-relative">
                        <div id="map" style="height: 570px; width: 100%;"></div>
                        <div class="map-overlay p-2">
                            <span class="badge bg-primary me-2 text-white"><i class="fas fa-square-full"></i> Polígono</span>
                            <span class="badge bg-danger text-white"><i class="fas fa-map-marker-alt"></i> Marcador</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de multimedia -->
        @if((isset($zona->imagenes) && count($zona->imagenes) > 0) || (isset($zona->videos) && count($zona->videos) > 0))
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-images me-2"></i> Multimedia
                </h5>
            </div>
            <div class="card-body">
                
                @if(isset($zona->imagenes) && count($zona->imagenes) > 0)
                <div class="mb-4">
                    <h6 class="text-muted mb-3"><i class="fas fa-image me-2"></i> Imágenes</h6>
                    <div class="row gallery">
                        @foreach($zona->imagenes as $imagen)
                        <div class="col-md-3 col-6 mb-4">
                            <div class="image-container" style="height: 200px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #f8f9fa; border-radius: 8px;">
                            
                                <a href="{{ asset('storage/' . $imagen->url) }}" data-lightbox="zona-images" data-title="{{ $zona->nombre }}" style="display: block; width: 100%; height: 100%;">
                                    <img src="{{ asset('storage/' . $imagen->url) }}" class="img-fluid" alt="Imagen de {{ $zona->nombre }}" style="object-fit: contain; width: 100%; height: 100%;">
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(isset($zona->videos) && count($zona->videos) > 0)
                <div>
                    <h6 class="text-muted mb-3"><i class="fas fa-video me-2"></i> Videos</h6>
                    <div class="row">
                        @foreach($zona->videos as $video)
                        <div class="col-md-6 mb-3">
                            <div class="ratio ratio-16x9" style="width: 300px ; height:200px">
                                
                                <video controls class="rounded shadow-sm bg-dark" style="width: 100%; height: 100%;">
                                    <source src="{{ asset('storage/' . $video->url) }}" type="video/mp4">
                                    Tu navegador no soporta el elemento de video.
                                </video>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
        @endif

        <!-- Botones de acción -->
        <div class="d-flex justify-content-between" style="margin-top: -0.5rem;">
            <a href="{{ route('zonas.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Volver al listado
            </a>
            <div>
                <a href="{{ route('zonas.edit', $zona->id) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-2"></i> Editar
                </a>
                <form action="{{ route('zonas.change-status', $zona) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-{{ $zona->estado ? 'danger' : 'success' }}">
                        <i class="fas {{ $zona->estado ? 'fa-toggle-off' : 'fa-toggle-on' }} me-2"></i>
                        {{ $zona->estado ? 'Desactivar' : 'Activar' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Incluir Leaflet.js y HTML2Canvas -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<!-- tokml -->
<script src="https://cdn.jsdelivr.net/npm/@mapbox/tokml@0.4.0/tokml.min.js"></script>
<script src="https://unpkg.com/tokml@0.4.0/tokml.js"></script>

<!-- JSZip -->

{{-- <script src="https://cdn.jsdelivr.net/npm/jszip@3.10.1/dist/jszip.min.js"></script> --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.6.1/jszip.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>


<!-- shp-write -->
<script src="https://cdn.jsdelivr.net/npm/shp-write@0.3.0/shpwrite.min.js"></script>


<style>
    .card {
        border-radius: 10px;
        border: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }

    #map {
        border-radius: 0 0 10px 10px;
        z-index: 1;
    }

    .map-overlay {
        position: absolute;
        bottom: 10px;
        right: 10px;
        z-index: 1000;
        background: rgba(255,255,255,0.9);
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .gallery {
        display: flex;
        flex-wrap: wrap;
        margin: -8px; /* Compensa el margen de las columnas */
    }

    .image-container {
        transition: transform 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .image-container:hover {
        transform: scale(1.03);
    }

    .image-container img {
        transition: transform 0.3s ease;
    }

    .btn-action {
        min-width: 120px;
    }

    .text-muted {
        color: #6c757d !important;
    }

    .badge {
        font-weight: 500;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Variable global para almacenar los colores asignados
    const assignedColors = {};
    const coordenadas = @json($coordenadas);
    
    // --- Funciones auxiliares ---
    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    // Convierte HEX (#RRGGBB) a KML AABBGGRR (Alpha-Blue-Green-Red)
    function hexToKmlColor(hex, alpha = 'ff') {
        hex = hex.startsWith('#') ? hex.slice(1) : hex;
        if (hex.length !== 6) return "7d00ff00";
        
        const r = hex.substring(0, 2);
        const g = hex.substring(2, 4);
        const b = hex.substring(4, 6);
        return `${alpha}${b}${g}${r}`;
    }

    function generarGeoJSONDesdeCoordenadas(coordenadas) {
        const features = [];
        let featureIndex = 0;

        if (Array.isArray(coordenadas[0]) && Array.isArray(coordenadas[0][0])) {
            coordenadas.forEach((poligonoCoords, index) => {
                const uniqueId = `polygon_${featureIndex++}`;
                const featureColor = assignedColors[uniqueId] || getRandomColor();
                assignedColors[uniqueId] = featureColor;

                const coordsLngLat = poligonoCoords.map(c => [c[1], c[0]]);
                if (coordsLngLat.length > 2 && (coordsLngLat[0][0] !== coordsLngLat[coordsLngLat.length - 1][0] || coordsLngLat[0][1] !== coordsLngLat[coordsLngLat.length - 1][1])) {
                    coordsLngLat.push(coordsLngLat[0]);
                }
                
                features.push({
                    type: 'Feature',
                    geometry: { type: 'Polygon', coordinates: [coordsLngLat] },
                    properties: { 
                        name: `Polígono ${index + 1}`, 
                        description: `Puntos: ${poligonoCoords.length}`, 
                        color: featureColor, 
                        tipo: 'poligono' 
                    }
                });
            });
        } else {
            coordenadas.forEach((item, index) => {
                const uniqueId = (item.tipo === 'poligono') ? `polygon_${featureIndex}` : `marker_${featureIndex}`;
                const featureColor = getRandomColor();
                assignedColors[uniqueId] = featureColor;
                
                if (item.tipo === 'poligono' && item.coordenadas && Array.isArray(item.coordenadas)) {
                    const coordsLngLat = item.coordenadas.map(coord => [coord.lng, coord.lat]);
                    if (coordsLngLat.length > 2 && (coordsLngLat[0][0] !== coordsLngLat[coordsLngLat.length - 1][0] || coordsLngLat[0][1] !== coordsLngLat[coordsLngLat.length - 1][1])) {
                        coordsLngLat.push(coordsLngLat[0]);
                    }
                    features.push({
                        type: 'Feature',
                        geometry: { type: 'Polygon', coordinates: [coordsLngLat] },
                        properties: { 
                            name: `Polígono ${index + 1}`, 
                            description: `Puntos: ${item.coordenadas.length}`, 
                            color: featureColor, 
                            tipo: 'poligono' 
                        }
                    });
                } else if (item.tipo === 'marcador' && item.coordenadas) {
                    features.push({
                        type: 'Feature',
                        geometry: { type: 'Point', coordinates: [item.coordenadas.lng, item.coordenadas.lat] },
                        properties: { 
                            name: `Marcador ${index + 1}`, 
                            description: `Lat: ${item.coordenadas.lat.toFixed(6)}, Lng: ${item.coordenadas.lng.toFixed(6)}`, 
                            color: featureColor, 
                            tipo: 'marcador' 
                        }
                    });
                }
                featureIndex++;
            });
        }

        return { type: 'FeatureCollection', features: features };
    }

    // --- Inicialización del Mapa Leaflet ---
    let initialLatLng = [-17.3895, -66.1568];

    const map = L.map('map', {
        zoomControl: true,
        scrollWheelZoom: true
    }).setView(initialLatLng, 18);

    L.layerGroup([
        L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: '© Esri, i-cubed, USDA, USGS, etc.',
            maxZoom: 18
        }),
        L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Labels © Esri'
        })
    ]).addTo(map);

    const bounds = L.latLngBounds();
    const featureGroup = L.featureGroup().addTo(map);

    // --- DIBUJAR GEOMETRÍAS CON COLORES ALEATORIOS ---
    if (coordenadas.length > 0) {
        let currentIndex = 0;

        if (Array.isArray(coordenadas[0]) && Array.isArray(coordenadas[0][0])) {
            // Formato: Array de arrays de coordenadas
            coordenadas.forEach((poligonoCoords, index) => {
                const uniqueId = `polygon_${currentIndex++}`;
                const randomColor = getRandomColor();
                assignedColors[uniqueId] = randomColor;
                
                const latLngs = poligonoCoords.map(coord => [coord[0], coord[1]]);
                const polygon = L.polygon(latLngs, {
                    color: randomColor,
                    fillColor: randomColor,
                    fillOpacity: 0.4,
                    weight: 3
                }).addTo(featureGroup);
                
                bounds.extend(polygon.getBounds());
                polygon.bindPopup(`<strong>Polígono ${index + 1}</strong><br>Puntos: ${poligonoCoords.length}<br>Color: <span style="display:inline-block;width:15px;height:15px;background:${randomColor};border:1px solid #000;"></span> ${randomColor}`);
            });
        } else {
            // Formato: Array de objetos con tipo y coordenadas
            coordenadas.forEach(function(item, index) {
                const randomColor = getRandomColor();
                
                if (item.tipo === 'poligono' && item.coordenadas && Array.isArray(item.coordenadas)) {
                    const uniqueId = `polygon_${currentIndex}`;
                    assignedColors[uniqueId] = randomColor;
                    
                    const latLngs = item.coordenadas.map(coord => [coord.lat, coord.lng]);
                    const polygon = L.polygon(latLngs, {
                        color: randomColor,
                        fillColor: randomColor,
                        fillOpacity: 0.4,
                        weight: 3
                    }).addTo(featureGroup);
                    
                    polygon.bindPopup(`<strong>Polígono ${index + 1}</strong><br>Puntos: ${item.coordenadas.length}<br>Color: <span style="display:inline-block;width:15px;height:15px;background:${randomColor};border:1px solid #000;"></span> ${randomColor}`);
                    bounds.extend(polygon.getBounds());
                    
                } else if (item.tipo === 'marcador' && item.coordenadas) {
                    const uniqueId = `marker_${currentIndex}`;
                    assignedColors[uniqueId] = randomColor;
                    
                    const marker = L.marker([item.coordenadas.lat, item.coordenadas.lng], {
                        icon: L.divIcon({
                            className: 'custom-marker',
                            html: `<i class="fas fa-map-marker-alt" style="color: ${randomColor}; font-size: 32px; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);"></i>`,
                            iconSize: [32, 32],
                            iconAnchor: [16, 32]
                        })
                    }).addTo(featureGroup);
                    
                    marker.bindPopup(`<strong>Marcador ${index + 1}</strong><br>Lat: ${item.coordenadas.lat.toFixed(6)}<br>Lng: ${item.coordenadas.lng.toFixed(6)}<br>Color: <span style="display:inline-block;width:15px;height:15px;background:${randomColor};border:1px solid #000;"></span> ${randomColor}`);
                    bounds.extend(marker.getLatLng());
                }
                currentIndex++;
            });
        }

        // Ajustar vista para mostrar todos los elementos
        if (bounds.isValid()) {
            setTimeout(() => {
                map.fitBounds(bounds, { padding: [50, 50] });
                map.invalidateSize();
            }, 100);
        }
    } else {
        L.marker(initialLatLng).addTo(map)
            .bindPopup('<strong>Zona sin coordenadas definidas</strong>')
            .openPopup();
    }

    // --- EXPORTACIÓN PNG ---
    document.getElementById('export-image').addEventListener('click', function (e) {
        e.preventDefault();
        
        html2canvas(document.getElementById('map'), {
            useCORS: true,
            allowTaint: true
        }).then(canvas => {
            const link = document.createElement('a');
            link.download = `zona_{{ $zona->nombre }}_${new Date().toISOString().slice(0,10)}.png`;
            link.href = canvas.toDataURL();
            link.click();
        });
    });

    // --- EXPORTACIÓN KML ---
    document.getElementById('export-kml').addEventListener('click', function (e) {
        e.preventDefault();

        const geojson = generarGeoJSONDesdeCoordenadas(coordenadas);
        let kmlStyles = '';
        const uniqueColors = new Set();

        geojson.features.forEach(feature => {
            if (feature.properties && feature.properties.color) {
                uniqueColors.add(feature.properties.color);
            }
        });

        // Generar estilos KML
        uniqueColors.forEach(hexColor => {
            const kmlColorLinea = hexToKmlColor(hexColor, 'ff');
            const kmlColorRelleno = hexToKmlColor(hexColor, '80');
            const styleId = `style_${hexColor.replace('#', '')}`;
            
            kmlStyles += `
                <Style id="${styleId}_polygon">
                    <LineStyle><color>${kmlColorLinea}</color><width>3</width></LineStyle>
                    <PolyStyle><color>${kmlColorRelleno}</color></PolyStyle>
                </Style>`;
            
            kmlStyles += `
                <Style id="${styleId}_marker">
                    <IconStyle>
                        <color>${kmlColorLinea}</color>
                        <Icon><href>http://maps.google.com/mapfiles/kml/pushpin/ylw-pushpin.png</href></Icon>
                        <hotSpot x="20" y="2" xunits="pixels" yunits="pixels"/>
                    </IconStyle>
                </Style>`;
        });

        // Generar KML
        let kml = tokml(geojson, {
            documentName: 'Zona {{ $zona->nombre }}',
            documentDescription: 'Datos de polígonos y marcadores con colores aleatorios.',
            name: 'name',
            description: 'description',
            style: (feature) => {
                if (feature.properties && feature.properties.color) {
                    const hexColor = feature.properties.color;
                    const styleId = `style_${hexColor.replace('#', '')}`;
                    return feature.geometry.type === 'Polygon' ? `#${styleId}_polygon` : `#${styleId}_marker`;
                }
                return '';
            }
        });

        kml = kml.replace('</Document>', `${kmlStyles}</Document>`);

        const blob = new Blob([kml], { type: 'application/vnd.google-earth.kml+xml' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `zona_{{ $zona->nombre }}_${new Date().toISOString().slice(0,10)}.kml`;
        link.click();
    });
    
    // --- EXPORTACIÓN KMZ ---
    document.getElementById('export-kmz').addEventListener('click', function (e) {
        e.preventDefault();

        const geojson = generarGeoJSONDesdeCoordenadas(coordenadas);
        let kmlStyles = '';
        const uniqueColors = new Set();

        geojson.features.forEach(feature => {
            if (feature.properties && feature.properties.color) {
                uniqueColors.add(feature.properties.color);
            }
        });

        uniqueColors.forEach(hexColor => {
            const kmlColorLinea = hexToKmlColor(hexColor, 'ff');
            const kmlColorRelleno = hexToKmlColor(hexColor, '80');
            const styleId = `style_${hexColor.replace('#', '')}`;
            
            kmlStyles += `<Style id="${styleId}_polygon"><LineStyle><color>${kmlColorLinea}</color><width>3</width></LineStyle><PolyStyle><color>${kmlColorRelleno}</color></PolyStyle></Style>`;
            kmlStyles += `<Style id="${styleId}_marker"><IconStyle><color>${kmlColorLinea}</color><Icon><href>http://maps.google.com/mapfiles/kml/pushpin/ylw-pushpin.png</href></Icon><hotSpot x="20" y="2" xunits="pixels" yunits="pixels"/></IconStyle></Style>`;
        });

        let kml = tokml(geojson, {
            documentName: 'Zona {{ $zona->nombre }}',
            documentDescription: 'Datos de polígonos y marcadores.',
            name: 'name',
            description: 'description',
            style: (feature) => {
                if (feature.properties && feature.properties.color) {
                    const hexColor = feature.properties.color;
                    const styleId = `style_${hexColor.replace('#', '')}`;
                    return feature.geometry.type === 'Polygon' ? `#${styleId}_polygon` : `#${styleId}_marker`;
                }
                return '';
            }
        });

        kml = kml.replace('</Document>', `${kmlStyles}</Document>`);

        const zip = new JSZip();
        zip.file('doc.kml', kml);
        
        zip.generateAsync({ type: 'blob' }).then(blob => {
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `zona_{{ $zona->nombre }}_${new Date().toISOString().slice(0,10)}.kmz`;
            link.click();
        });
    });

    // --- EXPORTACIÓN SHP ---
    document.getElementById('export-shp').addEventListener('click', function (e) {
        e.preventDefault();

        const geojson = generarGeoJSONDesdeCoordenadas(coordenadas);

        shpwrite.download(geojson, {
            folder: 'zona_shp', 
            filename: `zona_{{ $zona->nombre }}_${new Date().toISOString().slice(0,10)}`,
            types: {
                point: 'Marcadores',
                polygon: 'Poligonos'
            }
        });
    });

    // Configurar lightbox para las imágenes
    if (typeof lightbox !== 'undefined') {
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'albumLabel': 'Imagen %1 de %2'
        });
    }
});

</script>
@endsection
