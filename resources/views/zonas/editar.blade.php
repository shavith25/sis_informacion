@extends('layouts.app')

@section('title', 'Editar Zona: ' . $zona->nombre)

@section('content')
<section class="section" style="height: calc(100vh - 120px); display: flex; flex-direction: column;">
    
    <div class="section-header" style="flex-shrink: 0;">
        <h3 class="page__heading">
            <i class="fas fa-edit mr-2"></i> Editando Zona: {{ $zona->nombre }}
        </h3>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></div>
            <div class="breadcrumb-item"><a href="{{ route('zonas.index') }}">Zonas</a></div>
            <div class="breadcrumb-item active">Editar</div>
        </div>
    </div>

    <div class="section-body" style="flex-grow: 1; overflow: hidden; padding-bottom: 10px;">
        <div class="row justify-content-center h-100">
            <div class="col-xl-12 col-lg-12 h-100">
                <div class="card shadow-lg custom-card-form h-100" style="display: flex; flex-direction: column;">
                    <div class="card-header custom-card-header" style="flex-shrink: 0;">
                        <h4><i class="fas fa-layer-group mr-2"></i> Datos de la Zona y Geometría</h4>
                    </div>

                    {{-- MENSAJES DE ERROR: Fijos si aparecen --}}
                    @if (session('error') || $errors->any())
                        <div class="p-3" style="flex-shrink: 0;">
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                                    <i class="fas fa-times-circle mr-2"></i> {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show mb-0 mt-2" role="alert">
                                    <strong><i class="fas fa-exclamation-triangle mr-2"></i> Corrige los errores:</strong>
                                    <ul class="mb-0 mt-1 pl-4">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                            @endif
                        </div>
                    @endif

            
                    <form action="{{ route('zonas.update', $zona) }}" method="POST" enctype="multipart/form-data" id="zona-form" 
                        style="display: flex; flex-direction: column; flex: 1; min-height: 0;">
                        @csrf
                        @method('PUT')

                        <div class="card-body p-4" style="overflow-y: auto; flex: 1;">
                            
                            <input type="hidden" name="coordenadas" id="coordenadas-array"
                                value="{{ old('coordenadas', $zona->historial->first() ? json_encode($zona->historial->first()->coordenadas) : '') }}">
                            <input type="hidden" name="tipo_coordenada" id="tipo-coordenada"
                                value="{{ old('tipo_coordenada', $zona->historial->first() ? $zona->historial->first()->tipo_coordenada : '') }}">

                            <div class="row">
                                <div class="col-lg-5">
                                    <h6 class="text-primary font-weight-bold text-uppercase mb-3"><i class="fas fa-info-circle mr-1"></i> Información General</h6>
                                    
                                    <div class="form-group">
                                        <label for="nombre" class="required-label">Nombre de la Zona</label>
                                        <input type="text" name="nombre" id="nombre" class="form-control" required
                                            value="{{ old('nombre', $zona->nombre) }}">
                                    </div>
    
                                    <div class="form-group">
                                        <label for="area_id" class="required-label">Área Protegida</label>
                                        <select name="area_id" id="area_id" class="form-control select2" required>
                                            <option value="">Seleccione un área</option>
                                            @foreach ($areas as $area)
                                                <option value="{{ $area->id }}" {{ old('area_id', $zona->area_id) == $area->id ? 'selected' : '' }}>
                                                    {{ $area->area }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
    
                                    <div class="form-group">
                                        <label for="descripcion">Descripción</label>
                                        <textarea name="descripcion" id="descripcion" class="form-control" style="height: 120px;">{{ old('descripcion', $zona->descripcion) }}</textarea>
                                    </div>

                                    <hr>

                                    <h6 class="text-primary font-weight-bold text-uppercase mb-3"><i class="fas fa-photo-video mr-1"></i> Multimedia</h6>
                                    
                                    <div class="form-group">
                                        <label class="d-block mb-2 text-muted small font-weight-bold">IMÁGENES ACTUALES (Marcar para eliminar)</label>
                                        <div class="row existing-media-row px-2">
                                            @forelse($zona->imagenes as $imagen)
                                                <div class="col-6 col-md-4 preview-container mb-2 text-center">
                                                    <div class="media-wrapper">
                                                        <img src="{{ asset('storage/' . $imagen->url) }}" class="img-fluid rounded border">
                                                        <div class="custom-control custom-checkbox mt-1">
                                                            <input type="checkbox" class="custom-control-input" id="del_img_{{ $loop->index }}" name="imagenes_eliminadas[]" value="{{ $imagen->url }}">
                                                            <label class="custom-control-label small text-danger" for="del_img_{{ $loop->index }}">Eliminar</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="col-12 text-muted small font-italic">No hay imágenes cargadas.</p>
                                            @endforelse
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="d-block mb-2 text-muted small font-weight-bold">VIDEOS ACTUALES (Marcar para eliminar)</label>
                                        <div class="row existing-media-row px-2">
                                            @forelse($zona->videos as $video)
                                                <div class="col-6 col-md-4 preview-container mb-2 text-center">
                                                    <div class="media-wrapper">
                                                        <video class="img-fluid rounded border" style="height: 80px; width:100%; object-fit:cover;">
                                                            <source src="{{ asset('storage/' . $video->url) }}" type="video/mp4">
                                                        </video>
                                                        <div class="custom-control custom-checkbox mt-1">
                                                            <input type="checkbox" class="custom-control-input" id="del_vid_{{ $loop->index }}" name="videos_eliminadas[]" value="{{ $video->url }}">
                                                            <label class="custom-control-label small text-danger" for="del_vid_{{ $loop->index }}">Eliminar</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="col-12 text-muted small font-italic">No hay videos cargados.</p>
                                            @endforelse
                                        </div>
                                    </div>

                                    <div class="form-group bg-light p-3 rounded">
                                        <label class="small font-weight-bold">AGREGAR NUEVOS ARCHIVOS:</label>
                                        <div class="custom-file mb-2">
                                            <input type="file" class="custom-file-input" id="imagenes" name="imagenes[]" accept="image/*" multiple>
                                            <label class="custom-file-label" for="imagenes">Subir Imágenes</label>
                                        </div>
                                        <div id="imagenes-preview" class="row mt-2"></div>

                                        <div class="custom-file mt-2">
                                            <input type="file" class="custom-file-input" id="videos" name="videos[]" accept="video/mp4,video/quicktime" multiple>
                                            <label class="custom-file-label" for="videos">Subir Videos</label>
                                        </div>
                                        <div id="videos-preview" class="row mt-2"></div>
                                    </div>
                                </div>

                                {{-- COLUMNA DERECHA --}}
                                <div class="col-lg-7 border-left-lg">
                                    <h6 class="text-primary font-weight-bold text-uppercase mb-3 d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-map-marked-alt mr-1"></i> Geometría</span>
                                        <small class="text-muted font-weight-normal text-none-transform">Edite el dibujo en el mapa</small>
                                    </h6>
                                    
                                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                                        <div class="btn-group" role="group">
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

                                    <div id="map" style="height: 500px; width: 100%; border-radius: 8px; border: 2px solid #e9ecef;"></div>
                                    
                                    <div class="mt-4">
                                        <h6 class="text-primary font-weight-bold text-uppercase mb-2"><i class="fas fa-history mr-1"></i> Historial de Cambios</h6>
                                        <div class="table-responsive" style="max-height: 200px;">
                                            <table class="table table-sm table-hover mb-0">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Fecha</th>
                                                        <th>Tipo</th>
                                                        <th class="text-right">Ver</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($zona->historial as $historial)
                                                    <tr>
                                                        <td>{{ $historial->created_at->format('d/m/Y H:i') }}</td>
                                                        <td><span class="badge badge-light">{{ ucfirst($historial->tipo_coordenada) }}</span></td>
                                                        <td class="text-right">
                                                            <button type="button" class="btn btn-sm btn-info view-history"
                                                                    data-coordenadas="{{ json_encode($historial->coordenadas) }}" title="Ver en el mapa">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr><td colspan="3" class="text-center text-muted">Sin historial previo.</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-whitesmoke text-left" style="flex-shrink: 0;">
                            <a href="{{ route('zonas.index') }}" class="btn btn-secondary mr-2">
                                <i class="fas fa-times mr-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-4 btn-create">
                                <i class="fas fa-save mr-1"></i> Guardar Cambios
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
    /* Ajustes visuales de botones y tarjeta */
    .btn-create { background-color: #2f55d4 !important; border-color: #2f55d4 !important; box-shadow: 0 2px 6px rgba(47, 85, 212, 0.4); }
    .btn-create:hover { background-color: #2040a5 !important; }
    .custom-card-header { background-color: #5d75e8; border-bottom: 3px solid #6777ef; color: white; }
    .custom-card-header h4 { color: white !important; }
    .required-label:after { content: " *"; color: #dc3545; font-weight: bold; }
    
    @media (min-width: 992px) { .border-left-lg { border-left: 1px solid #eee; } }
    .preview-container img, .preview-container video { border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .remove-btn { position: absolute; top: -5px; right: 5px; background: #dc3545; color: white; border-radius: 50%; width: 20px; height: 20px; padding: 0; font-size: 10px; border: 2px solid white; cursor: pointer; }

    /* ESTILO PARA EL SCROLLBAR PERSONALIZADO */
    .card-body::-webkit-scrollbar { width: 8px; }
    .card-body::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
    .card-body::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
    .card-body::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
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
    // 1. Inicializar Mapa
    const cochabambaBounds = L.latLngBounds(L.latLng(-18.50, -67.50), L.latLng(-16.00, -64.00));
    map = L.map('map', { minZoom: 9, maxBounds: cochabambaBounds }).setView([-17.3895, -66.1568], 13); 

    L.layerGroup([
        L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: 'Esri' }),
        L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}')
    ]).addTo(map);

    drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);
    
    // Controles de dibujo
    const drawControl = new L.Control.Draw({
        position: 'topright',
        draw: {
            polygon: { allowIntersection: false, drawError: { color: '#b00b00', message: '¡Fuera de límites!' }, shapeOptions: { color: '#3388ff', fillColor: '#3388ff', fillOpacity: 0.2 } },
            marker: { icon: new L.Icon.Default() },
            polyline: false, circle: false, rectangle: false, circlemarker: false
        },
        edit: { featureGroup: drawnItems, remove: true }
    });
    map.addControl(drawControl);
    
    // Buscador
    L.Control.geocoder({ defaultMarkGeocode: false, placeholder: "Buscar lugar...", geocoder: new L.Control.Geocoder.Nominatim() })
    .on('markgeocode', function(e) {
        var bbox = e.geocode.bbox;
        var poly = L.polygon([bbox.getSouthEast(), bbox.getNorthEast(), bbox.getNorthWest(), bbox.getSouthWest()]);
        map.fitBounds(poly.getBounds());
    }).addTo(map);

    function estaEnCochabamba(lat, lng) {
        return lat >= cochabambaBounds.getSouth() && lat <= cochabambaBounds.getNorth() && lng >= cochabambaBounds.getWest() && lng <= cochabambaBounds.getEast();
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
                let latlngs = layer.getLatLngs();
                // Normaliza posibles anillos (leaflet devuelve [ [pts], [hole], ... ])
                if (Array.isArray(latlngs) && Array.isArray(latlngs[0])) latlngs = latlngs[0];
                const points = latlngs.map(l => ({ lat: l.lat, lng: l.lng }));
                if (points.some(pt => !estaEnCochabamba(pt.lat, pt.lng))) { fueraDeLimites = true; return; }
                coordenadas.push({ tipo: 'poligono', coordenadas: points });
            }
        });

        if (fueraDeLimites) {
            Swal.fire('Error', 'Puntos fuera de Cochabamba eliminados.', 'error');
            drawnItems.clearLayers();
            document.getElementById('coordenadas-array').value = '';
            return;
        }
        document.getElementById('coordenadas-array').value = JSON.stringify(coordenadas);
        const tipos = coordenadas.map(c => c.tipo);
        document.getElementById('tipo-coordenada').value = tipos.includes('poligono') ? (tipos.includes('marcador') ? 'mixto' : 'poligono') : 'marcador';
    }

    map.on(L.Draw.Event.CREATED, function(e) {
        const layer = e.layer;
        if (layer instanceof L.Marker) {
            const latlng = layer.getLatLng();
            if (!estaEnCochabamba(latlng.lat, latlng.lng)) return;
            // activar arrastre correctamente
            if (layer.dragging && typeof layer.dragging.enable === 'function') layer.dragging.enable();
            layer.on('dragend', updateCoordenadasInput);
        } else if (layer instanceof L.Polygon) {
            // no todos los eventos de edit estan disponibles en la misma instancia, la edición se controla por L.Draw.Event.EDITED
        }
        drawnItems.addLayer(layer);
        updateCoordenadasInput();
    });
    map.on(L.Draw.Event.EDITED, updateCoordenadasInput);
    map.on(L.Draw.Event.DELETED, updateCoordenadasInput);

    // Cargar datos existentes
    const oldCoordenadas = document.getElementById('coordenadas-array').value;
    if (oldCoordenadas) {
        try {
            const coordenadas = JSON.parse(oldCoordenadas);
            if (Array.isArray(coordenadas)) {
                coordenadas.forEach(item => {
                    if (item.tipo === 'marcador') {
                        // soporta formatos distintos
                        const c = normalizeCoord(item.coordenadas);
                        if (!c) return;
                        const marker = L.marker([c.lat, c.lng]);
                        if (marker.dragging && typeof marker.dragging.enable === 'function') marker.dragging.enable();
                        marker.on('dragend', updateCoordenadasInput);
                        drawnItems.addLayer(marker);
                    } else if (item.tipo === 'poligono') {
                        let latlngs = [];
                        // puede ser array de arrays ([ [lng,lat], ... ]) o array de objetos {lat,lng}
                        if (item.coordenadas && Array.isArray(item.coordenadas) && item.coordenadas.length > 0) {
                            if (Array.isArray(item.coordenadas[0])) {
                                // array de arrays
                                latlngs = item.coordenadas.map(coord => {
                                    const n = normalizeCoord(coord);
                                    return [n.lat, n.lng];
                                });
                            } else {
                                // array de objetos
                                latlngs = item.coordenadas.map(coord => {
                                    const n = normalizeCoord(coord);
                                    return [n.lat, n.lng];
                                });
                            }
                        }
                        if (latlngs.length === 0) return;
                        const polygon = L.polygon(latlngs, { color: '#3388ff', fillColor: '#3388ff', fillOpacity: 0.2 });
                        drawnItems.addLayer(polygon);
                    }
                });
                if (drawnItems.getLayers().length > 0) map.fitBounds(drawnItems.getBounds());
            }
        } catch (e) { console.error('Error cargando coordenadas', e); }
    }

    // Botones externos
    document.getElementById('draw-marker').addEventListener('click', function() { new L.Draw.Marker(map, drawControl.options.draw.marker).enable(); });
    document.getElementById('draw-polygon').addEventListener('click', function() { new L.Draw.Polygon(map, drawControl.options.draw.polygon).enable(); });
    document.getElementById('clear-all').addEventListener('click', function() { 
        Swal.fire({ title: '¿Limpiar mapa?', text: "Se borrará el dibujo actual.", icon: 'warning', showCancelButton: true, confirmButtonText: 'Sí, borrar' })
        .then((result) => { if (result.isConfirmed) { drawnItems.clearLayers(); updateCoordenadasInput(); } });
    });

    // Ver Historial
    document.querySelectorAll('.view-history').forEach(btn => {
        btn.addEventListener('click', function() {
            const historialCoords = JSON.parse(this.getAttribute('data-coordenadas'));
            drawnItems.clearLayers();
            historialCoords.forEach(item => {
                if (item.tipo === 'marcador') {
                    const c = normalizeCoord(item.coordenadas);
                    if (!c) return;
                    const m = L.marker([c.lat, c.lng]);
                    drawnItems.addLayer(m);
                } else if (item.tipo === 'poligono') {
                    let latlngs = [];
                    if (item.coordenadas && Array.isArray(item.coordenadas) && item.coordenadas.length > 0) {
                        if (Array.isArray(item.coordenadas[0])) {
                            latlngs = item.coordenadas.map(coord => {
                                const n = normalizeCoord(coord);
                                return [n.lat, n.lng];
                            });
                        } else {
                            latlngs = item.coordenadas.map(coord => {
                                const n = normalizeCoord(coord);
                                return [n.lat, n.lng];
                            });
                        }
                    }
                    if (latlngs.length === 0) return;
                    drawnItems.addLayer(L.polygon(latlngs, { color: '#ff5722', fillColor: '#ff5722', fillOpacity: 0.3 }));
                }
            });
            if (drawnItems.getLayers().length > 0) map.fitBounds(drawnItems.getBounds());
            Swal.fire({ title: 'Viendo Historial', text: 'Estás viendo una versión antigua. ¿Restaurar o volver?', icon: 'info', showDenyButton: true, showCancelButton: true, confirmButtonText: 'Restaurar', denyButtonText: 'Volver' })
            .then((result) => {
                if (result.isDenied) location.reload();
                else if (result.isConfirmed) { updateCoordenadasInput(); Swal.fire('Restaurado', 'Ahora puedes editar esta versión.', 'success'); }
            });
        });
    });

    // Previsualización Archivos
    const previewFiles = function(input, previewContainer, isImage) {
        return function(e) {
            const files = input.files;
            previewContainer.innerHTML = ''; 
            if (files) {
                Array.from(files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const div = document.createElement('div');
                        div.className = 'col-4 preview-container mb-2 text-center position-relative'; 
                        div.innerHTML = `${isImage ? `<img src="${event.target.result}" class="img-fluid rounded border">` : `<video src="${event.target.result}" class="img-fluid rounded border"></video>`}<button type="button" class="remove-btn" data-index="${index}">×</button>`;
                        previewContainer.appendChild(div);
                        div.querySelector('.remove-btn').addEventListener('click', function() {
                            const dt = new DataTransfer();
                            const inputFiles = input.files;
                            const delIdx = parseInt(this.getAttribute('data-index'));
                            for (let i = 0; i < inputFiles.length; i++) { if (i !== delIdx) dt.items.add(inputFiles[i]); }
                            input.files = dt.files;
                            div.remove();
                        });
                    };
                    reader.readAsDataURL(file);
                });
            }
        };
    };
    const imgInp = document.getElementById('imagenes');
    const vidInp = document.getElementById('videos');
    if (imgInp) imgInp.addEventListener('change', previewFiles(imgInp, document.getElementById('imagenes-preview'), true));
    if (vidInp) vidInp.addEventListener('change', previewFiles(vidInp, document.getElementById('videos-preview'), false));

    document.querySelectorAll('.custom-file-input').forEach(input => {
        input.addEventListener('change', function() { this.nextElementSibling.textContent = this.files.length > 0 ? `${this.files.length} archivos` : 'Seleccionar'; });
    });

    document.getElementById('zona-form').addEventListener('submit', function(e) {
        e.preventDefault();
        map.invalidateSize();
        if (!document.getElementById('coordenadas-array').value) return Swal.fire('Error', "Dibuja la zona en el mapa.", 'error');
        html2canvas(document.getElementById('map'), { useCORS: true, backgroundColor: null, ignoreElements: (element) => element.classList.contains('leaflet-control-container') })
        .then(canvas => {
            const input = document.createElement('input');
            input.type = 'hidden'; input.name = 'imagen_mapa'; input.value = canvas.toDataURL('image/jpeg', 0.6);
            this.appendChild(input); this.submit();
        }).catch(() => this.submit());
    });
});
</script>
@endpush