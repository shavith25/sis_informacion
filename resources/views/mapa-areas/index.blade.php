@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">

<link rel="stylesheet" href="{{ asset('css/mapa-areas/index.css') }}">

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Mapa de Áreas Protegidas</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('home') }}">Inicio</a></div>
                <div class="breadcrumb-item">Mapa de Áreas Protegidas</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <div id="map">
                                <button id="btn-cancelar-dibujo" class="btn btn-danger d-none"
                                    style="position: absolute; bottom: 30px; right: 20px; z-index: 1000; 
                                        background-color: #dc3545 !important; border-color: #dc3545 !important; 
                                        opacity: 1 !important; box-shadow: 0 0 10px rgba(0,0,0,0.3);">
                                    <i class="fas fa-times"></i> Cancelar
                                </button>

                                <div class="map-card-container">
                                    <div class="map-card">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">

                                            <li class="nav-item nav-custom" role="presentation">
                                                <button class="nav-link active" id="tab4-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab4" type="button" role="tab"
                                                    aria-controls="tab4" aria-selected="false">
                                                    <i class="fas fa-exclamation-circle"></i> Incidencias
                                                </button>
                                            </li>
                                            <li class="nav-item nav-custom" role="presentation">
                                                <button class="nav-link" id="tab3-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab3" type="button" role="tab"
                                                    aria-controls="tab3" aria-selected="false">
                                                    <i class="fas fa-search"></i> Busqueda
                                                </button>
                                            </li>
                                        </ul>

                                        <div class="tab-content mt-3" id="myTabContent">

                                            <div class="tab-pane fade " id="tab3" role="tabpanel3"
                                                aria-labelledby="tab3-tab">
                                                <div style="color: white;">
                                                    <label for="departamento"><i class="fas fa-map-marker-alt"></i> 
                                                        Límites administrativos
                                                    </label>
                                                    <select id="departamento" class="form-control mb-2">
                                                        <option value="">Seleccione un departamento</option>
                                                        @foreach ($departamentos as $dep)
                                                            <option value="{{ $dep->id }}">{{ $dep->nombre }}</option>
                                                        @endforeach
                                                    </select>

                                                    <div id="selects-provincias" style="display: none;">
                                                        <label for="provincia"><i class="fas fa-map-marker-alt"></i>
                                                            Provincias
                                                        </label>

                                                        <select id="provincia" class="form-control mb-2">
                                                            <option value="">Seleccione una provincia</option>
                                                        </select>

                                                        <div id="selects-municipios"
                                                            style="display: none; margin-top: 1rem;">
                                                            <label for="municipio"><i class="fas fa-map-marker-alt"></i>
                                                                Municipios
                                                            </label>

                                                            <select id="municipio" class="form-control mb-2">
                                                                <option value="">Seleccione un municipio</option>
                                                            </select>
                                                        </div>

                                                        <button id="buscar-zonas" class="btn btn-primary btn-block mt-2"
                                                            style="background-color: #0d6efd !important; border-color: #0d6efd !important; opacity: 1 !important; background-image: none !important; color: white;">
                                                            <i class="fas fa-search"></i> Buscar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-white mb-2 d-flex flex-column" id="resultados-zonas"
                                                style="display: none !important;">
                                                <p>Resultado de búsqueda:</p>
                                                <ul id="resultado-zonas-lista"></ul>
                                            </div>
                                            <button id="reset-zonas" class="btn btn-danger btn-block mt-2"
                                                style="display: none;"> <i class="fas fa-undo"></i> Restablecer
                                            </button>
                                        </div>

                                        <div class="tab-pane fade show active" id="tab4" role="tabpanel"
                                            aria-labelledby="tab4-tab">

                                            <div style="height: 600px; display: flex-direction: column;">
                                                <div id="mensaje-exito-zona-incidencia"
                                                    class="alert alert-success mt-3 d-none"></div>
                                                <div id="errores-zona-incidencia" class="alert alert-danger mt-3 d-none">
                                                </div>
                                                <div
                                                    style="flex: 1; overflow-y: auto; overflow-x: hidden; padding-right: 5px;">
                                                    <form id="form-zonas-incidencia"
                                                        action="{{ route('mapa-areas.storeZonaIncidenciaFromMapa') }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="mb-3 row">
                                                            <div class="col-md-6">
                                                                <label for="zona_nombre_incidencia"
                                                                    class="form-label text-white">Incidencia:</label>
                                                                <input type="text" class="form-control"
                                                                    name="zona_nombre_incidencia"
                                                                    id="zona_nombre_incidencia"
                                                                    placeholder="Nombre de la incidencia" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="tipo_incidencia"
                                                                    class="form-label text-white">Tipo:</label>
                                                                <select name="tipo_incidencia" id="tipo_incidencia"
                                                                    class="form-control" required>
                                                                    <option value="">Seleccione un tipo</option>
                                                                    <option value="incendio">Incendio</option>
                                                                    <option value="avasallamiento">Avasallamiento</option>
                                                                    <option value="inundacion">Inundación</option>
                                                                    <option value="sequia">Sequía</option>
                                                                    <option value="loteamiento">Loteamiento</option>
                                                                    <option value="afectacion_biodiversidad">Afectación a la biodiversidad</option>
                                                                    <option value="otro">Otros</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3 row">
                                                            <div class="col-md-6">
                                                                <label for="zona_id"
                                                                    class="form-label text-white">Zona:</label>
                                                                <select name="zona_id" id="zona_id"
                                                                    class="form-control" required>
                                                                    <option value="">Seleccione una Área Protegida</option>
                                                                    @foreach ($zonas as $zona)
                                                                        <option value="{{ $zona->id }}"
                                                                            {{ old('zona_id') == $zona->id ? 'selected' : '' }}>
                                                                            {{ $zona->nombre }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label for="fecha_incidencia"
                                                                    class="form-label text-white">Fecha:</label>
                                                                <input type="date" class="form-control"
                                                                    name="fecha_incidencia" id="fecha_incidencia"
                                                                    required>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="descripcion_zona_incidencia"
                                                                class="form-label text-white">Descripción</label>
                                                            <textarea class="form-control" name="descripcion_zona_incidencia" id="descripcion_zona_incidencia" rows="3"
                                                                placeholder="Agrega descripción aquí..."></textarea>
                                                        </div>

                                                        <div class="mb-3"
                                                            style="display: flex; flex-direction: column;">
                                                            <label for="dibujo_mapa" class="form-label text-white">Dibujar en el mapa:</label>
                                                            <div class="btn-group mb-2" role="group">
                                                                <button type="button" class="btn btn-primary"
                                                                    id="draw-marker-incidencia"
                                                                    style="background-color: #0d6efd !important; border-color: #0d6efd !important; opacity: 1 !important; background-image: none !important; color: white;">
                                                                    <i class="fas fa-map-marker-alt"></i> Marcador
                                                                </button>

                                                                <button type="button" class="btn btn-success"
                                                                    id="draw-polygon-incidencia">
                                                                    <i class="fas fa-draw-polygon"></i> Polígono
                                                                </button>

                                                                <button type="button" class="btn btn-warning"
                                                                    id="import-polygon-incidencia">
                                                                    <i class="fas fa-file-import"></i> Importar
                                                                </button>

                                                                <input type="file" id="import-file-incidencia"
                                                                    accept=".kml,.kmz,.shp,.zip" class="d-none">
                                                            </div>
                                                            <div class="btn-group mb-2" role="group">
                                                                <button type="button" class="btn btn-danger"
                                                                    id="clear-all-incidencia">
                                                                    <i class="fas fa-trash-alt"></i> Limpiar
                                                                </button>

                                                                <button type="button" class="btn btn-info"
                                                                    id="edit-zone-incidencia">
                                                                    <i class="fas fa-pencil-alt"></i> Editar
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label for="imagen_zona_incidencia"
                                                                    class="form-label text-white">Imágenes:
                                                                </label>
                                                                <input type="file" class="d-none"
                                                                    name="imagen_zona_incidencia[]"
                                                                    id="imagen_zona_incidencia" accept="image/*" multiple>
                                                                <button type="button"
                                                                    class="btn btn-outline-light w-100 d-flex align-items-center justify-content-center p-2"
                                                                    id="image-upload-area-incidencia">
                                                                    <i class="fas fa-plus-circle me-2"></i> Subir Imagenes
                                                                    <span class="ms-auto badge bg-secondary"
                                                                        id="image-count-display-incidencia">0</span>
                                                                </button>
                                                                <div id="image-preview-incidencia" class="row mt-2"></div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label for="video_zona_incidencia"
                                                                    class="form-label text-white">Videos:</label>
                                                                <input type="file" class="d-none"
                                                                    name="video_zona_incidencia[]"
                                                                    id="video_zona_incidencia" accept="video/*" multiple>
                                                                <button type="button"
                                                                    class="btn btn-outline-light w-100 d-flex align-items-center justify-content-center p-2"
                                                                    id="video-upload-area-incidencia">
                                                                    <i class="fas fa-plus-circle me-2"></i> Subir Videos
                                                                    <span class="ms-auto badge bg-secondary"
                                                                        id="video-count-display-incidencia">0</span>
                                                                </button>
                                                                <div id="video-preview-incidencia" class="row mt-2"></div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="mt-3 d-flex justify-content-end gap-2">
                                                    <button type="submit" form="form-zonas-incidencia" class="btn"
                                                        style="background-color: #007bff; color: white; border: 1px solid #007bff; opacity: 1;">
                                                        <i class="fas fa-save"></i> Guardar
                                                    </button>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div class="map-tools d-flex flex-column align-items-left p-2">
                                    <button class="btn btn-light btn-xl " onclick="toggleCard()">
                                        <span
                                            style="display: block; width: 20px; height: 2px; background: black; margin: 3px 0;"></span>
                                        <span
                                            style="display: block; width: 20px; height: 2px; background: black; margin: 3px 0;"></span>
                                        <span
                                            style="display: block; width: 20px; height: 2px; background: black; margin: 3px 0;"></span>
                                    </button>
                                    <button class="btn btn-light btn-xl text-dark" onclick="map.zoomIn()">➕</button>
                                    <button class="btn btn-light btn-xl text-dark" onclick="map.zoomOut()">➖</button>
                                    <button class="btn btn-light btn-xl" onclick="centerMap()"
                                        title="Centrar en cochabamba">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            fill="black" viewBox="0 0 16 16">
                                            <path
                                                d="M8 0a.5.5 0 0 1 .5.5v1.55a6.5 6.5 0 0 1 5.45 5.45H15.5a.5.5 0 0 1 0 1h-1.55a6.5 6.5 0 0 1-5.45 5.45v1.55a.5.5 0 0 1-1 0v-1.55a6.5 6.5 0 0 1-5.45-5.45H.5a.5.5 0 0 1 0-1h1.55a6.5 6.5 0 0 1 5.45-5.45V.5A.5.5 0 0 1 8 0zm0 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8z" />
                                        </svg>
                                    </button>
                                    <div class="layer-container" onmouseleave="hideLayerPanel()">
                                        <div id="layer-panel" class="layer-panel">

                                            <div id="layerAccordion">
                                                <div class="card bg-white text-black border-2">
                                                    <div class="card-header p-1" id="headingBaseMap">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-link text-left text-black p-2 w-100"
                                                                type="button" data-toggle="collapse"
                                                                data-target="#collapseBaseMap" aria-expanded="true"
                                                                aria-controls="collapseBaseMap">
                                                                Mapa Base
                                                            </button>
                                                        </h2>
                                                    </div>

                                                    <div id="collapseBaseMap" class="collapse"
                                                        aria-labelledby="headingBaseMap" data-parent="#layerAccordion">
                                                        <div class="card-body p-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    name="basemap" id="radioHibrido" value="hibrido"
                                                                    checked>
                                                                <label class="form-check-label" for="radioHibrido">
                                                                    Híbrido
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    name="basemap" id="radioOSM" value="osm">

                                                                <label class="form-check-label" for="radioOSM">
                                                                    OpenStreetMap
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    name="basemap" id="radioTopo" value="topo">
                                                                <label class="form-check-label" for="radioTopo">
                                                                    OpenTopoMap
                                                                </label>
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

                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://unpkg.com/@tmcw/togeojson@0.16.0/dist/togeojson.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://unpkg.com/shpjs@latest/dist/shp.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://unpkg.com/@tmcw/togeojson"></script>
    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>

    <script src="https://unpkg.com/proj4@2.9.1/dist/proj4.js"></script>
    <script src="https://unpkg.com/proj4leaflet@1.0.2/src/proj4leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        const RUTA_LISTADO_AREAS = "{{ route('mapa-areas.listado') }}";
        const zonasJson = @json($zonas);
    </script>
    <script src="{{ asset('js/mapa-areas/index.js') }}"></script>

@endpush
