@extends('layouts.app')

@section('title', 'Registrar Nueva Zona')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">
                <i class="fas fa-map-marked-alt mr-2"></i> Registrar Nueva Zona
            </h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('zonas.index') }}">Zonas</a></div>
                <div class="breadcrumb-item active">Crear</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row justify-content-center">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card shadow-lg custom-card-form">

                        <div class="card-header custom-card-header">
                            <h4><i class="fas fa-edit mr-1"></i> Datos Geográficos y Multimedia</h4>
                        </div>

                        @if ($errors->any())
                            <div class="p-4">
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <strong>Error:</strong> Por favor corrige los siguientes problemas:
                                    <ul class="mb-0 mt-2 pl-4">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('zonas.store') }}" method="POST" enctype="multipart/form-data"
                            id="zona-form">
                            @csrf

                            <div class="card-body custom-scroll-area p-4"
                                style="max-height: calc(100vh - 400px); overflow-y: auto;">

                                <input type="hidden" name="coordenadas" id="coordenadas-array"
                                    value="@json(old('coordenadas'))">
                                <input type="hidden" name="tipo_coordenada" id="tipo-coordenada">

                                <div class="row">
                                    <div class="col-lg-6">
                                        <h5 class="text-primary mb-3"><i class="fas fa-file-alt mr-1"></i> Información
                                            General</h5>

                                        <div class="form-group">
                                            <label for="nombre" class="required-label"
                                                style="font-size: 14px; font-weight: bold;">
                                                <i class="fas fa-tag mr-1"></i> Nombre de la Zona:
                                            </label>

                                            <input type="text" name="nombre" id="nombre" class="form-control"
                                                required value="{{ old('nombre') }}"
                                                placeholder="Escriba el nombre de la zona.">
                                        </div>

                                        <div class="form-group">
                                            <label for="area_id" class="required-label"
                                                style="font-size: 14px; font-weight: bold;">
                                                <i class="fas fa-globe-americas mr-1"></i> Área Protegida:
                                            </label>

                                            <select name="area_id" id="area_id" class="form-control select2" required>
                                                <option value="">Seleccione un área protegida</option>
                                                @foreach ($areas as $area)
                                                    <option value="{{ $area->id }}"
                                                        {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                                        {{ $area->area }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="descripcion" style="font-size: 14px; font-weight: bold;">
                                                <i class="fas fa-info-circle mr-1"></i> Descripción:
                                            </label>

                                            <textarea name="descripcion" id="descripcion" class="form-control" style="height: 110px;"
                                                placeholder="Descripción detallada de la zona">{{ old('descripcion') }}</textarea>
                                        </div>

                                        <hr class="d-lg-none my-4">
                                    </div>

                                    <div class="col-lg-6">
                                        <h5 class="text-primary mb-3"><i class="fas fa-camera mr-1"></i> Archivos Multimedia
                                        </h5>

                                        <div class="form-group">
                                            <label style="font-size: 14px; font-weight: bold;"><i
                                                    class="fas fa-images mr-2"></i> Imágenes de la Zona:</label>

                                            <div class="custom-file mb-2">
                                                <input type="file" class="custom-file-input" id="imagenes"
                                                    name="imagenes[]" accept="image/*" multiple>

                                                <label class="custom-file-label" for="imagenes">Seleccione una o más
                                                    imágenes</label>
                                            </div>

                                            <div id="imagenes-preview" class="row mt-3"></div>
                                            <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF.</small>
                                        </div>

                                        <div class="form-group">
                                            <label style="font-size: 14px; font-weight: bold;"><i
                                                    class="fas fa-video mr-1"></i> Videos de la Zona:</label>
                                            <div class="custom-file mb-2">
                                                <input type="file" class="custom-file-input" id="videos"
                                                    name="videos[]" accept="video/mp4,video/quicktime" multiple>

                                                <label class="custom-file-label" for="videos">Seleccione uno o más
                                                    videos</label>
                                            </div>
                                            <div id="videos-preview" class="row mt-3"></div>
                                            <small class="form-text text-muted">Formatos recomendados: MP4, MOV.</small>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <h5 class="text-primary mb-3"><i class="fas fa-map-pin mr-1"></i> Definición de Geometría
                                    (Mapa)
                                </h5>

                                <div class="form-group">
                                    <label style="font-size: 14px; font-weight: bold;"><i class="fas fa-pencil-alt"></i>
                                        Opciones de Dibujo:
                                    </label>

                                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                                        <div class="btn-group mb-2" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                id="draw-marker" title="Dibujar Marcador">
                                                <i class="fas fa-map-marker-alt"></i> Marcador
                                            </button>

                                            <button type="button" class="btn btn-outline-success btn-sm"
                                                id="draw-polygon" title="Dibujar Polígono">
                                                <i class="fas fa-draw-polygon"></i> Polígono
                                            </button>

                                            <button type="button" class="btn btn-outline-danger btn-sm" id="clear-all"
                                                title="Limpiar Mapa">
                                                <i class="fas fa-trash-alt"></i> Limpiar
                                            </button>
                                        </div>
                                    </div>

                                    <div id="geocoder-search" class="mb-2"></div>

                                    <div id="map"></div>

                                    <small class="form-text text-muted">
                                        Dibuje al menos un marcador o polígono en el mapa (dentro de los límites de
                                        Cochabamba) para definir la zona.
                                    </small>
                                </div>

                            </div>

                            <div class="card-footer text-center py-3">
                                <button type="submit" class="btn btn-primary btn-action-custom mr-3">
                                    <i class="fas fa-save"></i> Guardar Zona
                                </button>

                                <a href="{{ route('zonas.index') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-arrow-left"></i> Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

    <style>
        /* Estilo para el botón de acción (Azul Sólido) */
        .btn-action-custom {
            background-color: #2f55d4 !important;
            border-color: #2f55d4 !important;
            color: #ffffff !important;
            box-shadow: none !important;
            border-radius: 50px;
            padding: 0.55rem 1.5rem;
            font-weight: 600;
            transition: opacity 0.3s ease;
        }

        /* Estados Hover / Focus / Active */
        .btn-action-custom:hover,
        .btn-action-custom:focus,
        .btn-action-custom:active {
            background-color: #2f55d4 !important;
            border-color: #2f55d4 !important;
            color: #ffffff !important;
            box-shadow: none !important;
            outline: none !important;
            opacity: 0.9;
            transform: none !important;
        }

        /* Estilo de la Tarjeta (Consistencia) */
        .custom-card-form {
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        .custom-card-header {
            background: linear-gradient(90deg, #6777ef 0%, #a9b5f5 100%);
            color: #ffffff !important;
            border-radius: 12px 12px 0 0 !important;
            padding: 1rem 1.5rem;
        }

        .custom-card-header h4 {
            color: #ffffff !important;
            font-weight: 600;
            margin-bottom: 0;
        }

        /* SCROLL SOLO EN EL CONTENIDO DEL FORMULARIO */
        .card-footer {
            border-top: 1px solid #dee2e6;
            background-color: #f8f9fa;
            border-radius: 0 0 12px 12px;
        }

        /* Formulario y Etiquetas */
        .required-label:after {
            content: " *";
            color: #dc3545;
            font-weight: bold;
        }

        label {
            font-weight: 600;
            color: #495057;
        }

        /* Ajustes para el contenedor del buscador externo */
        #geocoder-search {
            width: 30%;
            z-index: 800;
            margin-bottom: 30px;
        }

        /* Estilos para el plugin Leaflet Geocoder cuando está fuera */
        #geocoder-search .leaflet-control-geocoder {
            box-shadow: none;
            margin: 0s;
            border: 1px solid #ced4da;
            border-radius: 15px;
            width: 80%;
            max-width: 80%;
        }

        /* Ajustar el input interno */
        #geocoder-search .leaflet-control-geocoder-form input {
            width: 100%;
            padding: 8px 10px 8px 30px;
            border: none;
            outline: none;
            font-size: 1rem;
        }

        /* Ajustar el icono de la lupa */
        #geocoder-search .leaflet-control-geocoder-icon {
            position: absolute;
            left: 5px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            background-size: contain;
            z-index: 10;
        }

        /* Asegurar que la lista de resultados se vea bien */
        .leaflet-control-geocoder-alternatives {
            width: 100%;
            border: 1px solid #ddd;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Mapa */
        #map {
            height: 600px;
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
        }

        /* Previsualizaciones */
        .preview-container {
            position: relative;
            padding: 5px;
        }

        .preview-container img,
        .preview-container video {
            max-width: 100%;
            max-height: 150px;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: block;
            margin: auto;
        }

        .remove-btn {
            position: absolute;
            top: 0px;
            right: 15px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.8rem;
            line-height: 1;
            padding: 0;
            border: 1px solid white;
            z-index: 5;
        }

        /* File Input Customizado */
        .custom-file-label::after {
            content: "Examinar";
        }

        /* Búsqueda en el mapa */
        .search-container {
            width: 350px;
        }

        /* Scrollbar estilizado para el área de contenido */
        .custom-scroll-area::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scroll-area::-webkit-scrollbar-thumb {
            background-color: #adb5bd;
            border-radius: 3px;
        }

        .custom-scroll-area::-webkit-scrollbar-track {
            background-color: #f1f1f1;
        }
    </style>
