@extends('layouts.app')

@section('title', 'Editar Provincia')

@section('content')
<section class="section">
    <div class="section-header">
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

    <div class="section-body">
        
        @if(session('success'))
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                {{ session('success') }}
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert"><span>&times;</span></button>
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
                <h4><i class="fas fa-pen text-primary"></i> Formulario de Edición</h4>
            </div>
            
            <div class="card-body">
                <form action="{{ route('limites.provincias.update', $provincia->id) }}" method="POST" enctype="multipart/form-data" id="form-provincia">
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
                        <label for="descripcion" class="font-weight-bold">
                            <i class="fas fa-info-circle"></i> Descripción
                        </label>
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

                    <div class="form-group mt-4">
                        <label for="media" class="font-weight-bold"><i class="fas fa-images"></i> Subir Medios (Imágenes/Videos)</label>
                        
                        {{-- Mostrar imagen actual si existe --}}
                        @if($provincia->media && $provincia->media->count() > 0)
                            <div class="mb-2">
                                <p class="text-muted mb-1">Imagen Actual:</p>
                                <img src="{{ asset('storage/' . $provincia->media->first()->archivo) }}" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        @endif

                        <div class="custom-file">
                            <input type="file" name="media[]" id="media" class="form-control" multiple accept="image/*,video/*">
                        </div>
                        <small class="text-muted">Puedes subir nuevas imágenes para reemplazar o agregar.</small>
                    </div>

                    {{-- BOTONES ALINEADOS --}}
                    <div class="form-group mt-5 d-flex mb-5">
                        <a href="{{ route('limites.provincias.index') }}" class="btn btn-secondary btn-lg mr-2">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>

                        <button type="submit" class="btn btn-primary btn-lg" id="btn-guardar-provincia">
                            <i class="fas fa-save"></i> Actualizar Provincia
                        </button>
                    </div>

                    <div style="height: 60px;"></div>

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

        // CARGAR DATOS EXISTENTES O VACÍOS
        // Usamos json_encode con un valor por defecto de objeto vacío si es null
        const initialData = {!! $provincia->geometria ? json_encode($provincia->geometria) : '{}' !!};

        // Si viene como string JSON, lo parseamos, si ya es objeto lo usamos directo
        const jsonData = (typeof initialData === 'string') ? JSON.parse(initialData) : initialData;

        const options = {
            mode: 'code',
            modes: ['code', 'tree'],
            ace: ace
        };

        const editor = new JSONEditor(container, options);
        editor.set(jsonData);

        const form = document.getElementById("form-provincia");

        form.addEventListener("submit", function(e) {
            try {
                const data = editor.get();
                if (!data) {
                    hiddenInput.value = '{}'; 
                } else {
                    hiddenInput.value = JSON.stringify(data);
                }
            } catch (err) {
                e.preventDefault();
                alert('Error: El código en el campo Geometría no es un JSON válido.');
                console.error(err);
            }
        });
    });
</script>

<style>
    /* 1. Layout Fijo */
    body { overflow: hidden; }

    /* 2. Card con scroll interno */
    .card {
        height: calc(100vh - 230px) !important;
        display: flex;
        flex-direction: column;
        margin-bottom: 0 !important;
    }
    .card-body {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 10px;
    }
    
    .card-body::-webkit-scrollbar { width: 8px; }
    .card-body::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
    .card-body::-webkit-scrollbar-thumb { background: #cdd3d8; border-radius: 4px; }
    .card-body::-webkit-scrollbar-thumb:hover { background: #6777ef; }

    /* 3. Estilos Visuales */
    .section-header h1 {
        color: #6c757d !important;
        font-size: 24px;
        font-weight: 700;
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

    /* 4. Botón Azul Sólido */
    #btn-guardar-provincia {
        background-color: #0d6efd !important; 
        border-color: #0d6efd !important;
        color: white !important;
        box-shadow: none !important;
        background-image: none !important;
    }
    #btn-guardar-provincia:hover, 
    #btn-guardar-provincia:focus, 
    #btn-guardar-provincia:active {
        background-color: #0b5ed7 !important; 
        border-color: #0a58ca !important;
        color: white !important;
        opacity: 1 !important;
    }
</style>
@endsection