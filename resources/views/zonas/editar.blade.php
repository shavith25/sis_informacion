@extends('layouts.app')

@section('title', 'Editar Zona: ' . $zona->nombre)

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">
            <i class="fas fa-edit mr-2"></i> Editando Zona: {{ $zona->nombre }}
        </h3>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></div>
            <div class="breadcrumb-item"><a href="{{ route('zonas.index') }}">Zonas</a></div>
            <div class="breadcrumb-item active">Editar</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-11 col-md-12">
                <div class="card shadow-lg custom-card-form">
                    
                    <div class="card-header custom-card-header">
                        <h4><i class="fas fa-layer-group mr-2"></i> Datos de la Zona y Geometría</h4>
                    </div>

                    @if (session('error'))
                        <div class="p-4">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-times-circle mr-2"></i>
                                <strong>Error:</strong> {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="p-4">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>Errores de validación:</strong>
                                <ul class="mb-0 mt-2 pl-4">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('zonas.update', $zona->id) }}" method="POST" enctype="multipart/form-data" id="zona-form">
                        @csrf
                        @method('PUT')
                        <div class="card-body custom-scroll-area p-4" style="max-height: calc(100vh - 400px); overflow-y: auto;">
                            
                            <input type="hidden" name="coordenadas" id="coordenadas-array"
                                value="{{ old('coordenadas', $zona->historial->first() ? json_encode($zona->historial->first()->coordenadas) : '') }}">
                            <input type="hidden" name="tipo_coordenada" id="tipo-coordenada"
                                value="{{ old('tipo_coordenada', $zona->historial->first() ? $zona->historial->first()->tipo_coordenada : '') }}">
                            <input type="hidden" id="historial-primero" value='@json($zona->historial->first() ? $zona->historial->first()->coordenadas : null)'>


                            <div class="row">
                                <div class="col-lg-6">
                                    <h5 class="text-primary mb-3"><i class="fas fa-info-circle mr-1"></i> Información General</h5>
                                    
                                    <div class="form-group">
                                        <label for="nombre" class="required-label" style="font-size: 14px;">
                                            <i class="fas fa-tag mr-1"></i> Nombre de la Zona:
                                        </label>
                                        <input type="text" name="nombre" id="nombre" class="form-control" required
                                            value="{{ old('nombre', $zona->nombre) }}" placeholder="Ej: Zona de seguridad A">
                                    </div>
    
                                    <div class="form-group">
                                        <label for="area_id" class="required-label" style="font-size: 14px;">
                                            <i class="fas fa-globe-americas mr-1"></i> Área Protegida:
                                        </label>
                                        <select name="area_id" id="area_id" class="form-control select2" required>
                                            <option value="">Seleccione un área protegida</option>
                                            @foreach ($areas as $area)
                                                <option value="{{ $area->id }}" {{ old('area_id', $zona->area_id) == $area->id ? 'selected' : '' }}>
                                                    {{ $area->area }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
    
                                    <div class="form-group">
                                        <label for="descripcion" style="font-size: 14px;">
                                            <i class="fas fa-file-alt mr-1"></i> Descripción:
                                        </label>
                                        <textarea name="descripcion" id="descripcion" class="form-control" style="height: 190px;"
                                            placeholder="Descripción detallada de la zona">{{ old('descripcion', $zona->descripcion) }}</textarea>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <h5 class="text-primary mb-3"><i class="fas fa-images mr-1"></i> Gestión Multimedia</h5>
                                    
                                    <div class="form-group media-section">
                                        <label style="font-size: 14px;">Imágenes Existentes:</label>
                                        <div class="row existing-media-row">
                                            @if (!empty($zona->imagenes) && is_iterable($zona->imagenes))
                                                @foreach($zona->imagenes as $imagen)
                                                    <div class="col-md-4 preview-container mb-3">
                                                        <img src="{{ asset('storage/' . $imagen->url) }}" class="img-fluid media-preview">
                                                        <label class="mt-2 d-block small">
                                                            <input type="checkbox" name="imagenes_eliminadas[]" value="{{ $imagen->url }}">
                                                            Eliminar
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @else
                                                <p class="col-12 text-muted small">No hay imágenes existentes.</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label style="font-size: 14px;"><i class="fas fa-plus-circle mr-1"></i> Agregar nuevas imágenes:</label>
                                        <div class="custom-file mb-2">
                                            <input type="file" class="custom-file-input" id="imagenes" name="imagenes[]" accept="image/*" multiple>
                                            <label class="custom-file-label" for="imagenes">Seleccione una o más imágenes</label>
                                        </div>
                                        <div id="imagenes-preview" class="row mt-3"></div>
                                    </div>

                                    <div class="form-group media-section">
                                        <label style="font-size: 14px;">Videos Existentes:</label>
                                        <div class="row existing-media-row">
                                            @if (!empty($zona->videos) && is_iterable($zona->videos))
                                                @foreach($zona->videos as $video)
                                                    <div class="col-md-4 preview-container mb-3">
                                                        <video controls class="img-fluid media-preview">
                                                            <source src="{{ asset('storage/' . $video->url) }}" type="video/mp4">
                                                        </video>
                                                        <label class="mt-2 d-block small">
                                                            <input type="checkbox" name="videos_eliminadas[]" value="{{ $video->url }}">
                                                            Eliminar
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @else
                                                <p class="col-12 text-muted small">No hay videos existentes.</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label style="font-size: 14px;"><i class="fas fa-plus-circle mr-1"></i> Agregar nuevos videos:</label>
                                        <div class="custom-file mb-2">
                                            <input type="file" class="custom-file-input" id="videos" name="videos[]" accept="video/mp4,video/quicktime" multiple>
                                            <label class="custom-file-label" for="videos">Seleccione uno o más videos</label>
                                        </div>
                                        <div id="videos-preview" class="row mt-3"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4">

                            <h5 class="text-primary mb-3"><i class="fas fa-map-pin mr-1"></i> Geometría de la Zona</h5>
                            <div class="form-group">
                                <label for="map" class="required-label" style="font-size: 14px;"><i class="fas fa-pencil-alt"></i> Opciones de Dibujo:</label>
                                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                    <div class="btn-group mb-2" role="group">
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="draw-marker">
                                            <i class="fas fa-map-marker-alt"></i> Marcador
                                        </button>
                                        <button type="button" class="btn btn-outline-success btn-sm" id="draw-polygon">
                                            <i class="fas fa-draw-polygon"></i> Polígono
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm" id="clear-all">
                                            <i class="fas fa-trash-alt"></i> Limpiar
                                        </button>
                                    </div>
                                </div>

                                <div id="map"></div>
                                <small class="form-text text-muted">
                                    Dibuje al menos un marcador o polígono en el mapa (dentro de Cochabamba) para definir la zona.
                                </small>
                            </div>
                            
                            <hr class="my-4">

                            <h5 class="text-primary mb-3"><i class="fas fa-history mr-1"></i> Historial de Geometría</h5>
                            <div class="form-group">
                                <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                                    <table class="table table-striped table-sm table-bordered">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Tipo</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($zona->historial as $historial)
                                            <tr>
                                                <td>{{ $historial->created_at->format('d/m/Y H:i') }}</td>
                                                <td>{{ ucfirst($historial->tipo_coordenada) }}</td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-info view-history"
                                                            data-coordenadas="{{ json_encode($historial->coordenadas) }}"
                                                            data-toggle="tooltip" title="Ver en el mapa">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3" class="text-center">No hay historial de geometría.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        
                        <div class="card-footer text-center py-3">
                            <button type="submit" class="btn btn-primary btn-action-custom mr-3">
                                <i class="fas fa-sync-alt"></i> Actualizar Zona
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

    .card-footer {
        border-top: 1px solid #adb5bd transparent;
        background-color: #adb5bd transparent;
        border-radius: 0 0 12px 12px;
    }

    /* SCROLL SOLO EN EL CONTENIDO */
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
    
    /* Mapa */
    #map {
        height: 500px;
        width: 100%;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border: 1px solid #adb5bd;
    }
    
    /* Previsualizaciones Multimedia */
    .preview-container {
        position: relative;
        padding: 5px;
    }

    .media-preview {
        max-width: 100%;
        max-height: 120px;
        border: 1px solid #adb5bd;
        border-radius: 4px;
        display: block;
        margin: auto;
        object-fit: cover;
    }

    .custom-file-label::after {
        content: "Examinar";
    }

    .search-container {
        width: 250px;
    }
    
    /* Historial */
    .table-responsive {
        border: 1px solid #adb5bd;
        border-radius: 8px;
    }
    
    .table th {
        background-color: #adb5bd;
        font-weight: 700;
    }
