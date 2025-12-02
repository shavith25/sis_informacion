let map;
let currentLayer;
let provinciasData = [];
let departamentoLayer = null;
let provinciaLayer = null;
let zonaGeometriaLayer = null;
let filtroGeometria;
let activeDrawer = null;
let currentDrawingContext; 
const drawnItemsIncidencia = new L.FeatureGroup();
const municipioSelect = document.getElementById("municipio");
const municipiosContainer = document.getElementById("selects-municipios");
const eventoColors = {
    incendio: '#FF0000',       
    avasallamiento: '#C2185B', 
    inundacion: '#006064',    
    otro: '#4527A0'           
};

const zonasColor = '#4CAF50'; 
let tileLayers = {
    osm: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }),

        hibrido: L.layerGroup([
        L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: '&copy; Esri, i-cubed, USDA, USGS, etc.'
        }),
        L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Transportation/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Labels © Esri'
        })
        ])
,
    topo: L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data: &copy; OpenTopoMap (CC-BY-SA)'
    })
};
function disableActiveDrawer() {
    if (activeDrawer) {
        activeDrawer.disable();
        activeDrawer = null;
    }
}

  function toggleActionButtonsIncidencia() {
        const editBtn = document.getElementById('edit-zone-incidencia');
        const clearBtn = document.getElementById('clear-all-incidencia');
        const hasDrawings = drawnItemsIncidencia.getLayers().length > 0;

        editBtn.style.display = hasDrawings ? 'inline-block' : 'none';
        clearBtn.style.display = hasDrawings ? 'inline-block' : 'none';
}
function limpiarSelect(id) {
        document.getElementById(id).innerHTML = `<option value="">Seleccione</option>`;
}

// Función para obtener un color único para una zona basado en su ID
function getColorForZona(zonaId) {
    // Paleta de colores distintivos
    const colors = [
        '#3388ff', '#ff3333', '#33ff33', '#ff33ff', '#33ffff',
        '#ffff33', '#ff9933', '#33ff99', '#9933ff', '#ff3399',
        '#3399ff', '#99ff33', '#ff33cc', '#33ccff', '#ccff33',
        '#ffcc33', '#33ffcc', '#cc33ff', '#ff3366', '#66ff33'
    ];
    // Usamos el ID de la zona para seleccionar un color de la paleta
    return colors[zonaId % colors.length];
}

function normalizeCoords(coords) {
    
    const isLat = (val) => val < 0 && val > -23;
    const isLng = (val) => val < 0 && val < -57 && val > -70;

    function swapIfNeeded(pair) {
        const [a, b] = pair;
        if (isLat(a) && isLng(b)) {
            
            return [a, b];
        } else if (isLat(b) && isLng(a)) {
            
            return [b, a];
        }
        return pair; 
    }

    
    if (Array.isArray(coords[0])) {
        return coords.map(c => normalizeCoords(c));
    } else {
        return swapIfNeeded(coords);
    }
}

