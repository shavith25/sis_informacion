@extends('layouts.app')

@section('title', 'Editar Municipio')

@section('content')
<section class="section">
    <div class="section-header">
        <h1 style="color: #6c757d; font-weight: 700;">
            <i class="fas fa-edit mr-2"></i> Editar Municipio: {{ $municipio->nombre }}
        </h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Inicio</a></div>
            <div class="breadcrumb-item"><a href="#">Límites</a></div>
            <div class="breadcrumb-item"><a href="{{ route('limites.municipios.index') }}">Municipios</a></div>
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

        <div class="card card-fixed-height">
            <div class="card-header">
                <h4><i class="fas fa-edit text-primary"></i> Formulario de Edición</h4>
            </div>
            
            <div class="card-body custom-scroll-area">
                <form action="{{ route('limites.municipios.update', $municipio) }}" method="POST" enctype="multipart/form-data" id="form-municipio">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_provincia" class="font-weight-bold">
                                    <i class="fas fa-map-marked-alt text-primary"></i> Provincia <span class="text-danger">*</span>
                                </label>
                                <select name="id_provincia" id="id_provincia" class="form-control select2" required>
                                    <option value="">-- Seleccione --</option>
                                    @foreach($provincias as $provincia)
                                        <option value="{{ $provincia->id }}" 
                                            {{ old('id_provincia', $municipio->id_provincia) == $provincia->id ? 'selected' : '' }}>
                                            {{ $provincia->nombre }}
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
                                    value="{{ old('nombre', $municipio->nombre) }}" placeholder="Ej: Tiquipaya" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="descripcion" class="font-weight-bold">
                            <i class="fas fa-info-circle"></i> Descripción
                        </label>
                        <textarea name="descripcion" id="descripcion" class="form-control" style="height: 80px;" placeholder="Descripción del municipio...">{{ old('descripcion', $municipio->descripcion) }}</textarea>
                    </div>

                    <div class="form-group mt-3">
                        <label for="geometria" class="font-weight-bold">
                            <i class="fas fa-draw-polygon"></i> Geometría (GeoJSON) <span class="text-danger">*</span>
                        </label>
                        <input type="hidden" name="geometria" id="geometria">
                        
                        <div id="jsoneditor" style="height: 300px; border: 1px solid #ddd;"></div>
                        
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Modifica la geometría en formato GeoJSON válido.
                        </small>
                    </div>

                    <div class="form-group mt-4">
                        <label for="media" class="font-weight-bold"><i class="fas fa-images"></i> Subir Medios (Imágenes/Videos)</label>
                        <div class="custom-file">
                            <input type="file" name="media[]" id="media" class="form-control" multiple accept="image/*,video/*">
                        </div>
                        <small class="text-muted">Puedes subir nuevos archivos para agregar a la galería.</small>
                    </div>

                    @if($municipio->media && $municipio->media->count())
                    <hr>
                    <div class="form-group">
                        <label class="font-weight-bold mb-3">Administrar Medios Existentes</label>
                        <div class="row">
                            @foreach($municipio->media as $media)
                                <div class="col-md-2 col-sm-4 mb-3">
                                    <div class="card h-100 shadow-sm border mb-0">
                                        <div class="card-body p-1 text-center bg-light d-flex align-items-center justify-content-center" style="height: 100px; overflow: hidden;">
                                            @if(str_contains($media->tipo, 'video'))
                                                <i class="fas fa-video fa-2x text-secondary"></i>
                                            @else
                                                <img src="{{ asset('storage/' . $media->archivo) }}" class="img-fluid" style="max-height: 100%; object-fit: contain;">
                                            @endif
                                        </div>
                                        <div class="card-footer p-2 bg-white text-center border-top">
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

                    <div class="form-group mt-5 d-flex mb-5">
                        <a href="{{ route('limites.municipios.index') }}" class="btn btn-secondary btn-lg mr-2">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        
                        {{-- Botón Azul Sólido --}}
                        <button type="submit" class="btn btn-primary btn-action-custom" id="btn-guardar-municipio">
                            <i class="fas fa-save mr-1"></i> Guardar Cambios
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
document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("jsoneditor");
    const hiddenInput = document.getElementById("geometria");

    // Cargar datos existentes o objeto vacío
    const initialData = {!! $municipio->geometria ? json_encode($municipio->geometria) : '{}' !!};
    const jsonData = (typeof initialData === 'string') ? JSON.parse(initialData) : initialData;

    const options = {
        mode: 'code',
        modes: ['code', 'tree'],
        ace: ace // Usa Ace editor si está disponible
    };

    const editor = new JSONEditor(container, options);
    editor.set(jsonData);

    const form = document.getElementById("form-municipio");

    form.addEventListener("submit", function (e) {
        try {
            const data = editor.get();
            hiddenInput.value = data ? JSON.stringify(data) : '{}';
        } catch (err) {
            e.preventDefault();

            Swal.fire({
                icon: 'error',
                title: 'Geometría inválida',
                text: 'El JSON de la geometría no es válido. Por favor, revisa la estructura antes de guardar.',
                confirmButtonText: 'Entendido',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-danger'
                }
            });
        }
    });
});
</script>


@push('css')
<style>
    /* 1. Layout Fijo */
    body { overflow: hidden; }

    /* 2. Card con scroll interno */
    .card-fixed-height {
        height: calc(100vh - 230px) !important;
        display: flex;
        flex-direction: column;
        margin-bottom: 0 !important;
    }
    .custom-scroll-area {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 10px;
    }
    
    /* Scrollbar personalizado */
    .custom-scroll-area::-webkit-scrollbar { width: 8px; }
    .custom-scroll-area::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
    .custom-scroll-area::-webkit-scrollbar-thumb { background: #cdd3d8; border-radius: 4px; }
    .custom-scroll-area::-webkit-scrollbar-thumb:hover { background: #6777ef; }

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
    
    /* Editor JSON */
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
    
    /* 4. Botón Azul Sólido (Estilo Unificado) */
    .btn-action-custom {
        background-color: #2f55d4 !important; 
        border-color: #2f55d4 !important;
        color: white !important;
        box-shadow: none !important;
        border-radius: 50px;
        padding: 0.55rem 1.5rem;
        font-weight: 600;
        transition: opacity 0.3s ease;
    }
    .btn-action-custom:hover, 
    .btn-action-custom:focus, 
    .btn-action-custom:active {
        background-color: #2f55d4 !important; 
        border-color: #2f55d4 !important;
        color: white !important;
        opacity: 0.9;
        transform: none !important;
    }
</style>
@endpush
@endsection