</style>
@endpush

@push('js')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
let map;
let drawnItems;

document.addEventListener('DOMContentLoaded', function() {
    // 1. Configuración del mapa centrado en Cochabamba
    const cochabambaBounds = L.latLngBounds(
        L.latLng(-18.50, -67.50), 
        L.latLng(-16.00, -64.00) 
    );

    map = L.map('map', {
        minZoom: 9,
        maxZoom: 18,
        maxBounds: cochabambaBounds
    }).setView([-17.3895, -66.1568], 16); 

    L.layerGroup([
        L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: '&copy; Esri, i-cubed, USDA, USGS, etc.',
        }),
        L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Labels &copy; Esri'
        })
    ]).addTo(map);

    // 2. Capa de elementos dibujados
    drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);
    
    // 3. Controles de Dibujo
    const drawControl = new L.Control.Draw({
        position: 'topright',
        draw: {
            polygon: {
                allowIntersection: false,
                drawError: { color: '#b00b00', message: '¡No puedes dibujar fuera de Cochabamba!' },
                shapeOptions: { color: '#3388ff', fillColor: '#3388ff', fillOpacity: 0.2 }
            },
            marker: { icon: new L.Icon.Default() },
            polyline: false, circle: false, rectangle: false, circlemarker: false
        },
        edit: { featureGroup: drawnItems, remove: true }
    });
    map.addControl(drawControl);
    
    // 4. Funciones Auxiliares (Validación y Actualización)
    function estaEnCochabamba(lat, lng) {
        return lat >= cochabambaBounds.getSouth() &&
            lat <= cochabambaBounds.getNorth() &&
            lng >= cochabambaBounds.getWest() &&
            lng <= cochabambaBounds.getEast();
    }

    function updateCoordenadasInput() {
        const coordenadas = [];
        let fueraDeLimites = false;

        drawnItems.eachLayer(function(layer) {
            if (layer instanceof L.Marker) {
                const latlng = layer.getLatLng();
                if (!estaEnCochabamba(latlng.lat, latlng.lng)) { fueraDeLimites = true; return; }
                coordenadas.push({ tipo: 'marcador', coordenadas: { lat: latlng.lat, lng: latlng.lng } });
            } else if (layer instanceof L.Polygon) {
                const points = layer.getLatLngs()[0];
                const puntosFuera = points.some(latlng => !estaEnCochabamba(latlng.lat, latlng.lng));

                if (puntosFuera) { fueraDeLimites = true; return; }
                coordenadas.push({ tipo: 'poligono', coordenadas: points.map(latlng => ({ lat: latlng.lat, lng: latlng.lng })) });
            }
        });

        if (fueraDeLimites) {
            Swal.fire('Error', 'Se detectaron puntos fuera de Cochabamba. Limpiando el mapa...', 'error');
            drawnItems.clearLayers();
            document.getElementById('coordenadas-array').value = '';
            return;
        }

        document.getElementById('coordenadas-array').value = JSON.stringify(coordenadas);

        const tipos = coordenadas.map(c => c.tipo);
        const tipoCoordenada = tipos.includes('poligono') ?
            (tipos.includes('marcador') ? 'mixto' : 'poligono') : 'marcador';
        document.getElementById('tipo-coordenada').value = tipoCoordenada;
    }
    
    // 5. Manejo de Eventos del Mapa
    map.on(L.Draw.Event.CREATED, function(e) {
        const layer = e.layer;
        
        if (layer instanceof L.Marker) {
            if (!estaEnCochabamba(layer.getLatLng().lat, layer.getLatLng().lng)) {
                map.removeLayer(layer);
                return Swal.fire('Error', 'No puedes dibujar marcadores fuera de Cochabamba.', 'error');
            }
            layer.options.draggable = true;
            layer.on('dragend', updateCoordenadasInput);
        } else if (layer instanceof L.Polygon) {
            layer.on('edit', updateCoordenadasInput);
        }

        drawnItems.addLayer(layer);
        updateCoordenadasInput();
    });

    map.on(L.Draw.Event.EDITED, updateCoordenadasInput);
    map.on(L.Draw.Event.DELETED, updateCoordenadasInput);

    // 6. Carga de Datos Existentes
    const oldCoordenadas = document.getElementById('coordenadas-array').value;
    if (oldCoordenadas) {
        try {
            const coordenadas = JSON.parse(oldCoordenadas);
            let coordenadasValidas = true;

            // Validación rápida antes de cargar
            for (const item of coordenadas) {
                if (item.tipo === 'marcador' && !estaEnCochabamba(item.coordenadas.lat, item.coordenadas.lng)) { coordenadasValidas = false; break; }
                if (item.tipo === 'poligono' && item.coordenadas.some(coord => !estaEnCochabamba(coord.lat, coord.lng))) { coordenadasValidas = false; break; }
            }

            if (coordenadasValidas) {
                coordenadas.forEach(item => {
                    if (item.tipo === 'marcador') {
                        const marker = L.marker([item.coordenadas.lat, item.coordenadas.lng], { draggable: true });
                        marker.on('dragend', updateCoordenadasInput);
                        drawnItems.addLayer(marker);
                    } else if (item.tipo === 'poligono') {
                        const latlngs = item.coordenadas.map(coord => [coord.lat, coord.lng]);
                        const polygon = L.polygon(latlngs, { color: '#3388ff', fillColor: '#3388ff', fillOpacity: 0.2 });
                        polygon.on('edit', updateCoordenadasInput);
                        drawnItems.addLayer(polygon);
                    }
                });

                if (drawnItems.getLayers().length > 0) { map.fitBounds(drawnItems.getBounds()); }
            } else {
                console.warn('Coordenadas existentes fuera de Cochabamba, no cargadas.');
                document.getElementById('coordenadas-array').value = '';
            }
        } catch (e) {
            console.error('Error al cargar coordenadas JSON:', e);
        }
    }
    
    // 7. Botones Personalizados
    document.getElementById('draw-marker').addEventListener('click', function() { new L.Draw.Marker(map, drawControl.options.draw.marker).enable(); });
    document.getElementById('draw-polygon').addEventListener('click', function() { new L.Draw.Polygon(map, drawControl.options.draw.polygon).enable(); });
    document.getElementById('clear-all').addEventListener('click', function() { drawnItems.clearLayers(); updateCoordenadasInput(); });
    
    // 8. Visualización de Historial
    document.querySelectorAll('.view-history').forEach(btn => {
        btn.addEventListener('click', function() {
            const historialCoords = JSON.parse(this.getAttribute('data-coordenadas'));
            drawnItems.clearLayers(); 

            // Cargar historial en rojo
            historialCoords.forEach(item => {
                const color = '#7f8c8d';
                if (item.tipo === 'marcador') {
                    drawnItems.addLayer(L.marker([item.coordenadas.lat, item.coordenadas.lng], { icon: L.divIcon({className: 'history-icon', html: `<i class="fas fa-map-marker-alt" style="color:${color}; font-size: 24px;"></i>`}) }));
                } else if (item.tipo === 'poligono') {
                    const latlngs = item.coordenadas.map(coord => [coord.lat, coord.lng]);
                    drawnItems.addLayer(L.polygon(latlngs, { color: color, fillColor: color, fillOpacity: 0.2 }));
                }
            });

            if (drawnItems.getLayers().length > 0) { map.fitBounds(drawnItems.getBounds()); }

            // Alerta con opción para restaurar la vista de edición
            Swal.fire({
                title: 'Vista Histórica',
                html: 'El mapa muestra la geometría antigua de la zona (en rojo).<br><br>¿Desea volver a la versión de edición actual?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Sí, volver a editar',
                cancelButtonText: 'Cerrar'
            }).then((result) => {
                if (result.isConfirmed) {
                    drawnItems.clearLayers();
                    // Recargar la geometría actual para edición
                    const currentCoords = JSON.parse(document.getElementById('coordenadas-array').value);
                    if (currentCoords) {
                        currentCoords.forEach(item => {
                            if (item.tipo === 'marcador') {
                                const marker = L.marker([item.coordenadas.lat, item.coordenadas.lng], { draggable: true });
                                marker.on('dragend', updateCoordenadasInput);
                                drawnItems.addLayer(marker);
                            } else if (item.tipo === 'poligono') {
                                const latlngs = item.coordenadas.map(coord => [coord.lat, coord.lng]);
                                const polygon = L.polygon(latlngs, { color: '#3388ff', fillColor: '#3388ff', fillOpacity: 0.2 });
                                polygon.on('edit', updateCoordenadasInput);
                                drawnItems.addLayer(polygon);
                            }
                        });
                        map.fitBounds(drawnItems.getBounds());
                    }
                }
            });
        });
    });
    
    // 9. Manejo de Archivos y Previsualización (Ajustada para edición)
    const previewFiles = function(input, previewContainer, isImage) {
        return function(e) {
            const files = input.files;
            previewContainer.innerHTML = ''; 

            if (files) {
                Array.from(files).forEach((file, index) => {
                    const reader = new FileReader();

                    reader.onload = function(event) {
                        const previewDiv = document.createElement('div');
                        // Usar col-md-4 para 3 en fila
                        previewDiv.className = 'col-md-4 preview-container mb-3'; 

                        if (isImage) {
                            previewDiv.innerHTML = `
                                <img src="${event.target.result}" class="img-fluid media-preview">
                                <button type="button" class="remove-btn" data-index="${index}">×</button>
                            `;
                        } else {
                            previewDiv.innerHTML = `
                                <video controls class="img-fluid media-preview">
                                    <source src="${event.target.result}" type="${file.type}">
                                </video>
                                <button type="button" class="remove-btn" data-index="${index}">×</button>
                            `;
                        }

                        previewContainer.appendChild(previewDiv);

                        previewDiv.querySelector('.remove-btn').addEventListener('click', function() {
                            const dt = new DataTransfer();
                            const inputFiles = input.files;
                            const deletedIndex = parseInt(this.getAttribute('data-index'));

                            for (let i = 0; i < inputFiles.length; i++) {
                                if (i !== deletedIndex) {
                                    dt.items.add(inputFiles[i]);
                                }
                            }

                            input.files = dt.files;
                            previewDiv.remove();
                            // Reindexar botones
                            previewContainer.querySelectorAll('.remove-btn').forEach((btn, newIndex) => { btn.setAttribute('data-index', newIndex); });
                        });
                    };

                    reader.readAsDataURL(file);
                });
            }
        };
    };

    const imagenesInput = document.querySelector('input#imagenes');
    const videosInput = document.querySelector('input#videos');
    const imagenesPreview = document.getElementById('imagenes-preview');
    const videosPreview = document.getElementById('videos-preview');

    if (imagenesInput) { imagenesInput.addEventListener('change', previewFiles(imagenesInput, imagenesPreview, true)); }
    if (videosInput) { videosInput.addEventListener('change', previewFiles(videosInput, videosPreview, false)); }

    // Actualizar etiquetas de archivos
    document.querySelectorAll('.custom-file-input').forEach(input => {
        input.addEventListener('change', function() {
            const label = this.nextElementSibling;
            const files = Array.from(this.files).map(f => f.name);

            if (files.length === 0) {
                label.textContent = 'Seleccione una o más imágenes';
            } else if (files.length === 1) {
                label.textContent = files[0];
            } else {
                label.textContent = `${files.length} archivos seleccionados`;
            }
        });
    });

    // 10. Geocoder y Leyenda
    L.Control.geocoder({
        defaultMarkGeocode: false,
        placeholder: "Buscar...",
        geocoder: new L.Control.Geocoder.Nominatim() 
    }).addTo(map);

    const legend = L.control({position: 'bottomright'});
    legend.onAdd = function(map) {
        const div = L.DomUtil.create('div', 'info legend');
        div.innerHTML = '<i style="background:#ff7800"></i> Área de Cochabamba (Validación)';
        return div;
    };
    legend.addTo(map);
    
    // Captura de Mapa
    document.getElementById('zona-form').addEventListener('submit', function(e) {
        e.preventDefault();

        map.invalidateSize();
        
        if (!document.getElementById('coordenadas-array').value) {
            return Swal.fire('Error', "Debe dibujar al menos un marcador o polígono en el mapa.", 'error');
        }

        setTimeout(() => {
            html2canvas(document.getElementById('map'), { useCORS: true, backgroundColor: null, scale: 1 
            }).then(canvas => {
                const resizedCanvas = document.createElement('canvas');
                const ctx = resizedCanvas.getContext('2d');
                const scaleFactor = 0.5; 

                resizedCanvas.width = canvas.width * scaleFactor;
                resizedCanvas.height = canvas.height * scaleFactor;
                ctx.drawImage(canvas, 0, 0, resizedCanvas.width, resizedCanvas.height);

                const base64Image = resizedCanvas.toDataURL('image/jpeg', 0.6); 

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'imagen_mapa';
                input.value = base64Image;
                document.getElementById('zona-form').appendChild(input);

                document.getElementById('zona-form').submit();
            }).catch(err => {
                console.error("Error al capturar el mapa:", err);
                document.getElementById('zona-form').submit();
            });
        }, 100); 
    });

});