document.addEventListener("DOMContentLoaded", function () {
    
    const boliviaBounds = L.latLngBounds(L.latLng(-23.0, -70.5), L.latLng(-8.0, -57.5));

    map = L.map('map', {
        center: [-17.3895, -66.1568],
        zoom: 10,
        minZoom: 6,
        maxZoom: 18,
        maxBounds: boliviaBounds,
        maxBoundsViscosity: 1.0,
        zoomControl: false
    });

    currentLayer = tileLayers.hibrido;
    currentLayer.addTo(map);

    setTimeout(() => map.invalidateSize(), 300);
    document.getElementById('clear-all-incidencia').style.display = 'none';
    document.getElementById('edit-zone-incidencia').style.display = 'none';

    map.addLayer(drawnItemsIncidencia);
    window.currentDrawnCoordinates = null;
    window.currentDrawnType = null;

   document.getElementById('draw-marker-incidencia').addEventListener('click', () => {
        disableActiveDrawer();
        setTimeout(() => {
            const markerDrawer = new L.Draw.Marker(map);
            markerDrawer.enable();
            currentDrawingContext = 'incidencia';
        }, 300);
    });

    document.getElementById('draw-polygon-incidencia').addEventListener('click', () => {
        disableActiveDrawer();
        const polygonDrawer = new L.Draw.Polygon(map);
        polygonDrawer.enable();
        currentDrawingContext = 'incidencia';
    });

    document.getElementById('clear-all-incidencia').addEventListener('click', () => {

        drawnItemsIncidencia.clearLayers();
        toggleActionButtonsIncidencia();
    });
    document.getElementById('import-polygon-incidencia').addEventListener('click', () => {
        document.getElementById('import-file-incidencia').click();
        currentDrawingContext = 'incidencia';
    });
    document.getElementById('import-file-incidencia').addEventListener('change', async function(event) {
        const file = event.target.files[0];
        if (!file) return;

        const ext = file.name.split('.').pop().toLowerCase();
        currentDrawingContext = 'incidencia';
        try {
            switch(ext) {
                case 'kml':
                    await loadKML(file);
                    break;
                case 'kmz':
                    await loadKMZ(file);
                    break;
                case 'shp':
                case 'zip':
                    await loadShapefile(file);
                    break;
                default:
                    alert('Formato no soportado');
            }
        } catch (error) {
            console.error(error);
            alert('Error al cargar el archivo: ' + error.message);
        }

        this.value = '';
    });
    async function loadKML(file) {
        const text = await file.text();

        const parser = new DOMParser();
        const kmlDoc = parser.parseFromString(text, 'text/xml');

        const geojson = toGeoJSON.kml(kmlDoc);

        addGeoJSONToMap(geojson);
    }

    async function loadKMZ(file) {
        const jszip = new JSZip();
        const zip = await jszip.loadAsync(file);
        const kmlFile = Object.keys(zip.files).find(name => name.endsWith('.kml'));

        if (!kmlFile) throw new Error('No se encontró archivo KML dentro del KMZ.');

        const kmlText = await zip.files[kmlFile].async('text');
        const parser = new DOMParser();
        const kmlDoc = parser.parseFromString(kmlText, 'text/xml');
        const geojson = toGeoJSON.kml(kmlDoc);

        addGeoJSONToMap(geojson);
    }
    proj4.defs("EPSG:32720", "+proj=utm +zone=20 +south +datum=WGS84 +units=m +no_defs");
   function reproyectarGeoJSON(geojson) {
        const from = proj4("EPSG:32720");
        const to = proj4("EPSG:4326");

        function reproyectarCoordenadas(coords) {
        if (typeof coords[0] === "number") {
            return proj4(from, to, coords);
        } else {
            return coords.map(reproyectarCoordenadas);
        }
        }

        const reproyectado = JSON.parse(JSON.stringify(geojson));
       reproyectado.features.forEach(f => {
            if (f.geometry && f.geometry.coordinates) {
                console.log('ANTES:', f.geometry.coordinates);
                f.geometry.coordinates = reproyectarCoordenadas(f.geometry.coordinates);
                console.log('DESPUÉS:', f.geometry.coordinates);
            }
        });

        return reproyectado;
    }
    async function loadShapefile(file) {
        const buffer = await file.arrayBuffer();
        shp(buffer).then(geojson => {
            // const geojsonReproyectado = reproyectarGeoJSON(geojson);
            addGeoJSONToMap(geojson);
        });
    }
    function addGeoJSONToMap(geojson) {

        if (window.importedLayer) {
            if (currentDrawingContext === 'incidencia') {
                drawnItemsIncidencia.removeLayer(window.importedLayer);
            }
            
        }


        window.importedLayer = L.geoJSON(geojson, {
            style: {
                color: '#ff7800',
                weight: 3,
                opacity: 0.65
            },
            onEachFeature: (feature, layer) => {

                if (feature.properties) {
                    let popupContent = '';
                    for (const key in feature.properties) {
                        popupContent += `<strong>${key}:</strong> ${feature.properties[key]}<br>`;
                    }
                    layer.bindPopup(popupContent);
                }
                if (currentDrawingContext === 'incidencia') {
                    drawnItemsIncidencia.addLayer(layer);
                }
                
            }
        });

        map.fitBounds(window.importedLayer.getBounds());

        toggleActionButtonsIncidencia();
        window.currentDrawnCoordinates = geojson.features.length > 0
            ? normalizeCoords(geojson.features[0].geometry.coordinates)
            : null;
        window.currentDrawnType = geojson.features.length > 0
            ? geojson.features[0].geometry.type.toLowerCase()
            : null;
    }

    map.on(L.Draw.Event.CREATED, function (event) {
        const layer = event.layer;
         if (currentDrawingContext === 'incidencia') {
            drawnItemsIncidencia.addLayer(layer);
            toggleActionButtonsIncidencia();
        } else {
        }
        const tipo = event.layerType;
        let coordenadas = null;

        if (tipo === 'marker') {
            const latlng = layer.getLatLng();
            coordenadas = [latlng.lat, latlng.lng];
        } else if (tipo === 'polygon' || tipo === 'polyline') {
            const latlngs = layer.getLatLngs();
            coordenadas = latlngs.map(inner =>
                inner.map(p => [p.lat, p.lng])
            );
        } else if (tipo === 'rectangle') {
            const bounds = layer.getBounds();
            coordenadas = [
                [bounds.getSouthWest().lat, bounds.getSouthWest().lng],
                [bounds.getNorthEast().lat, bounds.getNorthEast().lng]
            ];
        }
        
        
        disableActiveDrawer(); 
        let coordenadasFormat = normalizeCoords(coordenadas);
        window.currentDrawnCoordinates = coordenadasFormat;
        window.currentDrawnType = tipo;
    });
    const drawControlIncidencia = new L.Control.Draw({
        edit: {
            featureGroup: drawnItemsIncidencia,
            remove: false
        },
        draw: false
    });
    // map.addControl(drawControl);
    map.addControl(drawControlIncidencia);
    const editBtnIncidencia = document.getElementById('edit-zone-incidencia');
    let isEditingIncidencia = false;

    let isEditing = false;

     editBtnIncidencia.addEventListener('click', () => {
        if (!isEditingIncidencia) {
            drawControlIncidencia._toolbars.edit._modes.edit.handler.enable();
            editBtnIncidencia.innerHTML = '<i class="fas fa-save"></i> Guardar';
            isEditingIncidencia = true;
        } else {
            drawControlIncidencia._toolbars.edit._modes.edit.handler.save();
            drawControlIncidencia._toolbars.edit._modes.edit.handler.disable();
            editBtnIncidencia.innerHTML = '<i class="fas fa-pencil-alt"></i> Editar';
            isEditingIncidencia = false;
        }
    });

    map.on('draw:created', toggleActionButtonsIncidencia);
    map.on('draw:deleted', toggleActionButtonsIncidencia);
    map.on('draw:editstop', function () {
        drawControl._toolbars.edit._modes.edit.handler.disable();
    });

    document.querySelectorAll('input[name="basemap"]').forEach(radio => {
        radio.addEventListener('change', function () {
            if (tileLayers[this.value]) {
                map.removeLayer(currentLayer);
                currentLayer = tileLayers[this.value];
                currentLayer.addTo(map);
            }
        });
    });


    const departamentoSelect = document.getElementById("departamento");
    const provinciasContainer = document.getElementById("selects-provincias");
    const provinciaSelect = document.getElementById("provincia");
    const zonaSelect = document.getElementById('zona_id');
    
   zonaSelect.addEventListener("change", async function  () {
                console.log(this.value);

            
                const response = await fetch('/mapa-areas/zonas');
                    const zonas = await response.json();
                    const selectZona = zonas.find(z => z.id === parseInt(this.value));
                    if (!selectZona) {
                        console.warn("Zona no encontrada");
                        return;
                    }
                    debugger
                const geoZona =  normalizeZonaGeoJSON(selectZona.ultimo_historial)
                
                    if (!geoZona) {
                        Swal.fire({ icon: 'warning', title: 'Zona sin geometría válida' });
                        return;
                    }
                    const uniqueZonaColor = getColorForZona(selectZona.id); // Obtener color único
                    if (zonaGeometriaLayer) {
                            map.removeLayer(zonaGeometriaLayer);
                        }
                        zonaGeometriaLayer = L.geoJSON(geoZona, {
                            style: {
                                    color: uniqueZonaColor,       
                                    weight: 2,
                                    opacity: 1,            
                                    fillColor: uniqueZonaColor,   
                                    fillOpacity: 0.4       
                                }
                        }).addTo(map);

                map.fitBounds(zonaGeometriaLayer.getBounds());
            });

    departamentoSelect.addEventListener("change", function () {
        const departamentoId = this.value;

        if (!departamentoId) {
            provinciasContainer.style.display = 'none';
            limpiarSelect("provincia");
            document.getElementById("resultados-zonas").style.display = "none";
            document.getElementById("reset-zonas").style.display = "none";
            return;
        }
        document.getElementById("resultados-zonas").style.display = "block";
        document.getElementById("reset-zonas").style.display = "block";
        fetch(`/mapa-areas/${departamentoId}/areas`)
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw new Error(errorData.error || 'Error desconocido.');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (departamentoLayer) map.removeLayer(departamentoLayer);

                provinciasContainer.style.display = "block";
                limpiarSelect("provincia");
                llenarSelect("provincia", data.provincias);
                
                provinciasData = data.provincias;

                const geojsonObject = data.departamento_geometria;
                departamentoLayer = L.geoJSON(geojsonObject, {
                    style: { color: "#4ffd38ff", weight: 2, opacity: 1, fill: false }
                }).addTo(map);

                map.fitBounds(departamentoLayer.getBounds());
            })
            .catch(err => {
                Swal.fire({ icon: 'error', title: 'Oops...', text: err.message });
                provinciasContainer.style.display = 'none';
                limpiarSelect("provincia");
            });
    });

    provinciaSelect.addEventListener("change", async function () {
        const provinciaNombre = this.value;
        if (!provinciaNombre) {
            if (provinciaLayer) map.removeLayer(provinciaLayer);
            limpiarSelect("municipio");
            municipiosContainer.style.display = 'none';
            return;
        }

        const provincia = provinciasData.find(p => p.nombre === provinciaNombre);
        if (!provincia || !provincia.geometria) {
            Swal.fire({ icon: 'warning', title: 'Provincia sin geometría' });
            return;
        }

        if (departamentoLayer) {
        map.removeLayer(departamentoLayer);
        departamentoLayer = null;
        }

        if (provinciaLayer) map.removeLayer(provinciaLayer);
        console.log('Provincia GeoJSON:', provincia.geometria,provincia);
        const geojson = JSON.parse(provincia.geometria);

        provinciaLayer = L.geoJSON(geojson, {
            style: { color: "#4ffd38ff", weight: 2, opacity: 1, fill: false }
        }).addTo(map);

        map.fitBounds(provinciaLayer.getBounds());
        try {
        const response = await fetch(`/mapa-areas/provincia/${provincia.id}/municipios`);
        debugger
        const municipios = await response.json();

        if (municipios.length > 0) {
                limpiarSelect("municipio");
                llenarSelectMunicipio("municipio", municipios);
                municipiosContainer.style.display = 'block';
            } else {
                municipiosContainer.style.display = 'none';
                limpiarSelect("municipio");
                Swal.fire({ icon: 'info', title: 'Esta provincia no tiene municipios registrados' });
            }
        } catch (err) {
            console.error(err);
            Swal.fire({ icon: 'error', title: 'Error al cargar municipios' });
            municipiosContainer.style.display = 'none';
            limpiarSelect("municipio");
        }
    });



    function llenarSelect(id, items) {
        const select = document.getElementById(id);
        items.forEach(item => {
            const option = document.createElement("option");
            option.value = item.nombre;
            option.textContent = item.nombre;
            select.appendChild(option);
        });
    }
    function llenarSelectMunicipio(id, items) {
        const select = document.getElementById(id);
        items.forEach(item => {
            const option = document.createElement("option");
            option.value = item.id;
            option.textContent = item.nombre;
            select.appendChild(option);
        });
    }
 const mapCard = document.querySelector('.map-card');
        mapCard.addEventListener('mouseenter', () => {
        if (map && map.dragging) {
            map.dragging.disable();
        }
        });
        mapCard.addEventListener('mouseleave', () => {
        if (map && map.dragging) {
            map.dragging.enable();
        }
        });
});

