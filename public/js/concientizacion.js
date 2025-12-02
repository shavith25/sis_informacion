document.addEventListener("DOMContentLoaded", () => {
    'use strict';

    const setupAxiosCsrf = () => {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (token) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
            axios.defaults.headers.common['Accept'] = 'application/json';
        } else {
            console.error("ADVERTENCIA: El token CSRF no se encontró. Las peticiones POST fallarán.");
        }
    };

    /**
     * Función genérica para realizar llamadas a la API usando las rutas de Laravel (Ziggy).
     * @param {string} routeName - Nombre de la ruta (e.g., 'api.comentarios.index').
     * @param {string} method - Método HTTP (e.g., 'get', 'post').
     * @param {Object} data - Datos a enviar.
     * @param {Object} config - Configuración adicional de Axios.
     * @returns {Promise<Object>} Respuesta de la API.
     */
    const apiCall = async (routeName, method = 'get', data = {}, config = {}) => {
        if (!window.routes || !window.routes[routeName]) {
            throw new Error(`La ruta '${routeName}' no está definida en window.routes.`);
        }
        const route = window.routes[routeName];

        return await axios({
            method: method.toLowerCase(),
            url: route,
            data: data,
            ...config
        });
    };

    /**
     * Muestra una notificación usando SweetAlert2.
     * @param {string} icon - 'success', 'error', 'warning'.
     * @param {string} title - Título del modal.
     * @param {string} text - Contenido del modal.
     */
    const showAlert = (icon, title, text) => {
        Swal.fire({
            icon,
            title,
            text,
            confirmButtonColor: icon === 'success' ? '#9471f3' : '#dc3545',
        });
    };

    // --- SECCIÓN DE COMENTARIOS ---

    const initCommentsSection = () => {
        const commentsList = document.getElementById("commentsList");
        const commentForm = document.getElementById("commentForm");
        if (!commentForm || !commentsList) return;

        /**
         * Renderiza recursivamente los comentarios.
         */
        const renderComments = (comments) => {
            if (!comments || comments.length === 0) return '';

            return comments.map(c => `
                <div class="comment" data-comment-id="${c.id}">
                    <div class="comment-header">
                        <strong><i class="fas fa-user"></i> ${c.nombre}</strong>
                        <span><i class="far fa-calendar"></i> ${new Date(c.created_at).toLocaleDateString('es-ES')}</span>
                    </div>
                    <p>${c.comentario}</p>
                    <div class="comment-actions">
                        <button class="btn btn-sm btn-outline-secondary reply-btn"><i class="fas fa-reply"></i> Responder</button>
                    </div>
                    <div class="reply-form" style="display:none; margin-left:20px; margin-top:10px;">
                        <input type="text" class="form-control form-control-sm mb-1 reply-name" placeholder="Tu nombre" required>
                        <textarea class="form-control form-control-sm mb-1 reply-text" placeholder="Tu respuesta" required></textarea>
                        <button class="btn btn-sm btn-success send-reply-btn">Enviar</button>
                    </div>
                    ${c.respuestas_aprobadas?.length ? `<div class="replies">${renderComments(c.respuestas_aprobadas)}</div>` : ''}
                </div>
            `).join('');
        };

        const loadComments = async () => {
            try {
                const response = await apiCall('api.comentarios.index');
                const comments = response.data || [];
                const renderedHtml = renderComments(comments);

                commentsList.innerHTML = renderedHtml || '<p class="text-center text-muted mt-4">Aún no hay comentarios. ¡Sé el primero en participar!</p>';
            } catch (error) {
                console.error("Error al cargar comentarios:", error);
                commentsList.innerHTML = "<p class='text-center text-danger mt-4'>Error al cargar comentarios. Por favor, intenta de nuevo más tarde.</p>";
            }
        };

        // Manejo del formulario de comentario principal
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
                const response = await apiCall('api.comentarios.store', 'post', data);
                commentForm.reset();
                showAlert('success', '¡Comentario Enviado!', response.data.message);
            } catch (error) {
                console.error("Error al publicar comentario:", error.response || error);
                const errorMessage = error.response?.data?.message || 'No se pudo publicar el comentario.';
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
                const nombre = form.querySelector('.reply-name').value.trim();
                const comentario = form.querySelector('.reply-text').value.trim();

                if (!nombre || !comentario) {
                    showAlert("warning", "Campos vacíos", "Por favor completa tu nombre y respuesta.");
                    return;
                }

                sendReplyBtn.disabled = true;

                try {
                    const response = await apiCall('api.comentarios.store', 'post', {
                        nombre,
                        comentario,
                        parent_id: commentId
                    });
                    form.style.display = 'none';
                    form.querySelector('.reply-name').value = '';
                    form.querySelector('.reply-text').value = '';
                    showAlert('success', 'Respuesta Enviada', response.data.message);
                } 
                catch (error) {
                    console.error("Error al enviar respuesta:", error.response || error);
                    const errorMessage = error.response?.data?.message || 'No se pudo enviar la respuesta.';
                    showAlert('error', 'Error', errorMessage);
                } finally {
                    sendReplyBtn.disabled = false;
                }
            }
        });

        loadComments();

    };

    // --- SECCIÓN DE SUGERENCIAS ---

    const initSuggestionsSection = () => {
        const suggestionsListContainer = document.getElementById("approvedSuggestionsList"); 
        const suggestionForm = document.getElementById("suggestionForm");
        const modalElement = document.getElementById('suggestionModal');

        const noSuggestionsAlert = document.getElementById("noApprovedSuggestionsAlert");

        if (!suggestionForm || !suggestionsListContainer || !modalElement) return;

        const suggestionModal = new bootstrap.Modal(modalElement);

        const renderSuggestions = (suggestions) => {
            if (!suggestions.length) {
                return "";
            }

            return suggestions.map(s => `
                <div class="col>
                    <div class="card h-100 shadow-sm suggestion-approved">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-check-circle me-1"></i> ${s.titulo}</h5>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title">${s.titulo}</h6>
                            <p class="card-text flex">${s.contenido}</p>
                        </div>
                        <div class="card-footer d-flex justify-content-between bg-light border-top">
                            <small><i class="fas fa-user"></i> ${s.nombre}</small>
                            <small><i class="far fa-calendar"></i> ${new Date(s.created_at).toLocaleDateString('es-ES')}</small>
                        </div>
                    </div>
                </div>
            `).join('');
        };

        const loadSuggestions = async () => {
            if (noSuggestionsAlert) noSuggestionsAlert.style.display = 'none';
            suggestionsListContainer.innerHTML = '<div class="col-12 text-center text-primary py-3"><i class="fas fa-spinner fa-spin me-2"></i> Cargando sugerencias...</div>';

            try {
                const response = await apiCall('api.sugerencias.index');
                const suggestions = response.data.data || [];

                suggestionsListContainer.innerHTML = renderSuggestions(suggestions) || "<div class='col-12'><p class='text-center text-muted'>Sé el primero en dejar una sugerencia.</p></div>";

                if (suggestionForm.length === 0 && noSuggestionsAlert) {
                    suggestionsListContainer.innerHTML = '';
                    noSuggestionsAlert.style.display = 'block';
                }
            } catch (error) {
                console.error("Error al cargar sugerencias:", error);
                suggestionsListContainer.innerHTML = `<div class='col-12'><p class='text-center text-danger'><i class="fas fa-exclamation-triangle me-2"></i>Hubo un error al cargar las sugerencias. Por favor, intenta de nuevo más tarde.</p></div>`;
            }
        };

        suggestionForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const submitBtn = e.target.querySelector('button[type="submit"]');
            
            const data = {
                nombre: document.getElementById("sugerencia_nombre").value.trim(),
                titulo: document.getElementById("sugerencia_tema").value.trim(),
                contenido: document.getElementById("sugerencia_contenido").value.trim(),
            };

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Publicando...';

            try {
                const response = await apiCall('api.sugerencias.store', 'post', data);
                suggestionForm.reset();
                suggestionModal.hide();
                showAlert('success', '¡Sugerencia enviada!', response.data.message);
                //loadSuggestions();
                
            } catch (error) {
                console.error("Error al enviar sugerencia:", error.response || error);
                showAlert('error', 'Error al enviar', error.response?.data?.message || 'Ocurrió un error.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Publicar Sugerencia';
            }
        });
        loadSuggestions();
    };

    // --- SECCIÓN DE REPORTES ---

    const initReportsSection = () => {
        const reportsList = document.getElementById("reportsList");
        const reportForm = document.getElementById("reportForm");
        const modalElement = document.getElementById('reportModal');
        if (!reportForm || !reportsList || !modalElement) return;

        const reportModal = new bootstrap.Modal(modalElement);

        const renderReports = (reports) => {
            if (!reports.length) {
                return "<p class='text-center text-muted'>¡La comunidad aún no ha reportado problemas!</p>";
            }
            return reports.map(r => `
                <div class="suggestion-card" style="border-left-color: #dc3545;">
                    <h4><i class="fas fa-exclamation-triangle"></i> ${r.titulo}</h4>
                    <p>${r.contenido}</p>
                    <div class="suggestion-meta">
                        <span><i class="fas fa-user"></i> Por: <strong>${r.nombre}</strong></span>
                        <small><i class="far fa-calendar"></i> ${new Date(r.created_at).toLocaleDateString('es-ES')}</small>
                    </div>
                </div>
            `).join('');
        };

        const loadReports = async () => {
            try {
                const res = await apiCall('api.reportes.index');
                reportsList.innerHTML = renderReports(res.data.data || []);
            } catch (error) {
                console.error("Error al cargar reportes:", error);
                reportsList.innerHTML = "<p class='text-center text-danger'>Hubo un error al cargar los reportes.</p>";
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
                const response = await apiCall('api.reportes.store', 'post', data);
                reportForm.reset();
                reportModal.hide();
                showAlert('success', '¡Reporte enviado!', response.data.message);
                loadReports();
            } catch (error) {
                console.error("Error al enviar reporte:", error.response || error);
                showAlert('error', 'Error al enviar', error.response?.data?.message || 'Ocurrió un error.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar Reporte';
            }
        });

        loadReports();
    };

    // --- SECCIÓN DE MEDIA (FOTOS Y VIDEOS) ---

    const initMediaSection = () => {
        const videosList = document.getElementById("videosList");
        const imagesList = document.getElementById("imagesList");
        const mediaForm = document.getElementById("mediaForm");
        const modalElement = document.getElementById('mediaModal');
        if (!mediaForm || (!videosList && !imagesList) || !modalElement) return;

        const mediaModal = new bootstrap.Modal(modalElement);

        const renderMedia = (items, type) => {
            if (!items.length) {
                return `<p class='text-center text-muted'>Aún no se han compartido ${type}.</p>`;
            }
            return items.map(item => {
                const isVideo = type === 'videos';
                const iconClass = isVideo ? 'fas fa-video' : 'fas fa-images';
                const fileTag = isVideo
                    ? `<video controls width="100%"><source src="/storage/${item.ruta_archivo}" type="${item.mime_type}"></video>`
                    : `<img src="/storage/${item.ruta_archivo}" alt="${item.titulo}" style="width:100%; height:auto; object-fit:cover;">`;

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

        const loadMedia = async () => {
            try {
                const res = await apiCall('api.archivos.index');
                const allMedia = res.data.data || [];
                const videos = allMedia.filter(item => item.mime_type.startsWith('video'));
                const images = allMedia.filter(item => item.mime_type.startsWith('image'));

                if (videosList) videosList.innerHTML = renderMedia(videos, 'videos');
                if (imagesList) imagesList.innerHTML = renderMedia(images, 'imágenes');
            } catch (error) {
                console.error("Error al cargar media:", error);
                if (videosList) videosList.innerHTML = "<p class='text-center text-danger'>Error al cargar los videos.</p>";
                if (imagesList) imagesList.innerHTML = "<p class='text-center text-danger'>Error al cargar las imágenes.</p>";
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
                const response = await apiCall('api.archivos.store', 'post', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });
                mediaForm.reset();
                mediaModal.hide();
                showAlert('success', '¡Archivo compartido!', response.data.message);
                loadMedia();
            } catch (error) {
                console.error("Error al subir media:", error.response || error);
                showAlert('error', 'Error al subir', error.response?.data?.message || 'Ocurrió un error.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-share-alt"></i> Compartir';
            }
        });

        loadMedia();
    };

    // --- INICIALIZACIÓN ---
    setupAxiosCsrf();
 
    loadSuggestions();
    loadReports();
    loadMedia();
});