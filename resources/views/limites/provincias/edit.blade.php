@extends('layouts.app')

@section('title', 'Editar Provincia')

@section('content')
<section class="section" style="height: calc(100vh - 120px); display: flex; flex-direction: column;">
    
    <div class="section-header" style="flex-shrink: 0;">
        <h1 style="color: #6c757d; font-weight: 700;">
            <i class="fas fa-edit mr-2"></i> Editar Provincia: {{ $provincia->nombre }}
        </h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Inicio</a></div>
            <div class="breadcrumb-item"><a href="#">Límites</a></div>
            <div class="breadcrumb-item"><a href="{{ route('limites.provincias.index') }}">Provincias</a></div>
            <div class="breadcrumb-item">Editar</div>
        </div>
    </div>

    <div class="section-body" style="flex-grow: 1; overflow: hidden; padding-bottom: 20px;">
        
        {{-- Alertas fijas --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible show fade mb-3">
            <div class="alert-body">
                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                {{ session('success') }}
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible show fade mb-3">
            <div class="alert-body">
                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        </div>
        @endif

        <div class="card h-100 d-flex flex-column">
            <div class="card-header" style="flex-shrink: 0;">
                <h4><i class="fas fa-pen text-primary"></i> Formulario de Edición</h4>
            </div>
            
            <div class="card-body" style="flex: 1; overflow-y: auto;">
                <form action="{{ route('limites.provincias.update', $provincia) }}" method="POST" enctype="multipart/form-data" id="form-provincia">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="departamento_id" class="font-weight-bold">
                                    <i class="fas fa-map text-primary"></i> Departamento <span class="text-danger">*</span>
                                </label>
                                <select name="id_departamento" id="departamento_id" class="form-control select2" required>
                                    <option value="">-- Seleccione el departamento --</option>
                                    @foreach($departamentos as $departamento)
                                        <option value="{{ $departamento->id }}" 
                                            {{ (old('id_departamento', $provincia->id_departamento) == $departamento->id) ? 'selected' : '' }}>
                                            {{ $departamento->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre" class="font-weight-bold">
                                    <i class="fas fa-map-marker-alt text-danger"></i> Nombre <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nombre" id="nombre" class="form-control" 
                                    value="{{ old('nombre', $provincia->nombre) }}" placeholder="Ej: Chapare" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="descripcion" class="font-weight-bold"><i class="fas fa-info-circle"></i> Descripción</label>
                        <textarea name="descripcion" id="descripcion" class="form-control" style="height: 80px;" placeholder="Descripción de la provincia...">{{ old('descripcion', $provincia->descripcion) }}</textarea>
                    </div>

                    <div class="form-group mt-3">
                        <label for="geometria" class="font-weight-bold">
                            <i class="fas fa-draw-polygon"></i> Geometría (GeoJSON) <span class="text-danger">*</span>
                        </label>
                        <input type="hidden" name="geometria" id="geometria">
                        <div id="jsoneditor" style="height: 300px; border: 1px solid #ddd;"></div>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Modifica la geometría en formato GeoJSON si es necesario.
                        </small>
                    </div>

                    {{-- SECCIÓN DE SUBIDA DE ARCHIVOS --}}
                    <div class="form-group mt-4 bg-light p-3 rounded border">
                        <label for="media" class="font-weight-bold text-primary"><i class="fas fa-cloud-upload-alt"></i> Subir Nuevos Medios</label>
                        <div class="custom-file mb-2">
                            <input type="file" name="media[]" id="media" class="form-control" multiple accept="image/*,video/*">
                        </div>
                        <small class="text-muted d-block">Puedes seleccionar múltiples imágenes o videos a la vez.</small>
                    </div>

                    {{-- SECCIÓN DE MEDIOS EXISTENTES CON OPCIÓN DE ELIMINAR --}}
                    @if($provincia->media && $provincia->media->count() > 0)
                        <hr>
                        <div class="form-group">
                            <label class="font-weight-bold mb-3 text-danger"><i class="fas fa-trash-alt"></i> Administrar Medios Existentes (Marcar para eliminar)</label>
                            
                            <div class="row">
                                @foreach($provincia->media as $media)
                                    <div class="col-6 col-md-3 col-lg-2 mb-3">
                                        <div class="card h-100 shadow-sm border media-card">
                                            <div class="media-preview-container bg-light border-bottom">
                                                @if(str_contains($media->tipo, 'video'))
                                                    <div class="d-flex align-items-center justify-content-center h-100 w-100">
                                                        <i class="fas fa-video fa-2x text-secondary"></i>
                                                    </div>
                                                @else
                                                    <img src="{{ asset('storage/' . $media->archivo) }}" alt="Media" class="media-img">
                                                @endif
                                            </div>
                                            
                                            {{-- Footer con Checkbox de Eliminación --}}
                                            <div class="card-footer p-2 bg-white text-center">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="del_media_{{ $media->id }}" name="delete_media[]" value="{{ $media->id }}">
                                                    <label class="custom-control-label text-danger small font-weight-bold" for="del_media_{{ $media->id }}" style="cursor: pointer;">
                                                        Eliminar
                                                    </label>
                                                </div>
                                                
                                                @if(str_contains($media->tipo, 'video'))
                                                    <a href="{{ asset('storage/' . $media->archivo) }}" target="_blank" class="btn btn-sm btn-link p-0 mt-1" style="font-size: 0.75rem;">Ver video</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <hr>

                    <div class="form-group mt-4 d-flex justify-content-left mb-4">
                        <a href="{{ route('limites.provincias.index') }}" class="btn btn-secondary btn-lg mr-2">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg" id="btn-guardar-provincia">
                            <i class="fas fa-save"></i> Actualizar Provincia
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

        // Carga segura de datos JSON
        const initialData = {!! $provincia->geometria ? json_encode($provincia->geometria) : '{}' !!};
        const jsonData = (typeof initialData === 'string') ? JSON.parse(initialData) : initialData;

        const options = { mode: 'code', modes: ['code', 'tree'], ace: ace };
        const editor = new JSONEditor(container, options);
        editor.set(jsonData);

        const form = document.getElementById("form-provincia");

        form.addEventListener("submit", function(e) {
            try {
                const data = editor.get();
                hiddenInput.value = data ? JSON.stringify(data) : '{}';
            } catch (err) {
                e.preventDefault();
                alert('Error: El JSON de geometría no es válido.');
            }
        });
    });
</script>

<style>
    body { overflow: hidden; }
    .section-header h1 { color: #6c757d !important; font-size: 24px; font-weight: 700; }

    /* Scrollbar */
    .card-body::-webkit-scrollbar { width: 8px; }
    .card-body::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
    .card-body::-webkit-scrollbar-thumb { background: #cdd3d8; border-radius: 4px; }
    .card-body::-webkit-scrollbar-thumb:hover { background: #6777ef; }

    /* Botón Guardar */
    #btn-guardar-provincia {
        background-color: #0d6efd !important; border-color: #0d6efd !important;
        color: white !important; box-shadow: none !important;
    }
    #btn-guardar-provincia:hover { background-color: #0b5ed7 !important; }

    /* Tarjetas de Medios */
    .media-card {
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .media-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.1) !important;
    }

    /* Contenedor de Imagen (Fijo) */
    .media-preview-container {
        height: 100px; 
        width: 100%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Imagen ajustada (Cover) */
    .media-img {
        width: 100%;
        height: 100%;
        object-fit: cover; 
        display: block;
    }

    /* JSON Editor */
    div.jsoneditor { border-color: #e4e6fc; border-radius: 5px; }
    div.jsoneditor-menu { background-color: #6777ef; border-bottom: 1px solid #6777ef; }
</style>
@endsection