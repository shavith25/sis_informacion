@extends('layouts.app')

@section('title', 'Editar Especie')

@section('content')
<section class="section">
    <div class="section-header">
        <h1 style="color: #6c757d; font-weight: 700;">
            <i class="fas fa-edit mr-2"></i> Editar Especie: {{ strip_tags($especie->titulo) }}
        </h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Inicio</a></div>
            <div class="breadcrumb-item"><a href="#">Especies</a></div>
            <div class="breadcrumb-item">Editar</div>
        </div>
    </div>

    <div class="section-body">
        
        @if (session('success'))
            <div class="alert alert-success alert-dismissible show fade m-3">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible show fade m-3">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                    <strong>Error:</strong> Por favor revisa los campos.
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="card card-fixed-height">
            <div class="card-header">
                <h4><i class="fas fa-pen text-primary"></i> Formulario de Edición</h4>
            </div>
            
            <div class="card-body custom-scroll-area p-4">
                <form action="{{ route('especies.update', $especie->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-3 align-items-end">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="titulo" class="font-weight-bold text-muted small text-uppercase">Título</label>
                                <input id="titulo" type="hidden" name="titulo" value="{{ old('titulo', $especie->titulo) }}">
                                <trix-editor id="trix-titulo-editor" input="titulo" class="form-control" style="min-height: 50px;"></trix-editor>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="zona_id" class="font-weight-bold text-muted small text-uppercase">Zona</label>
                                <select name="zona_id" id="zona_id" class="form-control select2" required>
                                    <option value="">-- Selecciona una zona --</option>
                                    @foreach ($zonas as $zona)
                                        <option value="{{ $zona->id }}" {{ old('zona_id', $especie->zona_id) == $zona->id ? 'selected' : '' }}>
                                            {{ $zona->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tipo" class="font-weight-bold text-muted small text-uppercase">Tipo de Especie</label>
                                <select name="tipo" id="tipo" class="form-control select2" required>
                                    <option value="">-- Selecciona un tipo --</option>
                                    <option value="emblematica" {{ old('tipo', $especie->tipo) == 'emblematica' ? 'selected' : '' }}>Emblemática</option>
                                    <option value="vulnerable" {{ old('tipo', $especie->tipo) == 'vulnerable' ? 'selected' : '' }}>Vulnerable</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Descripción --}}
                    <div class="form-group mb-4">
                        <label for="descripcion" class="font-weight-bold text-muted small text-uppercase">
                            <i class="fas fa-info-circle me-1"></i> Descripción
                        </label>
                        <textarea name="descripcion" id="descripcion" class="form-control" style="height: 120px;" required>{{ old('descripcion', $especie->descripcion) }}</textarea>
                    </div>

                    {{-- Imágenes Actuales --}}
                    @if ($especie->imagenes->count())
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted small text-uppercase">Imágenes Actuales</label>
                            <div class="row g-2" id="imagenes-actuales">
                                @foreach ($especie->imagenes as $imagen)
                                    <div class="col-auto position-relative imagen-container">
                                        <div class="preview-item">
                                            <img src="{{ asset('storage/' . $imagen->url) }}" class="preview-img">
                                            <button type="button" class="remove-preview eliminar-imagen" data-id="{{ $imagen->id }}">✕</button>
                                        </div>
                                        <input type="hidden" name="eliminar_imagenes[]" value="" class="input-eliminar">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        {{-- Nuevas Imágenes --}}
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="imagenes" class="font-weight-bold text-muted small text-uppercase"><i class="fas fa-image me-1"></i> Nuevas Imágenes</label>
                                <div class="custom-file">
                                    <input type="file" name="imagenes[]" id="imagenes" class="form-control" multiple accept="image/*">
                                </div>
                                <div id="preview-nuevas-imagenes" class="d-flex flex-wrap gap-2 mt-2"></div>
                            </div>
                        </div>

                        {{-- Documentos --}}
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="documentos" class="font-weight-bold text-muted small text-uppercase"><i class="fas fa-file me-1"></i> Nuevos Documentos</label>
                                <div class="custom-file">
                                    <input type="file" name="documentos[]" id="documentos" class="form-control" multiple accept=".pdf,.doc,.docx">
                                </div>
                                <div id="preview-nuevos-documentos" class="d-flex flex-wrap gap-2 mt-2"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Documentos Actuales --}}
                    @if ($especie->media->count())
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted small text-uppercase">Documentos Actuales</label>
                            <div class="list-group">
                                @foreach ($especie->media as $doc)
                                    @php $ext = pathinfo($doc->archivo, PATHINFO_EXTENSION); @endphp
                                    <div class="list-group-item d-flex justify-content-between align-items-center p-2">
                                        <div>
                                            <i class="fas {{ $ext === 'pdf' ? 'fa-file-pdf text-danger' : 'fa-file-word text-primary' }} mr-2"></i>
                                            {{ basename($doc->archivo) }}
                                        </div>
                                        <div>
                                            <a href="{{ asset('storage/' . $doc->archivo) }}" class="btn btn-sm btn-outline-info mr-1" download title="Descargar"><i class="fas fa-download"></i></a>
                                            <button type="button" class="btn btn-sm btn-outline-danger eliminar-doc" data-id="{{ $doc->id }}" title="Eliminar"><i class="fas fa-trash"></i></button>
                                            <input type="hidden" name="eliminar_documentos[]" value="">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Botones de Acción --}}
                    <div class="form-group mt-4 d-flex mb-5">
                        <a href="{{ route('especies.index') }}" class="btn btn-secondary btn-lg mr-2">
                            <i class="fas fa-arrow-left mr-1"></i> Volver
                        </a>
                        
                        <button type="submit" class="btn btn-primary btn-lg btn-action-custom">
                            <i class="fas fa-sync-alt mr-1"></i> Actualizar Especie
                        </button>
                    </div>
                    
                    <div style="height: 50px;"></div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('css')
    <link rel="stylesheet" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    <style>
        /* 1. Estilos de Texto del Header */
        .section-header h1 {
            color: #6c757d !important;
            font-size: 24px;
            font-weight: 400;
            text-shadow: none;
        }
        
        /* 2. Botón de Acción Azul Sólido */
        .btn-action-custom {
            background-color: #2f55d4 !important;
            border-color: #2f55d4 !important;
            color: #ffffff !important;
            border-radius: 50px;
            padding: 0.55rem 1.5rem;
            font-weight: 600;
            box-shadow: none !important;
            transition: opacity 0.3s ease;
        }
        .btn-action-custom:hover,
        .btn-action-custom:focus,
        .btn-action-custom:active {
            background-color: #2f55d4 !important;
            border-color: #2f55d4 !important;
            color: #ffffff !important;
            box-shadow: none !important;
            opacity: 0.9;
            transform: none !important;
        }

        /* 3. Card con Scroll interno */
        .card-fixed-height {
            height: calc(100vh - 100px);
            display: flex;
            flex-direction: column;
            margin-bottom: 0;
        }
        .custom-scroll-area {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .custom-scroll-area::-webkit-scrollbar { width: 8px; }
        .custom-scroll-area::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
        .custom-scroll-area::-webkit-scrollbar-thumb { background: #cdd3d8; border-radius: 4px; }
        .custom-scroll-area::-webkit-scrollbar-thumb:hover { background: #6777ef; }

        /* 4. Estilos Trix */
        trix-editor { min-height: 100px; background-color: #fff; border: 1px solid #dee2e6; border-radius: 5px; }
        trix-toolbar { background-color: #f9f9f9; border-bottom: 1px solid #dee2e6; margin-bottom: 0; border-radius: 5px 5px 0 0; }
        .trix-button--icon-attach { display: none; }

        /* Previews */
        .preview-item {
            position: relative;
            width: 100px;
            height: 100px;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .preview-item img { width: 100%; height: 100%; object-fit: cover; }
        .remove-preview {
            position: absolute; top: 2px; right: 2px;
            width: 20px; height: 20px;
            background: #dc3545; color: white;
            border: none; border-radius: 50%;
            font-size: 12px; line-height: 1;
            cursor: pointer;
            z-index: 10;
        }

        /* Inputs */
        .form-control:focus, .form-select:focus { border-color: #6777ef; box-shadow: none; }
    </style>
@endpush

@push('js')
    <script src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Previsualización de nuevos archivos
            function setupFilePreview(inputId, previewContainerId, isImage) {
                const input = document.getElementById(inputId);
                const previewContainer = document.getElementById(previewContainerId);
                if(!input) return;

                input.addEventListener('change', function() {
                    previewContainer.innerHTML = '';
                    Array.from(this.files).forEach((file) => {
                        const reader = new FileReader();
                        const item = document.createElement('div');
                        item.className = 'preview-item';

                        reader.onload = function(e) {
                            if (isImage) {
                                item.innerHTML = `<img src="${e.target.result}">`;
                            } else {
                                const icon = file.name.endsWith('.pdf') ? 'fa-file-pdf text-danger' : 'fa-file-word text-primary';
                                item.innerHTML = `<div class="text-center p-2"><i class="fas ${icon} fa-2x"></i><br><small style="font-size:9px">${file.name.substring(0,10)}...</small></div>`;
                            }
                            const btn = document.createElement('button');
                            btn.className = 'remove-preview';
                            btn.innerHTML = '×';
                            btn.type = 'button';
                            btn.onclick = function() { item.remove(); }; 
                            item.appendChild(btn);
                            previewContainer.appendChild(item);
                        };
                        reader.readAsDataURL(file);
                    });
                });
            }

            setupFilePreview('imagenes', 'preview-nuevas-imagenes', true);
            setupFilePreview('documentos', 'preview-nuevos-documentos', false);

            // Lógica para eliminar imágenes existentes
            document.querySelectorAll('.eliminar-imagen').forEach(btn => {
                btn.addEventListener('click', function() {
                    const container = this.closest('.imagen-container');
                    const input = container.querySelector('.input-eliminar');
                    input.value = this.dataset.id;
                    container.style.opacity = '0.3';
                    this.style.display = 'none';
                });
            });

            // Lógica para eliminar documentos existentes
            document.querySelectorAll('.eliminar-doc').forEach(btn => {
                btn.addEventListener('click', function() {
                    const item = this.closest('.list-group-item');
                    const input = item.querySelector('input[name="eliminar_documentos[]"]');
                    input.value = this.dataset.id;
                    item.style.opacity = '0.5';
                    item.style.textDecoration = 'line-through';
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-check"></i>';
                    this.classList.replace('btn-outline-danger', 'btn-success');
                });
            });
        });
    </script>
@endpush