function centerMap() {
    map.setView([-17.3895, -66.1568], 13);
}

document.getElementById('departamento').addEventListener('change', function () {
    const selected = this.value;
    const cochabambaSelects = document.getElementById('selects-cochabamba');

    if (selected === 'cochabamba') {
        cochabambaSelects.style.display = 'block';
    } else {
        cochabambaSelects.style.display = 'none';
    }
});

function normalizeZonaGeoJSON(zona) {
    if (!zona || !zona.coordenadas) {
        return null;
    }

    let features = [];
    const coordenadas = zona.coordenadas;

    // Caso 1: Formato [{ tipo: 'poligono', coordenadas: [...] }, { tipo: 'marcador', coordenadas: {...} }]
    if (Array.isArray(coordenadas) && coordenadas.length > 0 && typeof coordenadas[0] === 'object' && coordenadas[0] !== null && coordenadas[0].tipo) {
        coordenadas.forEach(item => {
            if (item.tipo === 'poligono' && Array.isArray(item.coordenadas)) {
                const polygonCoords = item.coordenadas.map(p => [p.lng, p.lat]);
                // Asegurarse que el polígono esté cerrado
                if (polygonCoords.length > 2 && (polygonCoords[0][0] !== polygonCoords[polygonCoords.length - 1][0] || polygonCoords[0][1] !== polygonCoords[polygonCoords.length - 1][1])) {
                    polygonCoords.push(polygonCoords[0]);
                }
                if (polygonCoords.length > 3) {
                    features.push({
                        type: 'Feature',
                        properties: { ...zona },
                        geometry: { type: 'Polygon', coordinates: [polygonCoords] }
                    });
                }
            } else if (item.tipo === 'marcador' && item.coordenadas) {
                features.push({
                    type: 'Feature',
                    properties: { ...zona },
                    geometry: { type: 'Point', coordinates: [item.coordenadas.lng, item.coordenadas.lat] }
                });
            }
        });
    }
    // Caso 2: Formato de polígono simple [[[-66.1, -17.3], ...]]
    else if (Array.isArray(coordenadas) && Array.isArray(coordenadas[0]) && Array.isArray(coordenadas[0][0])) {
        coordenadas.forEach(polygonArray => {
            const polygonCoords = polygonArray.map(p => [p[1], p[0]]); // Asumiendo [lat, lng] y convirtiendo a [lng, lat]
            if (polygonCoords.length > 2 && (polygonCoords[0][0] !== polygonCoords[polygonCoords.length - 1][0] || polygonCoords[0][1] !== polygonCoords[polygonCoords.length - 1][1])) {
                polygonCoords.push(polygonCoords[0]);
            }
            if (polygonCoords.length > 3) {
                features.push({
                    type: 'Feature',
                    properties: { ...zona },
                    geometry: { type: 'Polygon', coordinates: [polygonCoords] }
                });
            }
        });
    }

    if (features.length === 0) {
        return null;
    }

    // Si solo hay una geometría, devuelve un Feature. Si hay varias, un FeatureCollection.
    if (features.length === 1) {
        return features[0];
    } else {
        return { type: 'FeatureCollection', features: features };
    }
}


