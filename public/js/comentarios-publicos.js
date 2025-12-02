document.addEventListener('DOMContentLoaded', () => {

    const commentsContainer = document.getElementById('comments-container');
    const commentsSpinner = document.getElementById('comments-spinner');
    const commentForm = document.getElementById('comment-form');

    /**
     * Renderiza una respuesta de administrador.
     * @param {Object} reply - Objeto de respuesta.
     */
    const renderReply = (reply) => {
        const date = new Date(reply.created_at).toLocaleDateString('es-ES');
        
        return `
            <div class="comment-reply-item mt-3">
                <div class="d-flex align-items-start">
                    <div class="flex">
                        <i class="bi bi-arrow-return-right text-primary me-1"></i>
                        <i class="bi bi-person-workspace fs-4 text-secondary"></i>
                    </div>
                    <div class="flex ms-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-1">
                                ${reply.nombre} 
                                <span class="badge bg-primary-light text-primary rounded-pill">Admin</span>
                            </h6>
                            <small class="text-muted">${date}</small>
                        </div>
                        <p class="mb-1">${reply.comentario}</p>
                    </div>
                </div>
            </div>
        `;
    };

    /**
     * Renderiza un comentario principal.
     * @param {Object} comment - Objeto de comentario.
     */
    const renderComment = (comment) => {
        const date = new Date(comment.created_at).toLocaleDateString('es-ES');

        const repliesHtml = comment.respuestas_aprobadas && comment.respuestas_aprobadas.length > 0
            ? `
            <div class="replies-container mt-3 ps-4 border-start">
                ${comment.respuestas_aprobadas.map(renderReply).join('')}
            </div>
            `
            : '';

        return `
            <div class="comment-item mb-4 pb-2 border-bottom">
                <div class="d-flex align-items-start">
                    <div class="flex">
                        <i class="bi bi-person-circle fs-3 text-secondary"></i>
                    </div>
                    <div class="flex ms-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-1">${comment.nombre}</h6>
                            <small class="text-muted">${date}</small>
                        </div>
                        <p class="mb-1">${comment.comentario}</p>
                    </div>
                </div>
                ${repliesHtml}
            </div>
        `;
    };

    /**
     * Carga y renderiza los comentarios aprobados de la API.
     */
    const loadComments = async () => {
        if (!commentsContainer) return;

        if (commentsSpinner) commentsSpinner.style.display = 'block';
        commentsContainer.innerHTML = '';

        try {
            const response = await fetch(window.routes['admin.comentarios.index']);
            
            if (!response.ok) {
                throw new Error(`Error de red: ${response.status} ${response.statusText}`);
            }

            const responseData = await response.json();

            // La API ahora devuelve directamente el array de comentarios.
            if (Array.isArray(responseData)) {
                const comments = responseData;
                if (comments.length > 0) {
                    commentsContainer.innerHTML = comments.map(renderComment).join('');
                } else {
                    commentsContainer.innerHTML = '<p class="text-center text-muted">Aún no hay comentarios. ¡Sé el primero en participar!</p>';
                }
            } else {
                throw new Error("El formato de los datos de la API no es válido.");
            }

        } catch (error) {
            console.error('Error al cargar los comentarios:', error);
            commentsContainer.innerHTML = '<p class="text-center text-danger">No se pudieron cargar los comentarios en este momento. Por favor, inténtalo de nuevo más tarde.</p>';
        } finally {
            if (commentsSpinner) commentsSpinner.style.display = 'none';
        }
    };

    // --- MANEJO DEL FORMULARIO ---

    if (commentForm) {
        commentForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Función auxiliar para limpiar y mostrar errores
            const displayError = (fieldId, message) => {
                const input = document.getElementById(fieldId);
                const errorElement = document.getElementById(`${fieldId}-error`);
                
                input.classList.remove('is-invalid');
                errorElement.textContent = '';

                if (message) {
                    input.classList.add('is-invalid');
                    errorElement.textContent = message;
                }
            };

            // Limpiar errores previos
            displayError('nombre', null);
            displayError('comentario', null);

            const formData = new FormData(commentForm);

            try {
                const response = await fetch(window.routes['api.comentarios.store'], {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        // Asegurarse de que el token CSRF esté presente
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (response.status === 422 && data.errors) {
                    displayError('nombre', data.errors.nombre ? data.errors.nombre[0] : null);
                    displayError('comentario', data.errors.comentario ? data.errors.comentario[0] : null);
                } else if (data.success === true) {
                    // Éxito
                    Swal.fire({
                        title: '¡Éxito!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        loadComments(); 
                    });
                    commentForm.reset();
                } else {
                    Swal.fire('Error', data.message || 'Ocurrió un error al procesar la solicitud.', 'error');
                }

            } catch (error) {
                console.error('Error en el envío del formulario:', error);
                Swal.fire('Error', 'No se pudo publicar el comentario. Inténtalo de nuevo.', 'error');
            }
        });
    }

    // Cargar comentarios al cargar la página
    loadComments();
});