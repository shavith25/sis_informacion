@extends('layouts.app')

@section('title', 'Registrar Nueva Zona')

@section('content')
    <section class="section" style="height: calc(100vh - 120px); display: flex; flex-direction: column;">
        <div class="section-header flex">
            <h3 class="page__heading">
                <i class="fas fa-map-marked-alt mr-2"></i> Registrar Nueva Zona
            </h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('zonas.index') }}">Zonas</a></div>
                <div class="breadcrumb-item active">Crear</div>
            </div>
        </div>

        <div class="section-body flex" style="overflow: hidden;">
            <div class="row h-100">
                <div class="col-12 h-100">
                    <div class="card shadow-lg custom-card-form h-100">

                        <div class="card-header custom-card-header text-white flex">
                            <h4><i class="fas fa-edit mr-2"></i> Formulario de Registro de Zona</h4>
                        </div>

                        <form action="{{ route('zonas.store') }}" method="POST" enctype="multipart/form-data"
                            id="zona-form" class="d-flex flex-column h-100" style="overflow: hidden;">
                            @csrf
                            <input type="hidden" name="coordenadas" id="coordenadas-array"
                                value="@json(old('coordenadas'))">
                            <input type="hidden" name="tipo_coordenada" id="tipo-coordenada">

                            <div class="card-body p-4 scrollable-body">

                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                        <div class="alert-body">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                            <strong>Por favor corrige los siguientes errores:</strong>
                                            <ul class="mb-0 mt-2 pl-4">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-lg-5">
                                        <h6 class="text-primary mb-3 font-weight-bold text-uppercase">
                                            <i class="fas fa-info-circle mr-1"></i> Información General
                                        </h6>

                                        <div class="form-group">
                                            <label for="nombre" class="required-label">Nombre de la Zona</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                </div>
                                                <input type="text" name="nombre" id="nombre" class="form-control"
                                                    required value="{{ old('nombre') }}"
                                                    placeholder="Ej: Zona Norte - Parque Tunari">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="area_id" class="required-label">Área Protegida Asociada</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-globe-americas"></i></span>
                                                </div>
                                                <select name="area_id" id="area_id" class="form-control select2" required>
                                                    <option value="">-- Seleccione Área Protegida --</option>
                                                    @foreach ($areas as $area)
                                                        <option value="{{ $area->id }}"
                                                            {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                                            {{ $area->area }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="descripcion">Descripción</label>
                                            <textarea name="descripcion" id="descripcion" class="form-control" style="height: 100px;"
                                                placeholder="Detalles adicionales...">{{ old('descripcion') }}</textarea>
                                        </div>

                                        <hr>

                                        <h6 class="text-primary mb-3 font-weight-bold text-uppercase">
                                            <i class="fas fa-images mr-1"></i> Multimedia
                                        </h6>

                                        <div class="form-group">
                                            <label>Imágenes</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="imagenes"
                                                    name="imagenes[]" accept="image/*" multiple>
                                                <label class="custom-file-label" for="imagenes">Examinar...</label>
                                            </div>
                                            <div id="imagenes-preview" class="row mt-2 px-2"></div>
                                        </div>

                                        <div class="form-group">
                                            <label>Videos</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="videos"
                                                    name="videos[]" accept="video/mp4,video/quicktime" multiple>
                                                <label class="custom-file-label" for="videos">Examinar...</label>
                                            </div>
                                            <div id="videos-preview" class="row mt-2 px-2"></div>
                                        </div>
                                    </div>

                                    {{-- COLUMNA DERECHA: MAPA --}}
                                    <div class="col-lg-7 border-left-lg">
                                        <h6
                                            class="text-primary mb-3 font-weight-bold text-uppercase d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-map-marked-alt mr-1"></i> Ubicación Geográfica</span>
                                            <small class="text-muted font-weight-normal" style="font-size: 0.75rem;">
                                                <i class="fas fa-mouse-pointer"></i> Dibuja en el mapa
                                            </small>
                                        </h6>

                                        <div class="map-controls mb-2 d-flex justify-content-between">
                                            <div id="geocoder-search" class="flex mr-2"></div>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-outline-primary btn-sm"
                                                    id="draw-marker" title="Marcador">
                                                    <i class="fas fa-map-marker-alt"> Marcador</i>
                                                </button>
                                                <button type="button" class="btn btn-outline-success btn-sm"
                                                    id="draw-polygon" title="Polígono">
                                                    <i class="fas fa-draw-polygon"> Dibujar</i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                    id="clear-all" title="Borrar Todo">
                                                    <i class="fas fa-trash-alt"> Eliminar</i>
                                                </button>
                                            </div>
                                        </div>

                                        <div id="map"
                                            style="height: 500px; border-radius: 8px; border: 2px solid #e9ecef;"></div>
                                        <small class="form-text text-muted mt-2 text-center">
                                            * Dibuja dentro de los límites de Cochabamba (rectángulo naranja).
                                        </small>
                                    </div>
                                </div>
                            </div>

                            {{-- PIE DE PÁGINA (Botones fijos abajo) --}}
                            <div class="card-footer bg-whitesmoke text-right flex">
                                <a href="{{ route('zonas.index') }}" class="btn btn-secondary mr-2">
                                    <i class="fas fa-times mr-1"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg px-4 btn-create">
                                    <i class="fas fa-save mr-1"></i> Guardar Zona
                                </button>
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
        .scrollable-body {
            overflow-y: auto;
            flex-grow: 1;
            scrollbar-width: thin;
            scrollbar-color: #adb5bd #f1f1f1;
        }

        /* Scrollbar estilizado (Chrome/Safari) */
        .scrollable-body::-webkit-scrollbar {
            width: 8px;
        }

        .scrollable-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .scrollable-body::-webkit-scrollbar-thumb {
            background-color: #c1c1c1;
            border-radius: 4px;
        }

        .scrollable-body::-webkit-scrollbar-thumb:hover {
            background-color: #a8a8a8;
        }

        /* ESTILO UNIFICADO (Igual que Áreas) */
        .custom-card-header {
            background-color: #5d75e8;
            border-bottom: 3px solid #6777ef;
        }

        .btn-create {
            background-color: #2f55d4 !important;
            border-color: #2f55d4 !important;
            box-shadow: 0 2px 6px rgba(47, 85, 212, 0.4);
        }

        .btn-create:hover {
            background-color: #2040a5 !important;
            transform: translateY(-1px);
        }

        .required-label:after {
            content: " *";
            color: #dc3545;
            font-weight: bold;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-color: #e4e6fc;
            color: #6c757d;
        }

        @media (min-width: 992px) {
            .border-left-lg {
                border-left: 1px solid #eee;
            }
        }

        .preview-container {
            position: relative;
            margin-bottom: 10px;
            text-align: center;
        }

        .preview-container img,
        .preview-container video {
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-height: 100px;
            object-fit: cover;
        }

        .remove-btn {
            position: absolute;
            top: -5px;
            right: 5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            padding: 0;
            font-size: 10px;
            border: 2px solid white;
            cursor: pointer;
        }

        /* Ajuste para que el mapa no se rompa al redimensionar */
        #map {
            z-index: 1;
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

            map.on(L.Draw.Event.CREATED, function(e) {
                const layer = e.layer;
                let fueraDeLimites = false;

                if (layer instanceof L.Marker) {
                    const latlng = layer.getLatLng();
                    if (!estaEnCochabamba(latlng.lat, latlng.lng)) fueraDeLimites = true;
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
                        if (points.some(latlng => !estaEnCochabamba(latlng.lat, latlng.lng))) {
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

            const oldCoordenadas = document.getElementById('coordenadas-array').value;
            if (oldCoordenadas) {
                try {
                    const coordenadas = JSON.parse(oldCoordenadas);
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
                    if (drawnItems.getLayers().length > 0) map.fitBounds(drawnItems.getBounds());
                } catch (e) {
                    console.error('Error al cargar coordenadas:', e);
                }
            }

            L.rectangle(cochabambaBounds, {
                color: "#ff7800",
                weight: 1,
                fillOpacity: 0.1,
                interactive: false
            }).addTo(map);

            const geocoderControl = L.Control.geocoder({
                defaultMarkGeocode: false,
                placeholder: "Buscar zonas...",
                geocoder: new L.Control.Geocoder.Nominatim(),
                collapsed: false,
                position: 'topleft'
            }).on('markgeocode', function(e) {
                const bbox = e.geocode.bbox;
                const poly = L.polygon([bbox.getSouthEast(), bbox.getNorthEast(), bbox.getNorthWest(), bbox
                    .getSouthWest()
                ]).addTo(map);
                map.fitBounds(poly.getBounds());
                map.removeLayer(poly);
            }).addTo(map);
            document.getElementById('geocoder-search').appendChild(geocoderControl.getContainer());

            // Lógica archivos...
            const previewFiles = function(input, previewContainer, isImage) {
                return function(e) {
                    const files = input.files;
                    previewContainer.innerHTML = '';
                    if (files) {
                        Array.from(files).forEach((file, index) => {
                            const reader = new FileReader();
                            reader.onload = function(event) {
                                const previewDiv = document.createElement('div');
                                previewDiv.className = 'col-md-4 preview-container';
                                const content = isImage ?
                                    `<img src="${event.target.result}" class="img-fluid">` :
                                    `<video controls class="img-fluid"><source src="${event.target.result}" type="${file.type}"></video>`;
                                previewDiv.innerHTML =
                                    `${content}<button type="button" class="remove-btn" data-index="${index}">×</button>`;
                                previewContainer.appendChild(previewDiv);

                                previewDiv.querySelector('.remove-btn').addEventListener(
                                    'click',
                                    function() {
                                        const dt = new DataTransfer();
                                        const inputFiles = input.files;
                                        const deletedIndex = parseInt(this.getAttribute(
                                            'data-index'));
                                        for (let i = 0; i < inputFiles.length; i++) {
                                            if (i !== deletedIndex) dt.items.add(inputFiles[
                                                i]);
                                        }
                                        input.files = dt.files;
                                        previewDiv.remove();
                                    });
                            };
                            reader.readAsDataURL(file);
                        });
                    }
                };
            };                 

            // Submit logic...
            const zonaForm = document.getElementById('zona-form');
            zonaForm.addEventListener('submit', function(e) {
                const coords = document.getElementById('coordenadas-array').value;
                if (!coords || coords === '' || coords === '[]') {
                    e.preventDefault();
                    alert('¡Debe dibujar al menos un marcador o polígono en el mapa!');
                    return;
                }
                e.preventDefault();
                map.invalidateSize();
                setTimeout(() => {
                    html2canvas(document.getElementById('map'), {
                        useCORS: true,
                        backgroundColor: null,
                        scale: 1
                    }).then(canvas => {
                        let imInput = document.querySelector('input[name="imagen_mapa"]');
                        if (!imInput) {
                            imInput = document.createElement('input');
                            imInput.type = 'hidden';
                            imInput.name = 'imagen_mapa';
                            zonaForm.appendChild(imInput);
                        }
                        imInput.value = canvas.toDataURL('image/jpeg', 0.7);
                        zonaForm.submit();
                    }).catch(err => {
                        if (confirm("No se pudo generar imagen del mapa. ¿Guardar igual?"))
                            zonaForm.submit();
                    });
                }, 200);
            });
        });
    </script>
@endpush
