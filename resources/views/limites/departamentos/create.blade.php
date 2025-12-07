@extends('layouts.app')

@section('title', 'Crear Nuevo Departamento')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading"><i class="fas fa-plus-circle mr-2"></i> Crear Nuevo Departamento</h3>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Inicio</a></div>
            <div class="breadcrumb-item"><a href="{{ route('limites.index') }}">Límites</a></div>
            <div class="breadcrumb-item"><a href="{{ route('limites.departamentos.index') }}">Departamentos</a></div>
            <div class="breadcrumb-item">Crear</div>
        </div>
    </div>

    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <ul class="mb-0 pl-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-map-marker-alt"></i> Crear Nuevo Departamento</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('limites.departamentos.store') }}" method="POST" enctype="multipart/form-data" id="form-departamento">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="nombre" class="form-label">
                                <i class="fas fa-map-marker-alt"></i> Nombre <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                name="nombre" 
                                id="nombre" 
                                class="form-control @error('nombre') is-invalid @enderror" 
                                value="{{ old('nombre') }}" 
                                placeholder="Ej: Cochabamba" 
                                required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-9">
                            <label for="descripcion" class="form-label">
                                <i class="fas fa-info-circle"></i> Descripción
                            </label>
                            <textarea name="descripcion" 
                                    id="descripcion" 
                                    class="form-control @error('descripcion') is-invalid @enderror" 
                                    rows="3"
                                    placeholder="Descripción del departamento...">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="geometria" class="form-label">
                            <i class="fas fa-draw-polygon"></i> Geometría (GeoJSON) <span class="text-danger">*</span>
                        </label>
                        <input type="hidden" name="geometria" id="geometria" value="{{ old('geometria') }}">
                        <div id="jsoneditor" style="height: 300px; border: 1px solid #ddd; border-radius: 4px;"></div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> 
                            Ingresa la geometría en formato GeoJSON válido. Ejemplo: 
                            <code>{"type":"Polygon","coordinates":[[[lng,lat],[lng,lat],...]]}</code>
                        </small>
                        @error('geometria')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="media" class="form-label">
                            <i class="fas fa-image"></i> Subir Medios (Imágenes/Videos)
                        </label>
                        <input type="file" 
                            name="media[]" 
                            id="media" 
                            class="form-control @error('media.*') is-invalid @enderror" 
                            multiple
                            accept="image/*,video/*">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> 
                            Puedes subir múltiples imágenes o videos. Mantén presionada Ctrl (Cmd en Mac) para seleccionar varios archivos.
                        </small>
                        @error('media.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        <!-- Preview de archivos seleccionados -->
                        <div id="media-preview" class="row mt-3"></div>
                    </div>

                    {{-- BOTONES ALINEADOS HORIZONTALMENTE --}}
                    <div class="mt-8 mb-12">
                        <a href="{{ route('limites.departamentos.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>

                        <button type="submit" class="btn btn-primary" id="btn-guardar-departamento">
                            <i class="fas fa-save"></i> Guardar Departamento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('css')
<link href="https://cdn.jsdelivr.net/npm/jsoneditor@latest/dist/jsoneditor.min.css" rel="stylesheet">
<style>
    .jsoneditor-mode-button {
        display: none !important;
    }

    /* ESTILO PARA EL BOTÓN GUARDAR DEPARTAMENTO */
    #btn-guardar-departamento {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
        color: white !important;
        box-shadow: none !important;
        background-image: none !important;
    }

    /* Eliminar el fondo blanco al pasar el mouse (Hover) */
    #btn-guardar-departamento:hover, 
    #btn-guardar-departamento:focus, 
    #btn-guardar-departamento:active {
        background-color: #0b5ed7 !important; 
        border-color: #0a58ca !important;
        color: white !important;
        opacity: 1 !important;
    }
    
    #media-preview img,
    #media-preview video {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
    }
    
    .media-item {
        position: relative;
    }
    
    .remove-media {
        position: absolute;
        top: 5px;
        right: 20px;
        background: rgba(255, 0, 0, 0.8);
        color: white;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        cursor: pointer;
        z-index: 10;
    }
    
    .remove-media:hover {
        background: rgba(255, 0, 0, 1);
    }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/jsoneditor@latest/dist/jsoneditor.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // ===== JSONEditor Configuration =====
        const container = document.getElementById("jsoneditor");
        const hiddenInput = document.getElementById("geometria");

        // Datos iniciales o del old() de Laravel
        let initialData = {};
        try {
            const oldValue = hiddenInput.value;
            if (oldValue) {
                initialData = JSON.parse(oldValue);
            }
        } catch (e) {
            console.warn("Error parseando geometría antigua:", e);
        }

        // Configuración del editor
        const options = {
            mode: "code",
            modes: ["code", "tree"],
            onError: function (err) {
                console.error("Error en JSONEditor:", err);
            }
        };

        const editor = new JSONEditor(container, options);
        editor.set(initialData);

        // Guardar datos antes de submit
        const form = document.getElementById("form-departamento");
        form.addEventListener("submit", function(e) {
            try {
                const data = editor.get();
                hiddenInput.value = JSON.stringify(data);
                console.log("Geometría a enviar:", hiddenInput.value);
            } catch (error) {
                e.preventDefault();
                alert("Error: La geometría GeoJSON no es válida. Por favor revisa el formato.");
                console.error("Error al obtener JSON:", error);
            }
        });

        // ===== Media Preview =====
        const mediaInput = document.getElementById('media');
        const mediaPreview = document.getElementById('media-preview');

        mediaInput.addEventListener('change', function(e) {
            mediaPreview.innerHTML = ''; // Limpiar previews anteriores
            
            const files = Array.from(e.target.files);
            
            files.forEach((file, index) => {
                const reader = new FileReader();
                
                reader.onload = function(event) {
                    const col = document.createElement('div');
                    col.className = 'col-md-3 mb-3 media-item';
                    
                    let mediaElement;
                    if (file.type.startsWith('image/')) {
                        mediaElement = `<img src="${event.target.result}" class="img-thumbnail" alt="${file.name}">`;
                    } else if (file.type.startsWith('video/')) {
                        mediaElement = `<video src="${event.target.result}" class="img-thumbnail" controls></video>`;
                    }
                    
                    col.innerHTML = `
                        ${mediaElement}
                        <button type="button" class="remove-media" onclick="removeMediaPreview(this, ${index})">
                            <i class="fas fa-times"></i>
                        </button>
                        <p class="text-center mt-1 mb-0 small text-truncate">${file.name}</p>
                    `;
                    
                    mediaPreview.appendChild(col);
                };
                
                reader.readAsDataURL(file);
            });
        });
    });

    // Función para remover preview de media
    function removeMediaPreview(button, index) {
        const mediaInput = document.getElementById('media');
        const dt = new DataTransfer();
        const files = Array.from(mediaInput.files);
        
        files.forEach((file, i) => {
            if (i !== index) {
                dt.items.add(file);
            }
        });
        
        mediaInput.files = dt.files;
        button.closest('.media-item').remove();
    }
</script>
@endpush