function normalizeEventoGeoJSON(evento, zonaNombre) {
  if (!evento.coordenadas) return null;

  return {
    type: "Feature",
    geometry: {
      type: evento.tipo_coordenada.toUpperCase(),
      coordinates: evento.coordenadas
    },
    properties: {
      id: evento.id,
      zona_id: evento.zona_id,
      nombre: evento.titulo || "Evento sin título",
      descripcion: evento.descripcion || "",
      tipo: "evento",
      zona_nombre: zonaNombre
    }
  };
}

municipioSelect.addEventListener("change", async function () {
    const municipioId = this.value;
    if (!municipioId) {
        if (window.municipioLayer) {
            map.removeLayer(window.municipioLayer);
            window.municipioLayer = null;
        }
        return;
    }

    try {
        const response = await fetch(`/mapa-areas/municipio/${municipioId}/geometria`);
        const municipioData = await response.json();

        if (!municipioData || !municipioData.geometria) {
            Swal.fire({ icon: 'warning', title: 'Municipio sin geometría' });
            return;
        }

        if (window.municipioLayer) {
            map.removeLayer(window.municipioLayer);
        }

        if (provinciaLayer) {
            map.removeLayer(provinciaLayer);
            provinciaLayer = null;
        }

        const geojson = municipioData.geometria;
        window.municipioLayer = L.geoJSON(geojson, {
            style: { 
                color: "#ff6600", 
                weight: 3, 
                fillOpacity: 0.2,
                fillColor: "#ff6600"
            }
        }).addTo(map);

        map.fitBounds(window.municipioLayer.getBounds());

    } catch (err) {
        console.error("Error al cargar geometría del municipio:", err);
        Swal.fire({ icon: 'error', title: 'No se pudo cargar la geometría del municipio' });
    }
});

