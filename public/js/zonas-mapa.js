document.addEventListener('DOMContentLoaded', function() {
    // Función para obtener color único por zona
    function getColorForZona(zonaId) {
        // Paleta de colores distintivos
        const colors = [
            '#3388ff', '#ff3333', '#33ff33', '#ff33ff', '#33ffff',
            '#ffff33', '#ff9933', '#33ff99', '#9933ff', '#ff3399',
            '#3399ff', '#99ff33', '#ff33cc', '#33ccff', '#ccff33',
            '#ffcc33', '#33ffcc', '#cc33ff', '#ff3366', '#66ff33'
        ];

        // Usamos el ID de la zona para seleccionar un color
        return colors[zonaId % colors.length];
    }

    // Función para oscurecer un color hexadecimal
    function darkenColor(hex, percent) {
        // Eliminar el # si está presente
        hex = hex.replace(/^#/, '');

        // Convertir a RGB
        let r = parseInt(hex.substring(0, 2), 16);
        let g = parseInt(hex.substring(2, 4), 16);
        let b = parseInt(hex.substring(4, 6), 16);

        // Oscurecer cada componente
        r = Math.floor(r * (100 - percent) / 100);
        g = Math.floor(g * (100 - percent) / 100);
        b = Math.floor(b * (100 - percent) / 100);

        // Asegurarse de que no sean menores que 0
        r = Math.max(0, r);
        g = Math.max(0, g);
        b = Math.max(0, b);

        // Convertir de nuevo a hexadecimal
        const rr = r.toString(16).padStart(2, '0');
        const gg = g.toString(16).padStart(2, '0');
        const bb = b.toString(16).padStart(2, '0');

        return `#${rr}${gg}${bb}`;
    }

    // Configuración del mapa
    const cochabambaBounds = L.latLngBounds(
        L.latLng(-18.50, -67.50), // suroeste (frontera con Oruro y Potosí)
        L.latLng(-16.00, -64.00)  // noreste (frontera con Beni y Santa Cruz)
    );

    const map = L.map('map', {
        maxBounds: cochabambaBounds,
        maxBoundsViscosity: 1.0,
        minZoom: 10,
        zoom: 11
    }).setView([-17.3895, -66.1568], 11);

L.layerGroup([
    L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '&copy; Esri, i-cubed, USDA, USGS, etc.'
    }),
    L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Labels &copy; Esri'
    })
    ]).addTo(map);

    // Variables para manejar la selección
    let selectedPolygon = null;
    let selectedMarker = null;
    let selectedCircle = null;
    let viewDataModal = new bootstrap.Modal(document.getElementById('viewDataModal'));
    let editCreateDataModal = new bootstrap.Modal(document.getElementById('modalCrearDatos'));
    let currentZona = null;
    let currentHistorialId = null;
    let historialLayer = L.layerGroup().addTo(map);
    document.getElementById('cerrarBtn').addEventListener('click', function() {
        viewDataModal.hide();
    });
    // Capas para agrupar elementos
    const polygonLayer = L.layerGroup().addTo(map);
    const markerLayer = L.layerGroup().addTo(map);

    // Datos de las zonas (inyectados desde Blade)
    const zonasData = window.zonasData || [];
    
    // Función para dibujar elementos en el mapa
    function drawMapElements(coordenadasData, zona, layerGroup) {
        console.log('Dibujando elementos para zona:', zona.nombre, coordenadasData);
        if (!coordenadasData || !Array.isArray(coordenadasData)) return;

        const zonaColor = getColorForZona(zona.id);

        coordenadasData.forEach((item,index) => {
            if (!item || !item.tipo) return;

            if (item.tipo === 'marcador' && item.coordenadas) {
                const marker = L.marker([item.coordenadas.lat, item.coordenadas.lng], {
                    icon: L.divIcon({
                        className: 'custom-marker',
                        html: `<i class="fas fa-map-marker-alt" style="color: ${zonaColor}; font-size: 24px;"></i>`,
                        iconSize: [24, 24],
                        iconAnchor: [12, 24],
                    })
                }).addTo(layerGroup);

            } else if (item.tipo === 'poligono' && item.coordenadas && Array.isArray(item.coordenadas)) {
                const latlngs = item.coordenadas.map(coord => [coord.lat, coord.lng]);
                const polygon = L.polygon(latlngs, {
                    color: zonaColor,
                    fillColor: zonaColor,
                    fillOpacity: 0.4,
                    weight: 2
                }).addTo(layerGroup);

                polygon.zonaId = zona.id;

                polygon.on('click', function(e) {
                    handlePolygonClick(polygon, zona, zona.id);
                });
            };
        });
    }

    // Procesar cada zona con su último historial
    zonasData.forEach(zona => {
        if (zona.coordenadas && Array.isArray(zona.coordenadas)) {
            zona.coordenadas.forEach((item) => {
                if (!item || !item.tipo || !item.coordenadas) return;

                const layerToUse = item.tipo === 'marcador' ? markerLayer : polygonLayer;

                drawMapElements([item], zona, layerToUse);
            });
        }

        else if (zona.coordenadas && Array.isArray(zona.coordenadas[0]) && Array.isArray(zona.coordenadas[0][0])) {

            const coordsParaPoligono = zona.coordenadas[0].map(coord => ({ 
                lat: coord[0],
                lng: coord[1],
            }));

            const coordenadasNormalizadas = [{
                tipo: 'poligono',
                coordenadas: coordsParaPoligono
            }];

            drawMapElements(coordenadasNormalizadas, zona, polygonLayer);

        } else {
            console.warn(`Zona ${zona.nombre} (ID: ${zona.id}) tiene coordenadas en formato inesperado o vacio.`, zona.coordenadas);
        }
    });
        document.querySelectorAll('.zona-detalle-btn').forEach(btn => {
            const zonaId = parseInt(btn.dataset.zonaId);

            btn.addEventListener('mouseenter', () => {
                polygonLayer.eachLayer(layer => {
                    if (layer.zonaId === zonaId) {
                        layer.setStyle({
                            fillOpacity: 0.8, 
                            weight: 4         
                        });
                    }
                });
            });

            btn.addEventListener('mouseleave', () => {
                polygonLayer.eachLayer(layer => {
                    if (layer.zonaId === zonaId) {
                        layer.setStyle({
                            fillOpacity: 0.4, 
                            weight: 2         
                        });
                    }
                });
            });
        });

    function showLoader() {
        const loader = document.getElementById('loader-container');
        loader.style.display = 'flex';
        document.getElementById('data-content').style.opacity = '0.5';
    }

    function hideLoader() {
        document.getElementById('loader-container').style.display = 'none';
        document.getElementById('data-content').style.opacity = '1';
    }

    function handlePolygonClick(polygon, zona,index) {
        if (selectedPolygon) {
            const originalColor = getColorForZona(selectedPolygon.zonaId);
            selectedPolygon.setStyle({
                color: originalColor,
                fillColor: originalColor,
                fillOpacity: 0.4,
                weight: 2
            });
        }


        const zonaColor = getColorForZona(zona.id);
        const darkerColor = darkenColor(zonaColor, 30); 

        polygon.setStyle({
            color: darkerColor,
            fillColor: darkerColor,
            fillOpacity: 0.7,
            weight: 4
        });
        selectedPolygon = polygon;
        currentZona = zona;
        currentHistorialId = null;

        // Crear o mover marcador al centroide
        const center = polygon.getBounds().getCenter();
        updateSelectedMarker(center);

        // Mostrar loader y ocultar contenido
        showLoader();
        document.getElementById('data-content').innerHTML = '';

        // Mostrar modal de vista primero
        viewDataModal.show();

        // Hacer la petición AJAX para obtener los datos
        setTimeout(() => {
            fetch(`/detalle/${zona.id}/zonas`)
                .then(response => {
                    if (!response.ok) throw new Error('Error en la respuesta');
                    return response.json();
                })
                .then(data => {
                    hideLoader();
                    if (data.datos) {
                        showZonaData(zona, data.datos);
                    } else {
                        showZonaData(zona, null);
                    }
                })
                .catch(error => {
                    hideLoader();
                    console.error('Error:', error);
                    document.getElementById('data-content').innerHTML = `
                        <div class="alert alert-danger">
                            Error al cargar los datos. Intente nuevamente.
                        </div>
                    `;
                });
        }, 1000);
    }

    // Función para manejar el click en marcadores
    function handleMarkerClick(marker, zona) {
        currentZona = zona;
        currentHistorialId = null;

        // Crear o mover marcador al centroide
        updateSelectedMarker(marker.getLatLng());

        // Mostrar loader y ocultar contenido
        showLoader();
        document.getElementById('data-content').innerHTML = '';

        // Mostrar modal de vista primero
        viewDataModal.show();

        // Hacer la petición AJAX para obtener los datos
        setTimeout(() => {
            fetch(`/detalle/${zona.id}/zonas`)
                .then(response => {
                    if (!response.ok) throw new Error('Error en la respuesta');
                    return response.json();
                })
                .then(data => {
                    hideLoader();
                    if (data.datos) {
                        showZonaData(zona, data.datos);
                    } else {
                        showZonaData(zona, null);
                    }
                })
                .catch(error => {
                    hideLoader();
                    console.error('Error:', error);
                    document.getElementById('data-content').innerHTML = `
                        <div class="alert alert-danger">
                            Error al cargar los datos. Intente nuevamente.
                        </div>
                    `;
                });
        }, 1000);
    }

    // Función para actualizar el marcador seleccionado
    function updateSelectedMarker(latlng) {
        if (selectedMarker) {
            selectedMarker.setLatLng(latlng);
        } else {
            selectedMarker = L.marker(latlng, {
                icon: L.divIcon({
                    className: 'selected-marker',
                    html: '<i class="fas fa-map-marker-alt" style="color:rgb(114, 229, 149); font-size: 32px;"></i>',
                    iconSize: [32, 32],
                    iconAnchor: [16, 32]
                })
            }).addTo(map);
        }

        // Añadir un círculo para resaltar la ubicación
        if (selectedCircle) {
            selectedCircle.setLatLng(latlng);
        } else {
            selectedCircle = L.circle(latlng, {
                radius: 100,
                color: '#1a5fb4',
                fillColor: '#1a5fb4',
                fillOpacity: 0.2
            }).addTo(map);
        }
    }

    // Función para mostrar datos existentes
    function showZonaData(zona, datos) {
        let html = `<h4>${zona.nombre}</h4>`;
        html += `<p><strong>Área:</strong> ${zona.area || 'No especificado'}</p>`;

        if (datos && Object.keys(datos).length > 0) {
            html += `
                <div class="row">
                    <div class="col-md-12">
                        <p><strong>Extensión:</strong> ${datos.extension || 'No registrado'}</p>
                        <p><strong>Población:</strong> ${datos.poblacion || 'No registrado'}</p>
                        <p><strong>Provincia:</strong> ${datos.provincia || 'No registrado'}</p>
                        <p><strong>Flora y Fauna:</strong><br>${datos.flora_fauna || 'No registrado'}</p>
                        <p><strong>Especies en peligro:</strong><br>${datos.especies_peligro || 'No registrado'}</p>
                        <p><strong>Otros datos:</strong><br>${datos.otros_datos || 'No registrado'}</p>
                    </div>
                </div>
            `;
            if (datos.imagenes && datos.imagenes.length > 0) {
                    html += `<div class="row mt-3"><h5>Imágenes</h5>`;
                    datos.imagenes.forEach(img => {
                        html += `
                            <div class="col-4 mb-2">
                                <img src="/storage/${img.path}" class="img-fluid img-thumbnail" style="height:120px; object-fit:cover;">
                            </div>
                        `;
                    });
                    html += `</div>`;
            }
            if (datos.medios && datos.medios.length > 0) {
                // Filtramos solo los videos
                const videos = datos.medios.filter(m => m.tipo === 'video');
                if (videos.length > 0) {
                    html += `<div class="row mt-3"><h5>Videos</h5>`;
                    videos.forEach(video => {
                        html += `
                            <div class="col-md-6 mb-2">
                                <video controls style="width:100%; max-height:200px;">
                                    <source src="/storage/${video.path}" type="video/mp4">
                                    Tu navegador no soporta reproducir este video.
                                </video>
                            </div>
                        `;
                    });
                    html += `</div>`;
                }

                // Filtramos solo los documentos
                const documentos = datos.medios.filter(m => m.tipo === 'documento');
                if (documentos.length > 0) {
                    html += `<div class="row mt-3"><h5>Documentos</h5>`;
                    documentos.forEach(doc => {
                        html += `
                            <div class="col-md-12 mb-1">
                                <a href="/storage/${doc.path}" target="_blank">${doc.descripcion || doc.path.split('/').pop()}</a>
                            </div>
                        `;
                    });
                    html += `</div>`;
                }
            }
        } else {
            html += `
                <div class="alert alert-info">
                    No hay datos adicionales registrados para esta zona.
                </div>
                <button class="btn btn-primary" id="btn-agregar-datos">
                    Agregar Datos
                </button>
            `;
        }

        // Mostrar historial de ubicaciones si existe
        if (zona.historial && zona.historial.length > 0) {
            html += `
                <div class="historial-ubicaciones">
                    <h5>Historial de Ubicaciones</h5>
                    <div class="historial-list">
            `;

            zona.historial.forEach((hist, index) => {
                const isActive = currentHistorialId === hist.id || (currentHistorialId === null && index === 0);
                html += `
                    <div class="historial-item ${isActive ? 'active' : ''}"
                        data-historial-id="${hist.id}"
                        data-coordenadas='${JSON.stringify(hist.coordenadas)}'>
                        <strong>Versión ${index + 1}</strong>
                        <small>${hist.created_at}</small>
                    </div>
                `;
            });

            html += `
                    </div>
                </div>
            `;
        }

        document.getElementById('data-content').innerHTML = html;

        // Configurar eventos para los items del historial
        document.querySelectorAll('.historial-item').forEach(item => {
            item.addEventListener('click', function() {
                const historialId = this.dataset.historialId;
                const coordenadas = JSON.parse(this.dataset.coordenadas);

                // Actualizar la clase activa
                document.querySelectorAll('.historial-item').forEach(i => {
                    i.classList.remove('active');
                });
                this.classList.add('active');

                // Limpiar capa de historial
                historialLayer.clearLayers();

                // Dibujar la ubicación seleccionada
                drawMapElements(coordenadas, zona, historialLayer);

                // Encontrar el centro para centrar el mapa
                let center = null;
                if (coordenadas && coordenadas.length > 0) {
                    const firstItem = coordenadas[0];
                    if (firstItem.tipo === 'marcador') {
                        center = L.latLng(firstItem.coordenadas.lat, firstItem.coordenadas.lng);
                    } else if (firstItem.tipo === 'poligono' && firstItem.coordenadas.length > 0) {
                        const bounds = L.latLngBounds(firstItem.coordenadas.map(c => [c.lat, c.lng]));
                        center = bounds.getCenter();
                    }
                }

                if (center) {
                    updateSelectedMarker(center);
                    map.setView(center, map.getZoom());
                }

                currentHistorialId = historialId;
            });
        });

        // Botón para agregar datos si no existen
        const btnAgregar = document.getElementById('btn-agregar-datos');
        if (btnAgregar) {
            btnAgregar.addEventListener('click', () => {
                document.getElementById('formCrearDatos').reset();
                document.getElementById('dato_id').value = '';
                document.querySelector('#formCrearDatos input[name="zona_id"]').value = currentZona.id;
                document.getElementById('preview-existing').innerHTML = '';
                document.getElementById('preview-videos-existing').innerHTML = '';
                document.getElementById('preview-documentos-existing').innerHTML = '';
                viewDataModal.hide();
                editCreateDataModal.show();
            });
        }

        // Configurar el botón de editar para cargar los datos en el formulario
        document.getElementById('edit-data-btn').onclick = function() {
            if (datos) {
                // Llenar el formulario con los datos existentes
                document.getElementById('dato_id').value = datos.id || '';
                document.querySelector('#formCrearDatos input[name="zona_id"]').value = currentZona.id;
                document.getElementById('flora_fauna').value = datos.flora_fauna || '';
                document.getElementById('extension').value = datos.extension || '';
                document.getElementById('poblacion').value = datos.poblacion || '';
                document.getElementById('provincia').value = datos.provincia || '';
                document.getElementById('especies_peligro').value = datos.especies_peligro || '';
                document.getElementById('otros_datos').value = datos.otros_datos || '';
                
                // Limpiar previews anteriores
                document.getElementById('preview-existing').innerHTML = '';
                document.getElementById('preview-videos-existing').innerHTML = '';
                document.getElementById('preview-documentos-existing').innerHTML = '';
                document.getElementById('preview').innerHTML = '';
                document.getElementById('imagenes_eliminar').value = '[]';
                document.getElementById('medios_eliminar').value = '[]';

                if (datos.imagenes && datos.imagenes.length > 0) {
                    datos.imagenes.forEach(img => {
                        const wrapper = document.createElement('div');
                        wrapper.classList.add('position-relative', 'm-2');
                        wrapper.innerHTML = `
                            <img src="/storage/${img.path}" class="img-thumbnail" style="height:120px;object-fit:cover;">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-existing" data-id="${img.id}">✕</button>
                        `;
                        document.getElementById('preview-existing').appendChild(wrapper);
                    });
                }
                if (datos.medios && datos.medios.length > 0) {
                    const existingVideos = datos.medios.filter(m => m.tipo === 'video');
                    if (existingVideos.length > 0) {
                        const previewExistingVideos = document.getElementById('preview-videos-existing');
                        existingVideos.forEach(video => {
                            const wrapper = document.createElement('div');
                            wrapper.classList.add('position-relative', 'd-inline-block', 'm-2');
                            wrapper.innerHTML = `
                                <video controls style="width:150px; height:100px; object-fit:cover; border: 1px solid #ddd;">
                                    <source src="/storage/${video.path}" type="video/mp4">
                                </video>
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-media-existing" data-id="${video.id}">✕</button>
                            `;
                            previewExistingVideos.appendChild(wrapper);
                        });
                    }
                    const existingDocumentos = datos.medios.filter(m => m.tipo === 'documento');
                    if (existingDocumentos.length > 0) {
                        const previewExistingDocumentos = document.getElementById('preview-documentos-existing');
                        existingDocumentos.forEach(doc => {
                            const wrapper = document.createElement('div');
                            wrapper.classList.add('position-relative', 'd-inline-block', 'm-2');
                            wrapper.innerHTML = `
                                <div style="width:150px; height:100px; border: 1px solid #ddd; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa; border-radius: 4px;">
                                    <i class="fas fa-file-pdf text-primary" style="font-size: 40px;"></i>
                                </div>
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-media-existing" data-id="${doc.id}">✕</button>
                            `;
                            previewExistingDocumentos.appendChild(wrapper);
                        });
                    }
                }
            }
            viewDataModal.hide();
            editCreateDataModal.show();
        };
    }

    let imagenesAEliminar = [];
    let mediosAEliminar = [];

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-existing')) {
            const id = e.target.getAttribute('data-id');
            if (!imagenesAEliminar.includes(id)) {
                imagenesAEliminar.push(id);
            }
            e.target.parentElement.remove();
            document.getElementById('imagenes_eliminar').value = JSON.stringify(imagenesAEliminar);
        }
        else if (e.target.classList.contains('remove-media-existing')) {
            const id = e.target.getAttribute('data-id');
            if (!mediosAEliminar.includes(id)) {
                mediosAEliminar.push(id);
            }
            e.target.parentElement.remove();
            document.getElementById('medios_eliminar').value = JSON.stringify(mediosAEliminar);
        }
    });

    // Delegación de eventos para los botones en los popups
    document.addEventListener('click', function(e) {
        const detalleBtn = e.target.closest('.zona-detalle-btn');
        if (detalleBtn) {
            e.preventDefault();
            const zonaId = detalleBtn.dataset.zonaId;
            const zona = zonasData.find(z => z.id == zonaId);

            if (!zona) return;

            // Centrar el mapa en el polígono/marcador
            polygonLayer.eachLayer(layer => {
                if (layer.zonaId == zonaId) {
                    handlePolygonClick(layer, zona, zonaId);
                }
            });
        }
    });

    // Validación y envío del formulario de creación/edición
    const formCrearDatos = document.getElementById('formCrearDatos');
    formCrearDatos.addEventListener('submit', function(event) {
        event.preventDefault();
        event.stopPropagation();

        if (!formCrearDatos.checkValidity()) {
            formCrearDatos.classList.add('was-validated');
            return;
        }

        const formData = new FormData(formCrearDatos);
        const submitBtn = formCrearDatos.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;

        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Guardando...';
        submitBtn.disabled = true;

        fetch(formCrearDatos.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Datos guardados!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                editCreateDataModal.hide();
                // Opcional: Recargar la página o actualizar datos dinámicamente
                setTimeout(() => location.reload(), 1500);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Ocurrió un error al guardar los datos.'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error de Conexión',
                text: 'No se pudo enviar la información.'
            });
        })
        .finally(() => {
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        });
    });

    // Ajustar vista para mostrar todas las zonas
    const allLayers = L.featureGroup([polygonLayer, markerLayer]);
    if (allLayers.getLayers().length > 0) {
        map.fitBounds(allLayers.getBounds().pad(0.2));
    }

    // Manejo de eliminación de zona
    document.querySelectorAll('.btn-eliminar-zona').forEach(button => {
        button.addEventListener('click', () => {
            const zonaId = button.dataset.id;
            const zonaNombre = button.dataset.nombre;

            Swal.fire({
                title: '¿Eliminar zona?',
                html: `¿Seguro que quieres eliminar <strong>${zonaNombre}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(`/zonas/${zonaId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Eliminado', data.message, 'success').then(() => {
                                button.closest('tr').remove();
                                // Opcional: remover del mapa también
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(() => {
                        Swal.fire('Error', 'Error al eliminar la zona', 'error');
                    });
                }
            });
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("imagenes");
    const preview = document.getElementById("preview");

    let filesArray = [];

    if (input) {
        input.addEventListener("change", function (e) {
            const newFiles = Array.from(e.target.files);
            filesArray = filesArray.concat(newFiles);

            updateInputFiles();
            renderPreviews();
        });
    }

    function renderPreviews() {
        preview.innerHTML = "";
        filesArray.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const div = document.createElement("div");
                div.classList.add("position-relative", "d-inline-block", "m-1");

                div.innerHTML = `
                    <img src="${e.target.result}" class="img-thumbnail"
                        style="width:120px; height:120px; object-fit:cover;">
                    <button type="button" 
                            class="btn btn-sm btn-danger btn-remove position-absolute top-0 end-0"
                            data-index="${index}">&times;</button>
                `;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    function updateInputFiles() {
        const dt = new DataTransfer();
        filesArray.forEach(f => dt.items.add(f));
        input.files = dt.files;
    }

    if (preview) {
        preview.addEventListener("click", function (e) {
            const btn = e.target.closest(".btn-remove"); 
            if (!btn) return;

            const index = btn.getAttribute("data-index");
            filesArray.splice(index, 1);

            updateInputFiles();
            renderPreviews();
        });
    }
});
