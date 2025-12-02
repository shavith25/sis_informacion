@extends('layouts.app')

@section('title', 'Editar Departamento')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Editar Departamento</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('limites.departamentos.index') }}">Inicio</a></div>
            <div class="breadcrumb-item"><a href="#">Límites</a></div>
            <div class="breadcrumb-item"><a href="{{ route('limites.departamentos.index') }}">Departamentos</a></div>
            <div class="breadcrumb-item">Editar</div>
        </div>
    </div>

    <div class="section-body">
        
        @if(session('success'))
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
                {{ session('success') }}
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-edit text-primary"></i> Formulario de Edición</h4>
            </div>
            
            <div class="card-body">
                <form action="{{ route('limites.departamentos.update', $departamento->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre" class="font-weight-bold">
                                    <i class="fas fa-map-marker-alt text-danger"></i> Nombre <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nombre" id="nombre" class="form-control" 
                                    value="{{ old('nombre', $departamento->nombre) }}" placeholder="Ej: Cochabamba" required>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="descripcion" class="font-weight-bold">
                                    <i class="fas fa-info-circle"></i> Descripción
                                </label>
                                <textarea name="descripcion" id="descripcion" class="form-control" style="height: 42px;" placeholder="Descripción del departamento...">{{ old('descripcion', $departamento->descripcion) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label for="geometria" class="font-weight-bold">
                            <i class="fas fa-draw-polygon"></i> Geometría (GeoJSON) <span class="text-danger">*</span>
                        </label>
                        <input type="hidden" name="geometria" id="geometria">
                        
                        <div id="jsoneditor" style="height: 300px; border: 1px solid #ddd;"></div>
                        
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Modifica la geometría en formato GeoJSON válido. Asegúrate de mantener la estructura correcta.
                        </small>
                    </div>

                    <div class="form-group mt-4">
                        <label for="media" class="font-weight-bold"><i class="fas fa-images"></i> Subir Medios (Imágenes/Videos)</label>
                        <div class="custom-file">
                            <input type="file" name="media[]" id="media" class="form-control" multiple accept="image/*,video/*">
                        </div>
                        <small class="text-muted">Puedes subir múltiples imágenes o videos nuevos para agregar a la galería existente.</small>
                    </div>

                    @if($departamento->media->count())

                    <hr>

                    <div class="form-group">
                        <label class="font-weight-bold mb-3">Administrar Medios Existentes</label>
                        <div class="row">
                            @foreach($departamento->media as $media)
                                <div class="col-md-2 col-sm-4 mb-3">
                                    <div class="card h-100 shadow-sm border">
                                        <div class="card-body p-0 bg-light" style="height: 120px; overflow: hidden; position: relative;">
                                            @if(str_contains($media->tipo, 'video'))
                                                <div class="d-flex align-items-center justify-content-center h-100">
                                                    <i class="fas fa-video fa-3x text-secondary"></i>
                                                </div>
                                            @else
                                                <img src="{{ asset('storage/' . $media->archivo) }}" alt="Media" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                            @endif
                                        </div>
                                        <div class="card-footer p-2 bg-white text-center">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="del_media_{{ $media->id }}" name="delete_media[]" value="{{ $media->id }}">
                                                <label class="custom-control-label text-danger small" for="del_media_{{ $media->id }}">Eliminar</label>
                                            </div>
                                            @if(str_contains($media->tipo, 'video'))
                                                <a href="{{ asset('storage/' . $media->archivo) }}" target="_blank" class="btn btn-sm btn-link p-0 mt-1">Ver video</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <hr>

                    <div class="form-group mt-5 mb-5 d-flex">
                        <a href="{{ route('limites.departamentos.index') }}" class="btn btn-secondary btn-lg mr-2">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg" id="btn-actualizar-departamento">
                            <i class="fas fa-save"></i> Actualizar Departamento
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

<link href="https://cdn.jsdelivr.net/npm/jsoneditor@latest/dist/jsoneditor.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/jsoneditor@latest/dist/jsoneditor.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const container = document.getElementById("jsoneditor");
        const hiddenInput = document.getElementById("geometria");

        // Datos iniciales desde el controlador
        const initialData = @json($departamento->geometria);

        // Configuración del editor para que se vea limpio
        const options = {
            mode: 'code',
            modes: ['code', 'tree'], 
            ace: ace 
        };

        const editor = new JSONEditor(container, options);
        editor.set(initialData);

        const form = hiddenInput.closest("form");

        form.addEventListener("submit", () => {
            try {
                const data = editor.get();
                hiddenInput.value = JSON.stringify(data);
            } catch (e) {
                alert('El JSON de geometría no es válido. Por favor revísalo.');
                event.preventDefault();
            }
        });
    });