document.querySelector('#selects-provincias button').addEventListener('click', async () => {
    if (!provinciaLayer && !window.municipioLayer) {
        Swal.fire({ icon: 'warning', title: 'Selecciona una provincia o municipio primero' });
        return;
    }

    // const provinciaGeoJSON = provinciaLayer.toGeoJSON();
    // const provinciaFeature = provinciaGeoJSON.type === 'FeatureCollection'
    // ? provinciaGeoJSON.features[0]
    // : provinciaGeoJSON;
    

    if (window.municipioLayer) {
        const municipioGeoJSON = window.municipioLayer.toGeoJSON();
        filtroGeometria = municipioGeoJSON.type === 'FeatureCollection'
            ? municipioGeoJSON.features[0]
            : municipioGeoJSON;
    } else if (provinciaLayer) {
        const provinciaGeoJSON = provinciaLayer.toGeoJSON();
        filtroGeometria = provinciaGeoJSON.type === 'FeatureCollection'
            ? provinciaGeoJSON.features[0]
            : provinciaGeoJSON;
    } else {
        Swal.fire({ icon: 'warning', title: 'No hay geometría seleccionada' });
        return;
    }
    // console.log('Provincia GeoJSON:', provinciaGeoJSON);
    const response = await fetch('/mapa-areas/zonas');
    const zonas = await response.json();
    console.log('Zonas obtenidas:', zonas);
   const municipioId = document.getElementById("municipio").value;
    let municipioFeature = null;

    const zonasFiltradas = zonas
    .map(zona => {
        if (!zona.ultimo_historial) return null; 
        const geo = normalizeZonaGeoJSON(zona.ultimo_historial);
        if (!geo) return null;
        geo.properties = { ...zona }; 
        return geo;
    })
    .filter(zonaGeoJSON => zonaGeoJSON !== null)
    .filter(zonaGeoJSON => turf.booleanIntersects(filtroGeometria, zonaGeoJSON));


    
    const eventosFiltrados = zonasFiltradas.flatMap(zona => {
    if (!zona.properties || !zona.properties.eventos) return [];

return zona.properties.eventos
    .map(evento => {
        if (!evento.coordenadas) return null;

    let geometry;

    if (evento.tipo_coordenada === "polygon") {
    
        const coords = evento.coordenadas.map(ring =>
        ring.map(coord => [coord[1], coord[0]]) 
        );

        coords.forEach(ring => {
            const first = ring[0];
            const last = ring[ring.length - 1];
        if (first[0] !== last[0] || first[1] !== last[1]) {
            ring.push([...first]);
        }
        });

        geometry = {
            type: "Polygon",
            coordinates: coords
        };
    } else {
        geometry = {
            type: "Point",
            coordinates: [evento.coordenadas[1], evento.coordenadas[0]] 
        };
    }

      return {
        type: "Feature",
        properties: {
            ...evento,
            zona_nombre: zona.properties.nombre
        },
        geometry
    };
    })
    .filter(Boolean);
    });

    console.log('Zonas dentro de la provincia:', zonasFiltradas,eventosFiltrados);
    window.zonasLayer = L.geoJSON(zonasFiltradas, {
        style: function(feature) {
            // Asigna un color único a cada zona
            const color = getColorForZona(feature.properties.id);
            return { color: color, weight: 2, fillOpacity: 0.4, fillColor: color };
        },
        onEachFeature: (feature, layer) => {
            layer.on('add', () => {
                    const el = layer.getElement();
                    if (el) {
                        el.setAttribute('tabindex', '-1');
                        el.style.outline = 'none';
                    }
                });

            let popupContent = '';
            let tooltipContent = '';
            if (feature.properties) {
                // Contenido para el Tooltip (al pasar el mouse)
                tooltipContent = `
                    <div>
                        <strong>Nombre:</strong> ${feature.properties.nombre || 'Sin nombre'}<br>
                        <strong>Descripción:</strong> ${feature.properties.descripcion || 'Sin descripción'}
                    </div>
                `;

                // Contenido para el Popup (al hacer clic)
                popupContent = `
                    <div style="max-height: 200px; overflow-y: auto; padding-right: 10px;">
                        <h5>${feature.properties.nombre || 'Zona sin nombre'}</h5>
                        <p><strong>Área:</strong> ${feature.properties.area?.area || 'No especificada'}</p>
                        <p><strong>Descripción:</strong> ${feature.properties.descripcion || 'Sin descripción'}</p>
                    </div>
                `;
            }

            layer.bindPopup(popupContent);
            layer.bindTooltip(tooltipContent, {
                permanent: false,
                direction: "top",
                opacity: 0.9,
                sticky: true
            });
        }
    }).addTo(map);

    // Actualizar la lista de resultados
    const lista = document.getElementById("resultado-zonas-lista");
    lista.innerHTML = "";
    zonasFiltradas.forEach(z => {
        const li = document.createElement("li");
        li.textContent = z.properties.nombre || "Zona sin nombre";
        lista.appendChild(li);
    });

    window.eventosLayer = L.geoJSON(eventosFiltrados, {
        pointToLayer: (feature, latlng) => {
            const color = eventoColors[feature.properties.tipo] || '#000000';
            debugger
            return L.circleMarker(latlng, {
                radius: 6,
                color: color,
                fillColor: color,
                fillOpacity: 0.7
            });
        },
        onEachFeature: (feature, layer) => {
            const tooltip = `
            <div>
                <strong>Evento:</strong> ${feature.properties.titulo}<br>
                <strong>Zona:</strong> ${feature.properties.zona_nombre}<br>
                <strong>Tipo:</strong> ${feature.properties.tipo}
            </div>
            `;
            layer.bindTooltip(tooltip, {
                permanent: false,
                direction: "top",
                opacity: 0.9,
                sticky: true
            });
        }
    }).addTo(map);

    if (window.zonasLayer.getLayers().length > 0) {
        map.fitBounds(window.zonasLayer.getBounds());

    } else {
        Swal.fire({ icon: 'info', title: 'No se encontraron zonas dentro de la provincia seleccionada' });
    }
});
document.getElementById("reset-zonas").addEventListener("click", () => {

    if (provinciaLayer) {
        map.removeLayer(provinciaLayer);
        provinciaLayer = null;
    }

    if (departamentoLayer) {
        map.removeLayer(departamentoLayer);
        departamentoLayer = null;
    }

    if (window.zonasLayer) {
        map.removeLayer(window.zonasLayer);
        window.zonasLayer = null;
    }
    if (window.eventosLayer) {
        map.removeLayer(window.eventosLayer);
        window.eventosLayer = null;
    }


    document.getElementById("departamento").value = "";
    limpiarSelect("provincia");
    document.getElementById("selects-provincias").style.display = "none";

    document.getElementById("resultado-zonas-lista").innerHTML = "";
    document.getElementById("reset-zonas").style.display = "none";
    document.getElementById("resultados-zonas").style.display = "none !important";
    if (window.municipioLayer) {
        map.removeLayer(window.municipioLayer);
        window.municipioLayer = null;
    }
    limpiarSelect("municipio");
    municipiosContainer.style.display = "none";
});