@endpush

@push('js')
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>


    <script>
        let map;
        document.addEventListener('DOMContentLoaded', function() {

            // 1. Configuración del mapa (Mantenido)
            const cochabambaBounds = L.latLngBounds(
                L.latLng(-18.50, -67.50), // suroeste
                L.latLng(-16.00, -64.00) // noreste
            );

            map = L.map('map', {
                minZoom: 9
            }).setView([-17.3895, -66.1568], 13); // Centro de Cochabamba

            L.layerGroup([
                L.tileLayer(
                    'https://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                        attribution: '&copy; Esri, i-cubed, USDA, USGS, etc.',
                    }),

                L.tileLayer(
                    'https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
                        attribution: 'Labels &copy; Esri'
                    })
            ]).addTo(map);

            const drawnItems = new L.FeatureGroup();
            map.addLayer(drawnItems);

            // Funciones de validación
            function estaEnCochabamba(lat, lng) {
                return lat >= cochabambaBounds.getSouth() &&
                    lat <= cochabambaBounds.getNorth() &&
                    lng >= cochabambaBounds.getWest() &&
                    lng <= cochabambaBounds.getEast();
            }

            const drawControl = new L.Control.Draw({
                position: 'topright',
                draw: {
                    polygon: {
                        allowIntersection: false,
                        drawError: {
                            color: '#b00b00',
                            message: '¡No puedes dibujar fuera de Cochabamba!'
                        },
                        shapeOptions: {
                            color: '#3388ff',
                            fillColor: '#3388ff',
                            fillOpacity: 0.2
                        }
                    },
                    marker: {
                        icon: new L.Icon.Default()
                    },
                    polyline: false,
                    circle: false,
                    rectangle: false,
                    circlemarker: false
                },
                edit: {
                    featureGroup: drawnItems,
                    remove: true
                }
            });

            // Manejadores de eventos de dibujo
            map.on(L.Draw.Event.CREATED, function(e) {
                const layer = e.layer;
                let fueraDeLimites = false;

                if (layer instanceof L.Marker) {
                    const latlng = layer.getLatLng();
                    if (!estaEnCochabamba(latlng.lat, latlng.lng)) {
                        fueraDeLimites = true;
                    }
                } else if (layer instanceof L.Polygon) {
                    const points = layer.getLatLngs()[0];
                    fueraDeLimites = points.some(latlng => !estaEnCochabamba(latlng.lat, latlng.lng));
                }

                if (fueraDeLimites) {
                    alert('¡Error! No puedes dibujar fuera de los límites de Cochabamba.');
                    map.removeLayer(layer);
                    return;
                }

                drawnItems.addLayer(layer);
                updateCoordenadasInput();
            });

            map.on(L.Draw.Event.EDITED, updateCoordenadasInput);
            map.on(L.Draw.Event.DELETED, updateCoordenadasInput);

            // Botones de dibujo personalizados
            document.getElementById('draw-marker').addEventListener('click', function() {
                new L.Draw.Marker(map, drawControl.options.draw.marker).enable();
            });

            document.getElementById('draw-polygon').addEventListener('click', function() {
                new L.Draw.Polygon(map, drawControl.options.draw.polygon).enable();
            });

            document.getElementById('clear-all').addEventListener('click', function() {
                drawnItems.clearLayers();
                document.getElementById('coordenadas-array').value = '';
            });

            // Función para actualizar el input de coordenadas
            function updateCoordenadasInput() {
                const coordenadas = [];
                let fueraDeLimites = false;

                drawnItems.eachLayer(function(layer) {
                    if (layer instanceof L.Marker) {
                        const latlng = layer.getLatLng();
                        if (!estaEnCochabamba(latlng.lat, latlng.lng)) {
                            fueraDeLimites = true;
                            return;
                        }
                        coordenadas.push({
                            tipo: 'marcador',
                            coordenadas: {
                                lat: latlng.lat,
                                lng: latlng.lng
                            }
                        });
                    } else if (layer instanceof L.Polygon) {
                        const points = layer.getLatLngs()[0];
                        const puntosFuera = points.some(latlng => !estaEnCochabamba(latlng.lat, latlng
                            .lng));

                        if (puntosFuera) {
                            fueraDeLimites = true;
                            return;
                        }

                        coordenadas.push({
                            tipo: 'poligono',
                            coordenadas: points.map(latlng => ({
                                lat: latlng.lat,
                                lng: latlng.lng
                            }))
                        });
                    }
                });

                if (fueraDeLimites) {
                    alert('¡Error! Se detectaron puntos fuera de Cochabamba. Limpiando el mapa...');
                    drawnItems.clearLayers();
                    document.getElementById('coordenadas-array').value = '';
                    return;
                }

                document.getElementById('coordenadas-array').value = JSON.stringify(coordenadas);
                const tipos = coordenadas.map(c => c.tipo);
                const tipoCoordenada = tipos.includes('poligono') ? (tipos.includes('marcador') ? 'mixto' :
                    'poligono') : 'marcador';
                document.getElementById('tipo-coordenada').value = tipoCoordenada;
            }

            // Cargar coordenadas antiguas y renderizar límites (Lógica idéntica a la anterior)
            const oldCoordenadas = document.getElementById('coordenadas-array').value;
            if (oldCoordenadas) {
                try {
                    const coordenadas = JSON.parse(oldCoordenadas);
                    let coordenadasValidas = true;

                    for (const item of coordenadas) {
                        if (item.tipo === 'marcador') {
                            if (!estaEnCochabamba(item.coordenadas.lat, item.coordenadas.lng)) {
                                coordenadasValidas = false;
                                break;
                            }
                        } else if (item.tipo === 'poligono') {
                            for (const coord of item.coordenadas) {
                                if (!estaEnCochabamba(coord.lat, coord.lng)) {
                                    coordenadasValidas = false;
                                    break;
                                }
                            }
                            if (!coordenadasValidas) break;
                        }
                    }

                    if (coordenadasValidas) {
                        coordenadas.forEach(item => {
                            if (item.tipo === 'marcador') {
                                drawnItems.addLayer(L.marker([item.coordenadas.lat, item.coordenadas.lng]));
                            } else if (item.tipo === 'poligono') {
                                const latlngs = item.coordenadas.map(coord => [coord.lat, coord.lng]);
                                drawnItems.addLayer(L.polygon(latlngs, {
                                    color: '#3388ff',
                                    fillColor: '#3388ff',
                                    fillOpacity: 0.2
                                }));
                            }
                        });
                        if (drawnItems.getLayers().length > 0) {
                            map.fitBounds(drawnItems.getBounds());
                        }
                    } else {
                        console.warn('Coordenadas antiguas están fuera de Cochabamba, no se cargarán');
                        document.getElementById('coordenadas-array').value = '';
                    }
                } catch (e) {
                    console.error('Error al cargar coordenadas:', e);
                }
            }

            // Renderizar límites y leyenda
            L.rectangle(cochabambaBounds, {
                color: "#ff7800",
                weight: 1,
                fillOpacity: 0.1,
                interactive: false
            }).addTo(map);
            const legend = L.control({
                position: 'bottomright'
            });
            legend.onAdd = function(map) {
                const div = L.DomUtil.create('div', 'info legend');
                div.innerHTML =
                    '<i style="background:#ff7800; width: 15px; height: 15px; display: inline-block; margin-right: 5px; opacity: 0.5;"></i> Área de Cochabamba (Límites de Validación)';
                return div;
            };
            legend.addTo(map);

            // Geocoder/Search
            const geocoderControl = L.Control.geocoder({
                    defaultMarkGeocode: false,
                    placeholder: "Buscar zonas...",
                    geocoder: new L.Control.Geocoder.Nominatim(),
                    collapsed: false,
                    position: 'topleft'
                })
                .on('markgeocode', function(e) {
                    const bbox = e.geocode.bbox;
                    const poly = L.polygon([
                        bbox.getSouthEast(), bbox.getNorthEast(), bbox.getNorthWest(), bbox
                        .getSouthWest()
                    ]).addTo(map);
                    map.fitBounds(poly.getBounds());
                    map.removeLayer(poly);
                })
                .addTo(map);

            // TRUCO: Mover el elemento HTML del buscador del mapa a tu div externo
            document.getElementById('geocoder-search').appendChild(geocoderControl.getContainer());

            // Lógica de previsualización de archivos
            const previewFiles = function(input, previewContainer, isImage) {
                return function(e) {
                    const files = input.files;
                    previewContainer.innerHTML = '';

                    if (files) {
                        Array.from(files).forEach((file, index) => {
                            const reader = new FileReader();

                            reader.onload = function(event) {
                                const previewDiv = document.createElement('div');
                                previewDiv.className = 'col-md-3 preview-container';

                                if (isImage) {
                                    previewDiv.innerHTML = `
                                    <img src="${event.target.result}" class="img-fluid">
                                    <button type="button" class="remove-btn" data-index="${index}">×</button>
                                `;
                                } else {
                                    previewDiv.innerHTML = `
                                    <video controls class="img-fluid">
                                        <source src="${event.target.result}" type="${file.type}">
                                    </video>
                                    <button type="button" class="remove-btn" data-index="${index}">×</button>
                                `;
                                }

                                previewContainer.appendChild(previewDiv);

                                previewDiv.querySelector('.remove-btn').addEventListener(
                                    'click',
                                    function() {
                                        const dt = new DataTransfer();
                                        const inputFiles = input.files;
                                        const deletedIndex = parseInt(this.getAttribute(
                                            'data-index'));

                                        for (let i = 0; i < inputFiles.length; i++) {
                                            if (i !== deletedIndex) {
                                                dt.items.add(inputFiles[i]);
                                            }
                                        }

                                        input.files = dt.files;
                                        previewDiv.remove();

                                        // Reindexar los botones
                                        previewContainer.querySelectorAll('.remove-btn')
                                            .forEach((btn, newIndex) => {
                                                btn.setAttribute('data-index',
                                                    newIndex);
                                            });
                                    });
                            };

                            reader.readAsDataURL(file);
                        });
                    }
                };
            };

            const imagenesInput = document.querySelector('input[name="imagenes[]"]');
            const videosInput = document.querySelector('input[name="videos[]"]');
            const imagenesPreview = document.getElementById('imagenes-preview');
            const videosPreview = document.getElementById('videos-preview');

            if (imagenesInput) {
                imagenesInput.addEventListener('change', previewFiles(imagenesInput, imagenesPreview, true));
            }
            if (videosInput) {
                videosInput.addEventListener('change', previewFiles(videosInput, videosPreview, false));
            }

            // Actualizar etiquetas de archivos
            document.querySelectorAll('.custom-file-input').forEach(input => {
                input.addEventListener('change', function() {
                    const label = this.nextElementSibling;
                    const files = Array.from(this.files).map(f => f.name);

                    if (files.length === 0) {
                        label.textContent = this.id === 'imagenes' ?
                            'Seleccione una o más imágenes' : 'Seleccione uno o más videos';
                    } else if (files.length === 1) {
                        label.textContent = files[0];
                    } else {
                        label.textContent = `${files.length} archivos seleccionados`;
                    }
                });
            });

            // --- INICIO: Lógica de Captura del Mapa ---
            const zonaForm = document.getElementById('zona-form');
            zonaForm.addEventListener('submit', function(e) {
                const coords = document.getElementById('coordenadas-array').value;

                // Validación: debe haber coordenadas
                if (!coords || coords === '' || coords === '[]') {
                    e.preventDefault();
                    alert('¡Debe dibujar al menos un marcador o polígono en el mapa!');
                    return;
                }

                // Prevenir el envío normal para poder capturar el mapa
                e.preventDefault();

                // Asegurarse de que el mapa se renderice completamente antes de la captura
                map.invalidateSize();

                // Añadir un pequeño retraso para garantizar la renderización
                setTimeout(() => {
                    html2canvas(document.getElementById('map'), {
                        useCORS: true,
                        backgroundColor: null,
                        scale: 1
                    }).then(canvas => {

                        let imagenMapaInput = document.querySelector(
                            'input[name="imagen_mapa"]');
                        if (!imagenMapaInput) {
                            imagenMapaInput = document.createElement('input');
                            imagenMapaInput.type = 'hidden';
                            imagenMapaInput.name = 'imagen_mapa';
                            zonaForm.appendChild(imagenMapaInput);
                        }
                        // Comprimir la imagen para reducir el tamaño de la petición
                        imagenMapaInput.value = canvas.toDataURL('image/jpeg', 0.7);

                        // Reanudar el envío del formulario de forma nativa
                        zonaForm.submit();

                    }).catch(err => {
                        console.error("Error al capturar el mapa con html2canvas:", err);

                        // Preguntar al usuario si desea continuar sin la imagen del mapa
                        if (confirm(
                                "No se pudo generar la imagen del mapa. ¿Desea guardar la zona de todos modos?"
                            )) {

                            let imagenMapaInput = document.querySelector(
                                'input[name="imagen_mapa"]');
                            if (imagenMapaInput) {
                                imagenMapaInput.value = '';
                            }

                            zonaForm.submit();
                        } else {
                            // Si el usuario cancela, no se hace nada y el formulario no se envía.
                            // Se podría habilitar el botón de guardar de nuevo si estuviera deshabilitado.
                        }
                    });
                }, 200);
            });
        });
    </script>
@endpush