</script>

<style>
    /* 1. Bloquear el scroll de la ventana principal del navegador */
    body {
        overflow: hidden;
    }

    /* 2. Ajustes visuales del encabezado (como tenías antes) */
    .section-header h1 {
        color: #34395e;
        font-size: 24px;
        font-weight: 700;
    }

    /* 3. Configuración de la Tarjeta para que ocupe el espacio vertical disponible */
    .card {
        /* Calculamos el 100% de la altura de la pantalla (100vh)
           y le restamos espacio para: Header, Título y el Footer (~230px es un margen seguro).
        */
        height: calc(100vh - 230px) !important; 
        display: flex;
        flex-direction: column;
        margin-bottom: 0 !important;
    }

    /* 4. Habilitar el scroll SOLO dentro del cuerpo del formulario */
    .card-body {
        flex: 1; /* Ocupa todo el espacio restante dentro de la tarjeta */
        overflow-y: auto; /* Activa el scroll vertical aquí */
        overflow-x: hidden;
        padding-right: 10px; /* Un pequeño espacio para que la barra no pegue con el contenido */
    }

    /* (Opcional) Estilizar la barra de scroll para que se vea moderna y combine con tu azul */
    .card-body::-webkit-scrollbar {
        width: 8px;
    }
    .card-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    .card-body::-webkit-scrollbar-thumb {
        background: #cdd3d8;
        border-radius: 4px;
    }
    .card-body::-webkit-scrollbar-thumb:hover {
        background: #6777ef; /* Azul al pasar el mouse */
    }

    /* --- TUS ESTILOS DE BOTONES Y JSON EDITOR (SE MANTIENEN IGUAL) --- */

    /* Botón Actualizar: Azul Fijo */
    #btn-actualizar-departamento {
        background-color: #0d6efd !important; 
        border-color: #0d6efd !important;
        color: white !important;
        box-shadow: none !important;
        background-image: none !important;
    }

    #btn-actualizar-departamento:hover, 
    #btn-actualizar-departamento:focus, 
    #btn-actualizar-departamento:active {
        background-color: #0b5ed7 !important; 
        border-color: #0a58ca !important;
        color: white !important;
        opacity: 1 !important;
    }

    .form-control:focus {
        border-color: #6777ef;
        box-shadow: none;
    }

    div.jsoneditor {
        border-color: #e4e6fc;
        border-radius: 5px;
    }

    div.jsoneditor-menu {
        background-color: #6777ef; 
        border-bottom: 1px solid #6777ef;
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
    }
    
    /* Estilos para preview de media (que ya tenías) */
    #media-preview img, #media-preview video {
        width: 100%; height: 150px; object-fit: cover; border-radius: 8px;
    }
    .media-item { position: relative; }
    .remove-media {
        position: absolute; top: 5px; right: 20px;
        background: rgba(255, 0, 0, 0.8); color: white;
        border: none; border-radius: 50%; width: 25px; height: 25px;
        cursor: pointer; z-index: 10;
    }
    .remove-media:hover { background: rgba(255, 0, 0, 1); }
</style>
@endsection