function toggleCard() {
    const card = document.querySelector('.map-card');
    card.classList.toggle('d-none');
}
let hideTimeout;

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


function cancelHideLayerPanel() {
    clearTimeout(hideTimeout);
}
let imageFiles = [];
let videoFiles = [];
$(document).ready(function() {
    $('#image-upload-area').on('click', function() {
        $('#imagen_zona').click();
    });

$('#imagen_zona').on('change', function () {
        const validImages = [];
        const invalidFiles = [];

        Array.from(this.files).forEach(file => {
            if (file.type.startsWith('image/')) {
                validImages.push(file);
            } else {
                invalidFiles.push(file.name);
            }
        });

        if (invalidFiles.length > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Archivo no permitido',
                html: `Los siguientes archivos no son imágenes válidas:<br><strong>${invalidFiles.join('<br>')}</strong>`,
                confirmButtonText: 'Entendido'
            });
        }

        imageFiles = [...imageFiles, ...validImages];
        updateImagePreviews();
        this.value = '';
    });


    function updateImagePreviews() {
        $('#image-preview').empty();
        let fileCount = imageFiles.length;
        $('#image-count-display').text(fileCount);

        if (fileCount > 0) {
            imageFiles.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const col = `<div class="col-12 col-sm-6 col-md-4 mb-2">
                                        <div class="position-relative">
                                            <img src="${e.target.result}" class="img-fluid rounded shadow-sm" style="max-height: 100px; width: 100%; object-fit: cover;">
                                            <button type="button" class="btn btn-sm btn-danger remove-image-btn position-absolute top-0 end-0 m-1" data-index="${index}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>`;
                        $('#image-preview').append(col);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    }

    $('#image-preview').on('click', '.remove-image-btn', function() {
        const indexToRemove = $(this).data('index');
        imageFiles.splice(indexToRemove, 1);
        updateImagePreviews();
    });
    $('#video-upload-area').on('click', function() {
        $('#video_zona').click();
    });

   $('#video_zona').on('change', function () {
        const validVideos = [];
        const invalidTypeFiles = [];
        const invalidSizeFiles = [];
        const MAX_VIDEO_SIZE_MB = 50; // Límite de 50 MB por video
        const MAX_VIDEO_SIZE_BYTES = MAX_VIDEO_SIZE_MB * 1024 * 1024;

        Array.from(this.files).forEach(file => {
            if (file.type.startsWith('video/')) {
                if (file.size <= MAX_VIDEO_SIZE_BYTES) {
                    validVideos.push(file);
                } else {
                    invalidSizeFiles.push({ name: file.name, size: (file.size / 1024 / 1024).toFixed(2) });
                }
            } else {
                invalidTypeFiles.push(file.name);
            }
        });

        if (invalidTypeFiles.length > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Archivo no permitido',
                html: `Los siguientes archivos no son videos válidos:<br><strong>${invalidFiles.join('<br>')}</strong>`,
                confirmButtonText: 'Entendido'
            });
        }

        if (invalidSizeFiles.length > 0) {
            const fileMessages = invalidSizeFiles.map(f => `<strong>${f.name}</strong> (${f.size} MB)`).join('<br>');
            Swal.fire({
                icon: 'warning',
                title: 'Videos demasiado grandes',
                html: `Los siguientes videos superan el límite de ${MAX_VIDEO_SIZE_MB} MB:<br>${fileMessages}`,
                confirmButtonText: 'Entendido'
            });
        }

        videoFiles = [...videoFiles, ...validVideos];
        updateVideoPreviews();
        this.value = '';
    });


    function updateVideoPreviews() {
        $('#video-preview').empty();
        let fileCount = videoFiles.length;
        $('#video-count-display').text(fileCount);

        if (fileCount > 0) {
            videoFiles.forEach((file, index) => {
                if (file.type.startsWith('video/')) {
                    const videoURL = URL.createObjectURL(file);
                    const col = `<div class="col-12 col-sm-6 col-md-4 mb-2">
                                    <div class="position-relative">
                                        <video src="${videoURL}" controls class="img-fluid rounded shadow-sm" style="max-height: 100px; width: 100%; object-fit: cover;"></video>
                                        <button type="button" class="btn btn-sm btn-danger remove-video-btn position-absolute top-0 end-0 m-1" data-index="${index}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>`;
                    $('#video-preview').append(col);
                }
            });
        }
    }
    $('#video-preview').on('click', '.remove-video-btn', function() {
        const indexToRemove = $(this).data('index');
        videoFiles.splice(indexToRemove, 1);
        updateVideoPreviews();
    });
    $('#image-count-display').text(0);
    $('#video-count-display').text(0);
});
//// incidencia ////
let imageFilesIncidencia = [];
let videoFilesIncidencia = [];
$(document).ready(function() {
    $('#image-upload-area-incidencia').on('click', function() {
        $('#imagen_zona_incidencia').click();
    });

   $('#imagen_zona_incidencia').on('change', function () {
        const validImages = [];
        const invalidFiles = [];

        Array.from(this.files).forEach(file => {
            if (file.type.startsWith('image/')) {
                validImages.push(file);
            } else {
                invalidFiles.push(file.name);
            }
        });

        if (invalidFiles.length > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Archivo no permitido',
                html: `Los siguientes archivos no son imágenes válidas:<br><strong>${invalidFiles.join('<br>')}</strong>`,
                confirmButtonText: 'Entendido'
            });
        }

        imageFilesIncidencia = [...imageFilesIncidencia, ...validImages];
        updateImagePreviews();
        this.value = '';
    });


    function updateImagePreviews() {
        $('#image-preview-incidencia').empty();
        let fileCount = imageFilesIncidencia.length;
        $('#image-count-display-incidencia').text(fileCount);

        if (fileCount > 0) {
            imageFilesIncidencia.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const col = `<div class="col-12 col-sm-6 col-md-4 mb-2">
                                        <div class="position-relative">
                                            <img src="${e.target.result}" class="img-fluid rounded shadow-sm" style="max-height: 100px; width: 100%; object-fit: cover;">
                                            <button type="button" class="btn btn-sm btn-danger remove-image-btn position-absolute top-0 end-0 m-1" data-index="${index}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>`;
                        $('#image-preview-incidencia').append(col);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    }

    $('#image-preview-incidencia').on('click', '.remove-image-btn', function() {
        const indexToRemove = $(this).data('index');
        imageFilesIncidencia.splice(indexToRemove, 1);
        updateImagePreviews();
    });
    $('#video-upload-area-incidencia').on('click', function() {
        $('#video_zona_incidencia').click();
    });

   $('#video_zona_incidencia').on('change', function () {
        const validVideos = [];
        const invalidTypeFiles = [];
        const invalidSizeFiles = [];
        const MAX_VIDEO_SIZE_MB = 50; // Límite de 50 MB por video
        const MAX_VIDEO_SIZE_BYTES = MAX_VIDEO_SIZE_MB * 1024 * 1024;

        Array.from(this.files).forEach(file => {
            if (file.type.startsWith('video/')) {
                if (file.size <= MAX_VIDEO_SIZE_BYTES) {
                    validVideos.push(file);
                } else {
                    invalidSizeFiles.push({ name: file.name, size: (file.size / 1024 / 1024).toFixed(2) });
                }
            } else {
                invalidTypeFiles.push(file.name);
            }
        });

        if (invalidTypeFiles.length > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Archivo no permitido',
                html: `Los siguientes archivos no son videos válidos:<br><strong>${invalidFiles.join('<br>')}</strong>`,
                confirmButtonText: 'Entendido'
            });
        }

        if (invalidSizeFiles.length > 0) {
            const fileMessages = invalidSizeFiles.map(f => `<strong>${f.name}</strong> (${f.size} MB)`).join('<br>');
            Swal.fire({
                icon: 'warning',
                title: 'Videos demasiado grandes',
                html: `Los siguientes videos superan el límite de ${MAX_VIDEO_SIZE_MB} MB:<br>${fileMessages}`,
                confirmButtonText: 'Entendido'
            });
        }

        videoFilesIncidencia = [...videoFilesIncidencia, ...validVideos];
        updateVideoPreviews();
        this.value = '';
    });


    function updateVideoPreviews() {
        $('#video-preview-incidencia').empty();
        let fileCount = videoFilesIncidencia.length;
        $('#video-count-display-incidencia').text(fileCount);

        if (fileCount > 0) {
            videoFilesIncidencia.forEach((file, index) => {
                if (file.type.startsWith('video/')) {
                    const videoURL = URL.createObjectURL(file);
                    const col = `<div class="col-12 col-sm-6 col-md-4 mb-2">
                                    <div class="position-relative">
                                        <video src="${videoURL}" controls class="img-fluid rounded shadow-sm" style="max-height: 100px; width: 100%; object-fit: cover;"></video>
                                        <button type="button" class="btn btn-sm btn-danger remove-video-btn position-absolute top-0 end-0 m-1" data-index="${index}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>`;
                    $('#video-preview-incidencia').append(col);
                }
            });
        }
    }
    $('#video-preview-incidencia').on('click', '.remove-video-btn', function() {
        const indexToRemove = $(this).data('index');
        videoFilesIncidencia.splice(indexToRemove, 1);
        updateVideoPreviews();
    });
    $('#image-count-display-incidencia').text(0);
    $('#video-count-display-incidencia').text(0);
});

