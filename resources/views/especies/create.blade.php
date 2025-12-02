@extends('layouts.app')

@section('title', 'Crear Nueva Especie')

@section('content')
<section class="section">
    {{-- ENCABEZADO DE LA SECCIÓN --}}
    <div class="section-header">
        <h1 style="color: #6c757d; font-weight: 700;">
            <i class="fas fa-plus mr-2"></i> Crear Nueva Especie
        </h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Inicio</a></div>
            <div class="breadcrumb-item"><a href="#">Especies</a></div>
            <div class="breadcrumb-item">Crear</div>
        </div>
    </div>

    <div class="section-body">
        
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible show fade m-3">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                    <strong>Error:</strong> Por favor revisa los siguientes campos:
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="card card-fixed-height">
            <div class="card-header">
                <h4><i class="fas fa-leaf text-primary"></i> Formulario de Registro</h4>
            </div>
            
            <div class="card-body custom-scroll-area p-4">
                <form action="{{ route('especies.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Fila 1: Título, Zona, Tipo --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="titulo" class="font-weight-bold text-uppercase text-muted small">Título</label>
                                <input id="titulo" type="hidden" name="titulo" value="{{ old('titulo') }}">
                                <trix-editor id="trix-titulo-editor" input="titulo" class="form-control" style="min-height: 50px;"></trix-editor>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="zona_id" class="font-weight-bold text-uppercase text-muted small">Zona</label>
                                <select name="zona_id" id="zona_id" class="form-control select2" required>
                                    <option value="">-- Selecciona una zona --</option>
                                    @foreach ($zonas as $zona)
                                        <option value="{{ $zona->id }}" {{ old('zona_id') == $zona->id ? 'selected' : '' }}>
                                            {{ $zona->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tipo" class="font-weight-bold text-uppercase text-muted small">Tipo de Especie</label>
                                <select name="tipo" id="tipo" class="form-control select2" required>
                                    <option value="">-- Selecciona un tipo --</option>
                                    <option value="emblematica" {{ old('tipo') == 'emblematica' ? 'selected' : '' }}>Emblemática</option>
                                    <option value="vulnerable" {{ old('tipo') == 'vulnerable' ? 'selected' : '' }}>Vulnerable</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Descripción --}}
                    <div class="form-group mb-4">
                        <label for="descripcion" class="font-weight-bold text-uppercase text-muted small">
                            <i class="fas fa-info-circle me-1"></i> Descripción
                        </label>
                        <textarea name="descripcion" id="descripcion" class="form-control" style="min-height: 120px;" required>{{ old('descripcion') }}</textarea>
                    </div>

                    <div class="row">
                        {{-- Imágenes --}}
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="imagenes" class="font-weight-bold text-uppercase text-muted small">
                                    <i class="fas fa-image me-1"></i> Subir imágenes
                                </label>
                                <div class="custom-file">
                                    <input type="file" name="imagenes[]" id="imagenes" class="form-control" multiple accept="image/*">
                                </div>
                                <div id="preview-imagenes" class="d-flex flex-wrap gap-2 mt-2"></div>
                                <small class="text-muted">Puedes subir varias imágenes a la vez.</small>
                            </div>
                        </div>

                        {{-- Documentos --}}
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="documentos" class="font-weight-bold text-uppercase text-muted small">
                                    <i class="fas fa-file me-1"></i> Documentos (PDF/Word)
                                </label>
                                <div class="custom-file">
                                    <input type="file" name="documentos[]" id="documentos" class="form-control" multiple accept=".pdf,.doc,.docx">
                                </div>
                                <div id="preview-documentos" class="d-flex flex-wrap gap-2 mt-2"></div>
                                <small class="text-muted">Admite archivos PDF o Word.</small>
                            </div>
                        </div>
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="form-group mt-4 d-flex mb-5">
                        <a href="{{ route('especies.index') }}" class="btn btn-secondary btn-lg mr-2">
                            <i class="fas fa-arrow-left mr-1"></i> Volver
                        </a>
                        
                        <button type="submit" class="btn btn-primary btn-action-custom">
                            <i class="fas fa-save mr-1"></i> Guardar Especie
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
            font-weight: 700;
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

        /* 3. Card con Scroll interno (Layout Fijo) */
        .card-fixed-height {
            /* Ajusta el 200px según el alto de tu header/footer */
            height: calc(100vh - 200px);
            display: flex;
            flex-direction: column;
            margin-bottom: 0;
        }
        .custom-scroll-area {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }
        /* Scrollbar */
        .custom-scroll-area::-webkit-scrollbar { width: 8px; }
        .custom-scroll-area::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
        .custom-scroll-area::-webkit-scrollbar-thumb { background: #cdd3d8; border-radius: 4px; }
        .custom-scroll-area::-webkit-scrollbar-thumb:hover { background: #6777ef; }

        /* 4. Estilos Trix Editor */
        trix-editor {
            min-height: 150px;
            background-color: #fff;
            border: 1px solid #e4e6fc;
            border-radius: 5px;
        }
        trix-toolbar {
            background-color: #f9f9f9;
            border-bottom: 1px solid #e4e6fc;
            margin-bottom: 0;
            border-radius: 5px 5px 0 0;
        }
        
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
        }
        
        /* Inputs */
        .form-control:focus, .form-select:focus {
            border-color: #6777ef;
            box-shadow: none;
        }
    </style>
@endpush

@push('js')
    <script src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Función para previsualizar archivos
            function setupFilePreview(inputId, previewContainerId, isImage) {
                const input = document.getElementById(inputId);
                const previewContainer = document.getElementById(previewContainerId);

                input.addEventListener('change', function() {
                    previewContainer.innerHTML = '';
                    Array.from(this.files).forEach((file, index) => {
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
                            // Botón eliminar (visual solamente, para UX)
                            const btn = document.createElement('button');
                            btn.className = 'remove-preview';
                            btn.innerHTML = '×';
                            btn.type = 'button'; // Importante para no enviar form
                            btn.onclick = function() { item.remove(); }; 
                            item.appendChild(btn);
                            
                            previewContainer.appendChild(item);
                        };
                        reader.readAsDataURL(file);
                    });
                });
            }

            setupFilePreview('imagenes', 'preview-imagenes', true);
            setupFilePreview('documentos', 'preview-documentos', false);
        });
    </script>
@endpush