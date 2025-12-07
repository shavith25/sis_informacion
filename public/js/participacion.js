document.addEventListener("DOMContentLoaded", () => {

    /**
     * @param {string} name - El nombre de la ruta.
     * @returns {string|null} La URL de la API/Web.
     */
    const getRoute = (name) => {
        const routes = {
            // Sugerencias (Web)
            'sugerencias.index': '/sugerencias', 
            'sugerencias.store': '/sugerencias', 

            // Reportes (Web)
            'reportes.index': '/api/reportes', 
            'reportes.store': '/api/reportes', 

            // Archivos (Media) (Web)
            'archivos.index': '/api/archivos', 
            'archivos.store': '/api/archivos',
            'api.comentarios.index': '/api/comentarios',
            'api.comentarios.store': '/api/comentarios',
            'api.reportes.index': '/api/reportes',
            'api.reportes.store': '/api/reportes',
            'api.archivos.index': '/api/archivos',
            'api.archivos.store': '/api/archivos',
        };
        
        if (!routes[name]) {
            console.error(`Ruta no definida: ${name}`);
        }
        return routes[name] || null;
    }

    /**
     * Muestra una notificación usando SweetAlert2.
     * @param {string} icon - 'success', 'error', 'warning'.
     * @param {string} title - Título del modal.
     * @param {string} text - Contenido del modal.
     */
    const  showAlert = (icon, title, text) => {
        Swal.fire({
            icon,
            title,
            text,
            confirmButtonColor: icon === 'success' ? '#9471f3' : '#dc3545',
        });
    };

    /**
     * Carga datos desde una API, los renderiza y maneja errores.
     * @param {string} routeName - Nombre de la ruta API.
     * @param {HTMLElement} listElement - Elemento del DOM para renderizar.
     * @param {Function} renderFunction - Función que genera el HTML.
     * @param {string} entityName - Nombre de la entidad para mensajes de error.
     * @returns {Promise<void>}
     */
    const loadAndRender = async (routeName, listElement, renderFunction, entityName) => {
        if (!listElement) return;

        try {
            const route = getRoute(routeName);
            if (!route) throw new Error(`Ruta no definida: ${routeName}`);

            const response = await axios.get(route);
            
            const dataToRender = response.data.data !== undefined ? response.data.data : response.data;

            listElement.innerHTML = renderFunction(dataToRender);
        } catch (error) {
            console.error(`Error al cargar ${entityName}:`, error.response || error);
            listElement.innerHTML = `<p class="text-center text-red-500 mt-4">Error al cargar ${entityName}. Por favor, intente de nuevo.</p>`;
        }
    };

    // --- SECCIÓN DE COMENTARIOS 💬 ---
    const commentsList = document.getElementById("commentsList");
    const commentForm = document.getElementById("commentForm");

    if (commentForm && commentsList) {
        /**
         * Renderiza recursivamente los comentarios.
         */
        const renderComments = (comments) => {
            if (!comments || comments.length === 0) {
                return '';
            }

            return comments.map(c => `
                <div class="comment border-b border-gray-200 p-4 mb-4 rounded-lg shadow-sm bg-white" data-comment-id="${c.id}">
                    <div class="comment-header flex justify-between items-center mb-2 text-sm text-purple-700">
                        <strong><i class="fas fa-user mr-1"></i> ${c.nombre}</strong>
                        <span class="text-xs text-gray-500"><i class="far fa-calendar mr-1"></i> ${new Date(c.created_at).toLocaleDateString('es-ES')}</span>
                    </div>
                    <p class="mb-3 text-gray-700">${c.comentario}</p>
                    <div class="comment-actions flex space-x-3">
                        <button class="btn btn-sm btn-outline-secondary reply-btn">
                            <i class="fas fa-reply mr-1"></i> Responder
                        </button>
                    </div>
                    <div class="reply-form mt-3 p-3 bg-gray-50 rounded" style="display:none; margin-left:20px;">
                        <input type="text" class="form-control form-control-sm mb-2 reply-name" placeholder="Tu nombre" required>
                        <textarea class="form-control form-control-sm mb-2 reply-text" placeholder="Tu respuesta" required></textarea>
                        <button class="btn btn-sm btn-success send-reply-btn">Enviar</button>
                    </div>
                    ${c.respuestas_aprobadas && c.respuestas_aprobadas.length ? `
                        <div class="replies border-l-2 border-purple-200 ml-4 pl-4 mt-4">
                            ${renderComments(c.respuestas_aprobadas)}
                        </div>` : ''}
                </div>
            `).join('');
        };

        const loadComments = async () => {
            try {
                // Se utiliza la ruta /api/comentarios-participacion para obtener los datos
                const res = await apiCall('comentarios.index');
                commentsList.innerHTML = renderComments(res.data);
            } catch (error) {
                console.error("Error al cargar comentarios:", error);
                commentsList.innerHTML = "<p class='text-center text-red-500 mt-4'>Error al cargar los comentarios.</p>";
            }
        };

        // Manejo de formulario de Comentario Principal
        commentForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const submitBtn = e.target.querySelector('button[type="submit"]');
            
            const data = {
                nombre: document.getElementById("nombre").value.trim(),
                comentario: document.getElementById("comentario").value.trim(),
            };

            if (!data.nombre || !data.comentario) {
                showAlert("warning", "Campos vacíos", "Por favor, completa tu nombre y comentario.");
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Publicando...';

            try {
                const response = await axios.post(getRoute('api.comentarios.store'), data);
                commentForm.reset();
                
                const successMessage = response.data.message || 'Gracias por tu participación. Tu comentario será revisado por el administrador del sistema.';

                showAlert('success', '¡Comentario Enviado!', successMessage);

                loadAndRender('api.comentarios.index', commentsList, renderComments, 'comentarios');
            } catch (error) {
                console.error("Error al publicar comentario:", error.response || error);
                
                let errorMessage = 'No se pudo publicar el comentario. Revisa los campos.';
                
                if (error.response?.status === 422 && error.response.data.errors) {
                    const firstError = Object.values(error.response.data.errors)[0][0];
                    errorMessage = `Error de validación: ${firstError}`;
                } else if (error.response?.data?.message) {
                    errorMessage = error.response.data.message;
                }
                
                showAlert('error', 'Error', errorMessage);
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Publicar comentario';
            }
        });

        // Manejo de respuestas (replies)
        commentsList.addEventListener('click', async (e) => {
            const target = e.target;
            const commentDiv = target.closest('.comment');
            if (!commentDiv) return;

            const commentId = commentDiv.dataset.commentId;
            const replyBtn = target.closest('.reply-btn');
            const sendReplyBtn = target.closest('.send-reply-btn');

            if (replyBtn) {
                const form = commentDiv.querySelector('.reply-form');
                form.style.display = form.style.display === 'none' ? 'block' : 'none';
            }

            if (sendReplyBtn) {
                const form = sendReplyBtn.closest('.reply-form');
                const nombreInput = form.querySelector('.reply-name');
                const comentarioInput = form.querySelector('.reply-text');
                
                const nombre = nombreInput.value.trim();
                const comentario = comentarioInput.value.trim();

                if (!nombre || !comentario) {
                    showAlert("warning", "Campos vacíos", "Por favor completa tu nombre y respuesta.");
                    return;
                }
                
                sendReplyBtn.disabled = true;
                
                try {
                    await axios.post(getRoute('api.comentarios.store'), {
                        nombre,
                        comentario,
                        parent_id: commentId
                    });
                    
                    form.style.display = 'none';
                    nombreInput.value = '';
                    comentarioInput.value = '';
                    
                    showAlert('success', 'Respuesta Enviada', 'Tu respuesta ha sido enviada y será revisada por el administrador del sistema.');
                    
                    loadAndRender('api.comentarios.index', commentsList, renderComments, 'comentarios');
                } catch (error) {
                    console.error("Error al enviar respuesta:", error);
                    showAlert('error', 'Error', 'No se pudo enviar la respuesta.');
                } finally {
                    sendReplyBtn.disabled = false;
                }
            }
        });

        // La carga inicial se moverá para ejecutarse en paralelo.
    }

    // --- SECCIÓN DE SUGERENCIAS 💡 --- (Lógica movida a concientizacion.js para centralizar)
    // const suggestionsList = document.getElementById("approvedSuggestionsList");
    // const suggestionForm = document.getElementById("suggestionForm");

    // if (suggestionForm && approvedSuggestionsList && typeof bootstrap !== 'undefined') {
    //     const suggestionModal = new bootstrap.Modal(document.getElementById('suggestionModal'));

    //     const renderSuggestions = (suggestions) => {
    //         if (!suggestions.length) {
    //             return "<p class='text-center text-muted'>Sé el primero en dejar una sugerencia para proteger nuestras áreas protegidas.</p>";
    //         }

    //         return suggestions.map(s => `
    //             <div class="col>
    //                 <div class="card h-100 shadow-sm suggestion-approved">
    //                     <div class="card-header">
    //                         <i class="fas fa-check-circle me-1"></i> ${s.titulo}
    //                     </div>
    //                     <div class="card-body d-flex flex-column">
    //                         <h5 class="card-title font-weight-bold">${s.titulo}</h5>
    //                         <p class="card-text flex-grow-1">${s.contenido}</p>
    //                     </div>
    //                     <div class="card-footer d-flex justify-content-between bg-light border-top">
    //                         <small class="text-muted"><i class="fas fa-user me-1"></i> ${s.nombre}</small>
    //                         <small class="text-muted"><i class="far fa-calendar me-1"></i> ${new Date(s.created_at).toLocaleDateString('es-ES')}</small>
    //                     </div>
    //                 </div>
    //             </div>
    //         `).join('');
    //     };
        
    //     /**
    //      * Carga y renderiza solo las sugerencias aprobadas.
    //      */
    //     const loadApprovedSuggestions = async () => {
    //         await loadAndRender('api.sugerencias.index', approvedSuggestionsList, renderSuggestions, 'sugerencias');
    //     };

    //     suggestionForm.addEventListener("submit", async (e) => {
    //         e.preventDefault();
    //         const submitBtn = e.target.querySelector('button[type="submit"]');
            
    //         const data = {
    //             nombre: document.getElementById("sugerencia_nombre").value.trim(),
    //             titulo: document.getElementById("sugerencia_tema").value.trim(),
    //             contenido: document.getElementById("sugerencia_contenido").value.trim(),
    //         };

    //         submitBtn.disabled = true;
    //         submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Publicando...';

    //         try {
    //             await axios.post(getRoute('api.sugerencias.store'), data);
    //             suggestionForm.reset();
    //             suggestionModal.hide();
    //             showAlert('success', '¡Sugerencia enviada!', 'Tu tema ha sido registrado y será verificado por el administrador del sistema.');
    //             //loadApprovedSuggestions();

    //         } catch (error) {
    //             //loadAndRender('api.sugerencias.index', suggestionsList, renderSuggestions, 'sugerencias');
    //             console.error("Error al enviar sugerencia:", error.response || error);
    //             showAlert('error', 'Error al enviar', error.response?.data?.message || 'Ocurrió un error.');
    //         } finally {
    //             submitBtn.disabled = false;
    //             submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Publicar Sugerencia';
    //         }
    //     });

    //     // La carga inicial se moverá para ejecutarse en paralelo.
    // }

    // --- SECCIÓN DE REPORTES 🚨 ---
    const reportsList = document.getElementById("reportsList");
    const reportForm = document.getElementById("reportForm");

    if (reportForm && reportsList && typeof bootstrap !== 'undefined') {
        const reportModal = new bootstrap.Modal(document.getElementById('reportModal'));

        const renderReports = (reports) => {
            if (!reports.length) {
                return "<p class='text-center text-muted'>¡La comunidad aún no ha reportado problemas! Sé el primero en contribuir.</p>";
            }
            return reports.map(r => `
                <div class="suggestion-card p-4 mb-4 bg-red-50 rounded-lg shadow-md border-l-4 border-red-500">
                    <h4 class="text-lg font-semibold text-red-700 mb-1"><i class="fas fa-exclamation-triangle mr-2"></i> ${r.titulo}</h4>
                    <p class="text-gray-700 mb-3">${r.contenido}</p>
                    <div class="suggestion-meta text-xs text-gray-500 flex justify-between">
                        <span><i class="fas fa-user mr-1"></i> Reportado por: <strong>${r.nombre}</strong></span>
                        <span><i class="far fa-calendar mr-1"></i> ${new Date(r.created_at).toLocaleDateString('es-ES')}</span>
                    </div>
                </div>
            `).join('');
        };

        const loadReports = async () => {
            try {
                const res = await apiCall('reportes.index'); 
                reportsList.innerHTML = renderReports(res.data);
            } catch (error) {
                console.error("Error al cargar reportes:", error);
                reportsList.innerHTML = "<p>Hubo un error al cargar los reportes.</p>";
            }
        };

        reportForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const submitBtn = e.target.querySelector('button[type="submit"]');
            
            const data = {
                nombre: document.getElementById("reporte_nombre").value.trim(),
                titulo: document.getElementById("reporte_tema").value.trim(),
                contenido: document.getElementById("reporte_contenido").value.trim(),
            };
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';

            try {
                await axios.post(getRoute('api.reportes.store'), data);
                reportForm.reset();
                reportModal.hide();
                showAlert('success', '¡Reporte enviado!', 'Tu reporte ha sido registrado.');
                loadAndRender('api.reportes.index', reportsList, renderReports, 'reportes');
            } catch (error) {
                console.error("Error al enviar reporte:", error.response || error);
                showAlert('error', 'Error al enviar', error.response?.data?.message || 'Ocurrió un error.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar Reporte';
            }
        });

        // La carga inicial se moverá para ejecutarse en paralelo.
    }

    // --- SECCIÓN DE MEDIA (VIDEOS E IMÁGENES) 📸 ---
    const videosList = document.getElementById("videosList");
    const imagesList = document.getElementById("imagesList");
    const mediaForm = document.getElementById("mediaForm");

    if (mediaForm && (videosList || imagesList) && typeof bootstrap !== 'undefined') {
        const mediaModal = new bootstrap.Modal(document.getElementById('mediaModal'));

        const renderMedia = (items) => {
            if (!items.length) {
                return "<p class='text-center text-muted'>Aún no se ha compartido media. ¡Sé el primero!</p>";
            }
            return items.map(item => {
                const isVideo = item.mime_type.startsWith('video');
                const iconClass = isVideo ? 'fas fa-video' : 'fas fa-images';
                
                const fileTag = isVideo
                    ? `<video controls width="100%" class="rounded"><source src="/storage/${item.ruta_archivo}" type="${item.mime_type}"></video>`
                    : `<img src="/storage/${item.ruta_archivo}" alt="${item.titulo}" style="width:100%; height:auto; object-fit:cover;" class="rounded">`;

                return `
                    <div class="video-card">
                        ${fileTag}
                        <div class="video-info">
                            <h3><i class="${iconClass}"></i> ${item.titulo}</h3>
                            <p>${item.descripcion}</p>
                            <div class="video-meta">
                                <span><i class="fas fa-user"></i> ${item.nombre}</span>
                                <small><i class="far fa-calendar"></i> ${new Date(item.created_at).toLocaleDateString('es-ES')}</small>
                            </div>
                        </div>
                    </div>`;
            }).join('');
        };

        /**
         * Carga los archivos multimedia una sola vez y los distribuye
         * entre la lista de videos e imágenes.
         */
        const loadMedia = async () => {
            try {
                const response = await axios.get(getRoute('api.archivos.index'));
                const allMedia = response.data.data || response.data;

                if (videosList) {
                    videosList.innerHTML = renderMedia(allMedia.filter(item => item.mime_type.startsWith('video')));
                }
                if (imagesList) {
                    imagesList.innerHTML = renderMedia(allMedia.filter(item => item.mime_type.startsWith('image')));
                }
            } catch (error) {
                console.error('Error al cargar media:', error.response || error);
                if (videosList) videosList.innerHTML = `<p class="text-center text-red-500 mt-4">Error al cargar videos.</p>`;
                if (imagesList) imagesList.innerHTML = `<p class="text-center text-red-500 mt-4">Error al cargar imágenes.</p>`;
            }
        };


        mediaForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const submitBtn = e.target.querySelector('button[type="submit"]');
            const fileInput = document.getElementById('media_file');

            if (!fileInput.files[0]) {
                showAlert('warning', 'Archivo no seleccionado', 'Por favor, selecciona una foto o video para compartir.');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Subiendo...';

            const formData = new FormData(mediaForm);

            try {
                await axios.post(getRoute('api.archivos.store'), formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });

                mediaForm.reset();
                mediaModal.hide();
                showAlert('success', '¡Media compartida!', 'Tu foto/video ha sido subido y será visible en breve.');
                loadMedia();
            } catch (error) {
                console.error("Error al subir media:", error.response || error);
                let errorMsg = error.response?.data?.message || 'Ocurrió un error al subir el archivo.';
                
                showAlert('error', 'Error al subir', errorMsg);
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-share-alt"></i> Compartir';
            }
        });

        // La carga inicial se moverá para ejecutarse en paralelo.
    }

    /**
     * Función de inicialización principal que carga todos los datos
     * de la página en paralelo para mejorar el rendimiento.
     */
    const initPage = () => {
        const promises = [];

        if (commentsList) promises.push(loadAndRender('api.comentarios.index', commentsList, renderComments, 'comentarios'));
        // La carga de sugerencias ahora es manejada por concientizacion.js
        // if (approvedSuggestionsList) promises.push(loadAndRender('api.sugerencias.index', approvedSuggestionsList, renderSuggestions, 'sugerencias'));
        if (reportsList) promises.push(loadAndRender('api.reportes.index', reportsList, renderReports, 'reportes'));
        if (videosList || imagesList) promises.push(loadMedia());

        Promise.all(promises).catch(error => console.error("Ocurrió un error durante la carga inicial de la página:", error));
    };

    initPage();
});