@extends('layouts.app')

@section('title', 'Panel de Ayuda - Sistema SIG')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/ayuda.css') }}">
@endpush

@section('content')
    <div class="container-fluid">

        <header class="sig-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="system-title display-3 mb-3">
                            <i class="bi bi-geo-alt-fill me-3"></i> Sistema de Información Geográfica (SIG)
                        </h1>
                        <p class="system-subtitle lead mb-0">
                            Plataforma integral para el analisis, gestión y visualización de datos geoespaciales.
                        </p>
                    </div>
                </div>
            </div>
        </header>

        <br><br>

        <div class="container main-container">
            <div class="row align-items-center module-header">
                <div class="col-md-8">
                    <h2 class="display-4 fw-bold gradient-text mb-3">
                        <i class="bi bi-life-preserver me-3"></i> Panel de Ayuda SIG
                    </h2>
                    <p class="lead text-muted mb-0 fs-4">
                        Documentación técnica, manual de usuario y guías especializada del Sistema de Información
                        Geográfica.
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="module-icon">
                        <i class="bi bi-file-earmark-pdf-fill display-4 text-white"></i>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 upload-section">
                    <div class="mb-5">
                        <h3 class="text-sig-primary fw-bold mb-4 display-5">
                            <i class="bi bi-cloud-upload-fill me-3"></i> Cargar Documentos
                        </h3>
                        <p class="text-muted mb-4 fs-5">
                            Suba manuales, guías y documentación técnica en formato PDF.
                        </p>
                    </div>

                    <form id="uploadForm" action="{{ route('ayuda.subir') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="fileInput" class="upload-area mb-5" id="uploadArea">
                            <div class="upload-icon">
                                <i class="bi bi-cloud-arrow-up-fill"></i>
                            </div>
                            <h3 class="text-sig-primary mb-4">Arrastre el archivo PDF aquí</h3>
                            <p class="text-muted mb-4 fs-5">O haga clic para seleccionar</p>

                            <span class="btn btn-sig-pdf btn-lg mb-4">
                                <i class="bi bi-folder2-open me-3"></i> Seleccionar Archivo PDF
                            </span>

                            <p class="mt-4 small text-muted fs-6">
                                <i class="bi bi-info-circle me-2"></i> Solo se permiten archivos PDF. Tamaño máximo: 15MB.
                            </p>
                        </label>

                        <input type="file" name="pdf_file" id="fileInput" class="d-none" accept=".pdf" required>
                    </form>

                    <div id="fileInfo" class="document-info d-none">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="grow">
                                <h4 class="text-sig-primary mb-3">
                                    <i class="bi bi-file-earmark-pdf-fill me-3"></i>
                                    <span id="fileName">document.pdf</span>
                                </h4>
                                
                                <div class="d-flex gap-3 mb-4 flex-wrap">
                                    <span class="badge bg-primary fs-6 p-3" id="fileSize">0 MB</span>
                                    <span class="badge bg-success fs-6 p-3">PDF Válido</span>
                                    <span class="badge bg-warning fs-6 p-3" id="fileStatus">Listo</span>
                                </div>
                            </div>
                            <button type="button" class="btn-close fs-5" onclick="clearFileSelection()"></button>
                        </div>

                        <div class="progress-sig mb-4">
                            <div id="uploadProgress" class="progress-bar-sig" style="width: 0%;"></div>
                        </div>
                        <div class="d-flex gap-3 flex-wrap">
                            <button class="btn btn-sig-primary flex-fill p-3" onclick="startUpload()">
                                <i class="bi bi-cloud-check me-3"></i> Subir Documento
                            </button>
                            <button class="btn btn-outline-secondary p-3" onclick="clearFileSelection()">
                                <i class="bi bi-x-circle me-2"></i> Cancelar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="mb-5">
                        <h3 class="text-sig-primary fw-bold mb-4 display-5">
                            <i class="bi bi-eye-fill me-3"></i> Vista Previa del Documento
                        </h3>
                        
                        <p class="text-muted fs-5">
                            Visualice el contenido del documento PDF antes de descargarlo.
                        </p>
                    </div>

                    <section class="section" style="overflow: auto; margin-top: 100px; height: calc(100vh - 250px);">
                        @if ($documentos->isNotEmpty())
                            <div id="documentList">
                                @foreach ($documentos as $documento)
                                    <div class="document-item card mb-3">
                                        <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                                            <div class="me-3 mb-2 mb-md-0">
                                                <h5 class="card-title mb-1 fs-5">
                                                    <i class="bi bi-file-earmark-pdf-fill text-danger me-2"></i>
                                                    {{ $documento->nombre_original }}
                                                </h5>
                                                <small class="text-muted">Subido: {{ $documento->created_at->format('d/m/Y H:i') }}</small>
                                            </div>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <button class="btn btn-sm btn-outline-primary" onclick="viewDocument('{{ asset('storage/' . $documento->ruta_archivo) }}', '{{ $documento->nombre_original }}')"><i class="bi bi-eye"></i> Ver</button>
                                                <a href="{{ asset('storage/' . $documento->ruta_archivo) }}" class="btn btn-sm btn-outline-success" download="{{ $documento->nombre_original }}"><i class="bi bi-download"></i> Descargar</a>
                                                <button class="btn btn-sm btn-outline-danger delete-doc-btn" onclick="confirmDelete({{ $documento->id }}, '{{ $documento->nombre_original }}')" data-doc-id="{{ $documento->id }}"><i class="bi bi-trash"></i> Eliminar</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5" id="emptyState">
                                <i class="bi bi-file-text" style="font-size: 48px; color: #ccc;"></i>
                                <h5 class="mt-3 text-muted">No hay documentos de ayuda disponibles.</h5>
                                <p class="text-muted">Sube un archivos PDF o Word para verlos aqui.</p>
                            </div>
                        @endif
                    </section>
                </div>
            </div>

            <div class="mt-5">
                <h3 class="text-center mb-4"><i class="bi bi-question-circle"></i> ¿Cómo funciona el sistema SIG?</h3>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="bg-primary p-4 rounded text-dark">
                            <h5><span class="step-number">1: </span> Captura de datos.</h5>
                            <p>Los SIG recopilan Geográficos de diversas fuente como mapas en Google Earth, y bases de datos existentes.</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="bg-primary p-4 rounded text-dark">
                            <h5><span class="step-number">2: </span> Almacenamiento y gestión.</h5>
                            <p>Los datos se almacenan y organizan en bases de datos geográficas que permiten almacenar Información atributiva de manera eficiente.</p>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="bg-primary p-4 rounded text-dark">
                            <h5><span class="step-number">3: </span> Análisis espacial.</h5>
                            <p>Los SIG permiten realizar análisis espaciales complejos, como la superposición de capas, cálculos de rutas y modelos predictivos.</p>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="bg-primary p-4 rounded text-dark">
                            <h5><span class="step-number">4: </span> Visualización y presentación.</h5>
                            <p>Los resultados del análisis se pueden visualizar mediante mapas temáticos, gráficos y tablas, facilitando la interpretación de los datos.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-2">
                <h3 class="text-center mb-4">Caracteristicas Principales del SIG.</h3>
                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <div class="card feature-card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-layers"></i> Gestión de capas</h5>
                                <p class="card-text">Organización de información en capas temáticas superpuestas para análisis integrado.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card feature-card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-geo"></i> Geoprocesamiento</h5>
                                <p class="card-text">Herramientas para manipular, analizar y modelar datos espaciales súgun necesidades especificas.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card feature-card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-map"></i> Creación de mapas</h5>
                                <p class="card-text">Generación de mapas temáticos personalizados con elementos cartográficos profesionales.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para visualizar PDF -->
        <div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pdfModalLabel">
                            <i class="bi bi-filetype-pdf text-danger"></i> Visualizar Documento
                        </h5>
                    </div>
                    <div class="modal-body p-0" style="height: 80vh;">
                        <iframe id="pdfViewer" src="" width="100%" height="100%" style="border:none;"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('fileInput');
            const uploadArea = document.getElementById('uploadArea');
            if (!uploadArea) return; 

            const fileInfo = document.getElementById('fileInfo');
            const fileNameSpan = document.getElementById('fileName');
            const fileSizeSpan = document.getElementById('fileSize');
            const fileStatusSpan = document.getElementById('fileStatus');
            const uploadProgress = document.getElementById('uploadProgress');
            const pdfViewer = document.getElementById('pdfViewer');
            const pdfPlaceholder = document.getElementById('pdfPlaceholder');
            const pdfTitle = document.getElementById('pdfModalLabel'); 
            const downloadLink = document.getElementById('downloadLink');

            const pdfModal = new bootstrap.Modal(document.getElementById('pdfModal'));
            let fileURL = null;
            let uploadedDocId = null;

            const showAlert = (title, text, icon = 'info') => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: title,
                        text: text,
                        icon: icon,
                        confirmButtonText: 'Aceptar'
                    });
                } else {
                    alert(`${title}: ${text}`);
                }
            };

            const showFileInfo = (file) => {
                if (file.type !== 'application/pdf') {
                    showAlert('Formato Inválido', 'Solo se permiten archivos PDF.', 'error');
                    clearFileSelection();
                    return;
                }

                const sizeMB = (file.size / (1024 * 1024)).toFixed(2);

                fileNameSpan.textContent = file.name;
                fileSizeSpan.textContent = `${sizeMB} MB`;
                fileStatusSpan.textContent = 'Listo para subir';
                uploadProgress.style.width = '0%';
                uploadProgress.classList.remove('bg-success');

                uploadArea.classList.add('d-none');
                fileInfo.classList.remove('d-none');

                if (fileURL) {
                    URL.revokeObjectURL(fileURL);
                }
                fileURL = URL.createObjectURL(file);
                // No mostramos la vista previa hasta que se suba
            };

            window.clearFileSelection = () => {
                fileInput.value = '';
                uploadedDocId = null;

                uploadArea.classList.remove('d-none');
                fileInfo.classList.add('d-none');

                if (fileURL) {
                    URL.revokeObjectURL(fileURL);
                    fileURL = null;
                }
            };

            window.viewDocument = (url, title) => {
                const pdfViewerIframe = document.getElementById('pdfViewer');
                const pdfModalLabel = document.getElementById('pdfModalLabel');

                pdfModalLabel.textContent = title;
                pdfViewerIframe.src = url;
                pdfModal.show();
            };

            const deleteDocument = (docId, docTitle) => {
                const docElement = document.querySelector(`.document-item .delete-doc-btn[data-doc-id="${docId}"]`).closest('.document-item');

                fetch(`{{ url('/documento') }}/${docId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(error => {
                                throw new Error(error.message || 'Error desconocido')
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        showAlert('Eliminado', data.message || `Documento ${docTitle} eliminado.`, 'success');
                        if (docElement) {
                            docElement.remove();
                        }
                    })
                    .catch(error => {
                        showAlert('Error de Eliminación', error.message, 'error');
                        console.error('Error:', error);
                    });
            };

            window.confirmDelete = (docId, docTitle) => {
                if (typeof Swal === 'undefined') {
                    if (confirm(`¿Estás seguro de que deseas eliminar el documento "${docTitle}"?`)) {
                        deleteDocument(docId, docTitle);
                    }
                    return;
                }

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "El documento se eliminará permanentemente.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, Eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteDocument(docId, docTitle);
                    }
                });
            };

            fileInput.addEventListener('change', (e) => {
                if (fileInput.files.length > 0) {
                    showFileInfo(fileInput.files[0]);
                }
            });

            uploadArea.addEventListener('click', () => fileInput.click());

            ['dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    if (eventName === 'dragover') uploadArea.classList.add('dragover');
                    else uploadArea.classList.remove('dragover');
                });
            });

            uploadArea.addEventListener('drop', (e) => {
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    showFileInfo(files[0]);
                }
            });

            window.startUpload = () => {
                if (!fileInput || fileInput.files.length === 0) {
                    showAlert('Error de Subida', 'Por favor, selecciona un archivo PDF para subir.', 'warning');
                    return;
                }

                fileStatusSpan.textContent = 'Subiendo...';
                const formData = new FormData(document.getElementById('uploadForm'));
                const xhr = new XMLHttpRequest();

                xhr.open('POST', "{{ route('ayuda.subir') }}");
                xhr.setRequestHeader('X-CSRF-TOKEN', document.head.querySelector('meta[name="csrf-token"]').content);

                xhr.upload.addEventListener('progress', (e) => {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        uploadProgress.style.width = `${percent}%`;
                    }
                });

                xhr.onreadystatechange = () => {
                    if (xhr.readyState === 4) {
                        let data;
                        try {
                            data = JSON.parse(xhr.responseText);
                        } catch (e) {
                            showAlert('Error Crítico de Servidor', 'Respuesta inesperada del servidor.', 'error');
                            fileStatusSpan.textContent = 'Error de Servidor';
                            return;
                        }

                        if (xhr.status === 201) {
                            uploadProgress.classList.add('bg-success');
                            fileStatusSpan.textContent = '¡Subida Completa!';
                            showAlert('¡Éxito!', data.message, 'success');
                            window.location.reload();
                        } else {
                            uploadProgress.style.width = '0%';
                            fileStatusSpan.textContent = 'Error de Subida';
                            const errorMessage = data.errors ? Object.values(data.errors).flat().join('\n') : data.message;
                            showAlert('Fallo la Subida', errorMessage, 'error');
                        }
                    }
                };

                xhr.send(formData);
            };
        });
    </script>
@endpush