// 11. Funcionalidad de Búsqueda para el campo HTML
const searchBox = document.getElementById('search-box');
const searchResults = document.getElementById('search-results');
const geocoderInstance = L.Control.Geocoder.nominatim();

if (searchBox) {
    let currentTimeout = null;

    searchBox.addEventListener('keyup', function(e) {
        clearTimeout(currentTimeout);
        const query = searchBox.value.trim();

        if (query.length < 3) {
            searchResults.style.display = 'none';
            return;
        }

        // Realizar búsqueda con retardo para evitar sobrecarga (debounce)
        currentTimeout = setTimeout(() => {
            geocoderInstance.geocode(query, results => {
                searchResults.innerHTML = '';
                searchResults.style.display = 'block';

                if (results.length === 0) {
                    searchResults.innerHTML = '<li class="list-group-item text-muted small">No se encontraron resultados.</li>';
                    return;
                }

                results.forEach(result => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item list-group-item-action small';
                    li.textContent = result.name;
                    li.style.cursor = 'pointer';

                    li.addEventListener('click', function() {
                        // Zoom al resultado de la búsqueda
                        map.fitBounds(result.bbox); 
                        searchResults.style.display = 'none';
                        searchBox.value = result.name; 

                        // Opcional: añadir un marcador temporal
                        L.marker(result.center).addTo(map).bindPopup(result.name).openPopup();
                    });
                    searchResults.appendChild(li);
                });
            });
        }, 500); // 500ms de retardo
    });

    // Ocultar resultados si se hace clic fuera
    document.addEventListener('click', function(e) {
        if (!searchBox.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
}
</script>
@endpush