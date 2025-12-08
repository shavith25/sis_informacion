@extends('layouts.app')

@section('title', 'Panel de Ayuda - Sistema SIG')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/ayuda.css') }}">
@endpush

@section('content')
    <div class="container-fluid p-0">

        <header class="sig-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="system-title display-3 mb-3">
                            <i class="bi bi-geo-alt-fill me-3"></i> Sistema de Información Geográfica (SIG)
                        </h1>
                        <p class="system-subtitle lead mb-0">
                            Plataforma integral para el análisis, gestión y visualización de datos geoespaciales.
                        </p>
                    </div>
                </div>
            </div>
        </header>

        <br>

        <div class="container main-container mt-n5">
            <div class="row align-items-center module-header">
                <div class="col-md-8">
                    <h2 class="display-4 fw-bold gradient-text mb-3">
                        <i class="bi bi-life-preserver me-3"></i> Panel de Ayuda SIG
                    </h2>
                    <p class="lead text-muted mb-0 fs-4">
                        Documentación técnica, manual de usuario y guías especializadas.
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="module-icon ms-auto">
                        <i class="bi bi-file-earmark-pdf-fill display-4 text-white"></i>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 upload-section">
                    <div class="mb-4">
                        <h3 class="text-sig-primary fw-bold mb-3 display-6">
                            <i class="bi bi-cloud-upload-fill me-2"></i> Cargar Documentos
                        </h3>
                        <p class="text-muted mb-2 fs-6">
                            Suba manuales y documentación técnica (PDF).
                        </p>
                    </div>

                    <form id="uploadForm" action="{{ route('ayuda.subir') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="fileInput" class="upload-area" id="uploadArea">
                            <div class="upload-content-wrapper">
                                <div class="upload-icon">
                                    <i class="bi bi-cloud-arrow-up-fill"></i>
                                </div>
                                <h4 class="text-sig-primary mb-1 fw-bold">Arrastre el archivo PDF aquí</h4>
                                <p class="text-muted mb-5 small">O haga clic para seleccionar</p>

                                <span class="btn btn-sig-primary mb-3">
                                    <i class="bi bi-folder2-open me-2"></i> Seleccionar Archivo
                                </span>

                                <p class="mt-1 x-small text-muted fst-italic mb-0" style="font-size: 1.2rem;">
                                    <i class="bi bi-info-circle me-2"></i> Solo PDF. Máx: 20MB.
                                </p>
                            </div>
                        </label>

                        <input type="file" name="pdf_file" id="fileInput" class="d-none" accept=".pdf" required>
                    </form>

                    <div id="fileInfo" class="document-info d-none mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="grow text-truncate me-2">
                                <h5 class="text-sig-primary mb-1 text-truncate">
                                    <i class="bi bi-file-earmark-pdf-fill me-2"></i>
                                    <span id="fileName">document.pdf</span>
                                </h5>
                                <div class="d-flex gap-2">
                                    <span class="badge bg-primary" id="fileSize">0 MB</span>
                                    <span class="badge bg-warning text-dark" id="fileStatus">Listo</span>
                                </div>
                            </div>
                            
                            <button type="button" class="btn-close" aria-label="Close" onclick="clearFileSelection()"></button>
                        </div>

                        <div class="progress-sig mb-3">
                            <div id="uploadProgress" class="progress-bar-sig" style="width: 0%;"></div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sig-primary flex-fill" onclick="startUpload()">
                                <i class="bi bi-cloud-check me-2"></i> Subir
                            </button>
                            <button class="btn btn-outline-secondary" onclick="clearFileSelection()">
                                <i class="bi bi-x-circle me-2"></i> Cancelar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="mb-4">
                        <h3 class="text-sig-primary fw-bold mb-3 display-6">
                            <i class="bi bi-eye-fill me-2"></i> Documentos Disponibles
                        </h3>
                        <p class="text-muted fs-6 mb-0">
                            Visualice o descargue la documentación existente.
                        </p>
                    </div>

                    <section class="section" style="overflow: auto; max-height: 600px;">
                        @if ($documentos->isNotEmpty())
                            <div id="documentList">
                                @foreach ($documentos as $documento)
                                    <div class="document-item card mb-2">
                                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                                            <div class="me-2 text-truncate">
                                                <h6 class="card-title mb-0 text-truncate" title="{{ $documento->nombre_original }}">
                                                    <i class="bi bi-file-earmark-pdf-fill text-danger me-2"></i>
                                                    {{ $documento->nombre_original }}
                                                </h6>
                                                <small class="text-muted" style="font-size: 0.75rem;">{{ $documento->created_at->format('d/m/Y') }}</small>
                                            </div>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" onclick="viewDocument('{{ asset('storage/' . $documento->ruta_archivo) }}', '{{ $documento->nombre_original }}')"><i class="bi bi-eye"></i> Ver</button>
                                                <a href="{{ asset('storage/' . $documento->ruta_archivo) }}" class="btn btn-outline-success" download="{{ $documento->nombre_original }}"><i class="bi bi-download"></i> Descargar</a>
                                                <button class="btn btn-outline-danger delete-doc-btn" onclick="confirmDelete({{ $documento->id }}, '{{ $documento->nombre_original }}')" data-doc-id="{{ $documento->id }}"><i class="bi bi-trash"></i> Eliminar</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 border rounded bg-light" id="emptyState">
                                <i class="bi bi-folder-x" style="font-size: 3rem; color: #ccc;"></i>
                                <h6 class="mt-2 text-muted">No hay documentos.</h6>
                            </div>
                        @endif
                    </section>
                </div>
            </div>

            <div class="mt-5 pt-4 border-top">
                <h4 class="text-center mb-4 text-muted">¿Cómo funciona el sistema SIG?</h4>
                <div class="row g-3">
                    <div class="col-md-3 col-sm-6">
                        <div class="p-3 bg-light rounded text-center h-100 border">
                            <span class="step-number mb-2">1</span>
                            <h6 class="fw-bold">Captura</h6>
                            <p class="small text-muted mb-0">Recopilación de datos geográficos.</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="p-3 bg-light rounded text-center h-100 border">
                            <span class="step-number mb-2">2</span>
                            <h6 class="fw-bold">Gestión</h6>
                            <p class="small text-muted mb-0">Almacenamiento en bases de datos.</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="p-3 bg-light rounded text-center h-100 border">
                            <span class="step-number mb-2">3</span>
                            <h6 class="fw-bold">Análisis</h6>
                            <p class="small text-muted mb-0">Cálculos espaciales y modelos.</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="p-3 bg-light rounded text-center h-100 border">
                            <span class="step-number mb-2">4</span>
                            <h6 class="fw-bold">Visualización</h6>
                            <p class="small text-muted mb-0">Mapas temáticos y gráficos.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="pdfModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered" style="height: 80vh;">
                <div class="modal-content h-100">
                    <div class="modal-header py-2 bg-light">
                        <h6 class="modal-title m-0" id="pdfModalLabel">
                            <i class="bi bi-filetype-pdf text-danger me-2"></i> Visualizar
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> Cerrar</button>
                    </div>
                    <div class="modal-body p-0 h-100">
                        <iframe id="pdfViewer" src="" width="100%" height="100%" style="border:none;"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            const pdfModalEl = document.getElementById('pdfModal');
            const pdfModal = new bootstrap.Modal(pdfModalEl);
            const pdfViewer = document.getElementById('pdfViewer');
            
            let fileURL = null;

            const showAlert = (title, text, icon = 'info') => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ title, text, icon, confirmButtonText: 'Aceptar', confirmButtonColor: '#1e83e9' });
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
                fileStatusSpan.textContent = 'Listo';
                uploadProgress.style.width = '0%';
                uploadProgress.classList.remove('bg-success');
                uploadArea.classList.add('d-none');
                fileInfo.classList.remove('d-none');
            };

            // --- LÓGICA DE LA X (CANCELAR SELECCIÓN) ---
            window.clearFileSelection = () => {
                fileInput.value = ''; 
                
                uploadArea.classList.remove('d-none');
                fileInfo.classList.add('d-none');

                uploadProgress.style.width = '0%';
                uploadProgress.classList.remove('bg-success');
                fileStatusSpan.textContent = 'Listo';

                if (fileURL) {
                    URL.revokeObjectURL(fileURL);
                    fileURL = null;
                }
            };

            // --- LÓGICA DEL VISOR PDF ---
            window.viewDocument = (url, title) => {
                document.getElementById('pdfModalLabel').textContent = title;
                pdfViewer.src = url;
                pdfModal.show();
            };

            pdfModalEl.addEventListener('hidden.bs.modal', function (event) {
                pdfViewer.src = "";
            });

            // --- LÓGICA DE ELIMINACIÓN ---
            window.confirmDelete = (docId, docTitle) => {
                Swal.fire({
                    title: '¿Eliminar archivo?',
                    text: `Se eliminará "${docTitle}" permanentemente.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`{{ url('/documento') }}/${docId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            Swal.fire('Eliminado', data.message, 'success').then(() => location.reload());
                        })
                        .catch(err => showAlert('Error', 'No se pudo eliminar', 'error'));
                    }
                });
            };

            // Event Listeners para Drag & Drop e Input
            fileInput.addEventListener('change', (e) => {
                if (fileInput.files.length > 0) showFileInfo(fileInput.files[0]);
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

            // Subida AJAX
            window.startUpload = () => {
                if (!fileInput.files.length) return;
                fileStatusSpan.textContent = 'Subiendo...';
                const formData = new FormData(document.getElementById('uploadForm'));
                const xhr = new XMLHttpRequest();
                xhr.open('POST', "{{ route('ayuda.subir') }}");
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
                
                xhr.upload.addEventListener('progress', (e) => {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        uploadProgress.style.width = `${percent}%`;
                    }
                });

                xhr.onload = () => {
                    if (xhr.status === 201) {
                        const data = JSON.parse(xhr.responseText);
                        Swal.fire('¡Éxito!', data.message, 'success').then(() => window.location.reload());
                    } else {
                        showAlert('Error', 'Error al subir el archivo', 'error');
                        fileStatusSpan.textContent = 'Error';
                        uploadProgress.style.width = '0%';
                    }
                };
                xhr.onerror = () => showAlert('Error', 'Error de red', 'error');
                xhr.send(formData);
            };
        });
    </script>
@endpush