function resetZonaFormIncidencia() {

    document.getElementById('form-zonas-incidencia').reset();


    document.getElementById('image-preview-incidencia').innerHTML = '';
    document.getElementById('video-preview-incidencia').innerHTML = '';


    document.getElementById('image-count-display-incidencia').textContent = '0';
    document.getElementById('video-count-display-incidencia').textContent = '0';


    imageFilesIncidencia = [];
    videoFilesIncidencia = [];


     drawnItemsIncidencia.clearLayers();
    toggleActionButtonsIncidencia();

    const coordInput = document.getElementById('coordenadas');
    if (coordInput) coordInput.value = '';
}


/// zonas-incidencia ///
document.getElementById('form-zonas-incidencia').addEventListener('submit', function (e) {
   
    e.preventDefault();
    showLoader();
    const form = e.target;
    const formData = new FormData(form);
    const mapCard = document.querySelector('.map-card-container');
    if (mapCard) mapCard.style.display = 'none';
    map.invalidateSize();
    map.panBy([0, 0]);

    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            html2canvas(document.getElementById('map'), {
                useCORS: true,
                backgroundColor: null,
                scale: 1
            }).then(canvas => {
                const resizedCanvas = document.createElement('canvas');
                const ctx = resizedCanvas.getContext('2d');
                const scaleFactor = 0.8;

                resizedCanvas.width = canvas.width * scaleFactor;
                resizedCanvas.height = canvas.height * scaleFactor;
                ctx.drawImage(canvas, 0, 0, resizedCanvas.width, resizedCanvas.height);

                const base64Image = resizedCanvas.toDataURL('image/jpeg', 0.7);
                formData.append('imagen_mapa', base64Image);

                if (mapCard) mapCard.style.display = '';

                imageFilesIncidencia.forEach(file => {
                    formData.append('imagen_zona_incidencia[]', file);
                });

                videoFilesIncidencia.forEach(file => {
                    formData.append('video_zona_incidencia[]', file);
                });

                formData.append('coordenadas', JSON.stringify(window.currentDrawnCoordinates));
                formData.append('tipo_coordenada', window.currentDrawnType);

                
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.errors) {
                        const errorBox = document.getElementById('errores-zona');
                        if (errorBox) {
                            let errores = Object.values(data.errors).flat().join('\n');
                            errorBox.innerText = errores;
                            errorBox.classList.remove('d-none');
                        }
                        return;
                    }

                    const successBox = document.getElementById('mensaje-exito-zona');
                    if (successBox) {
                        successBox.innerText = data.message || 'Zona guardada correctamente.';
                        successBox.classList.remove('d-none');
                        setTimeout(() => {
                            successBox.classList.add('d-none');
                            successBox.innerText = '';
                        }, 4000);
                    }
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Incidencia guardada correctamente",
                        showConfirmButton: false,
                        timer: 2000
                    });
                    form.reset();
                    resetZonaFormIncidencia();
                    actualizarListaAreas();
                    hideLoader();
                })
                .catch(error => {
                    if (error.errors) {
                        let errores = Object.values(error.errors).flat().join('\n');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al guardar',
                            text: errores,
                            confirmButtonText: 'Cerrar'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al guardar',
                            text: 'Ocurrió un error inesperado.',
                            confirmButtonText: 'Cerrar'
                        });
                    }
                    hideLoader();
                });
            });
        });
    });
});


function actualizarListaAreas() {
    fetch(RUTA_LISTADO_AREAS)
    .then(response => response.json())
    .then(data => {
        const select = document.getElementById('area_id');
        if (!select) {
            console.error('No existe el elemento select#area_id');
            return;
        }
        select.innerHTML = '<option value="">Seleccione un área</option>';

        data.forEach(area => {
            const option = document.createElement('option');
            option.value = area.id;
            option.textContent = area.area;
            select.appendChild(option);
        });
    })
    .catch(error => {
        console.error('Error al cargar áreas:', error);
    });
}
