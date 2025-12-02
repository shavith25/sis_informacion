@extends('layouts.app')

@section('title', 'Nuevo Video de Concientización')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading"><i class="fas fa-plus-circle text-primary mr-2"></i> Agregar Video de Concientización</h3>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Inicio</a></div>
            <div class="breadcrumb-item"><a href="{{ route('panelConcientizaciones.index') }}">Concientización</a></div>
            <div class="breadcrumb-item">Nuevo</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-video"></i> Nuevo Video</h4>
            </div>

            <div class="card-body">
                {{-- ⚠️ ELIMINADO: Bloque de alerta de éxito de Bootstrap --}}

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible show fade">
                        <div class="alert-body">
                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <ul class="mb-0 pl-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('panelConcientizaciones.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="titulo" class="form-label"><i class="fas fa-heading"></i> Título</label>
                            <input type="text" name="titulo" id="titulo" class="form-control" value="{{ old('titulo') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="categoria" class="form-label"><i class="fas fa-tags"></i> Categoría</label>
                            <select name="categoria" id="categoria" class="form-control" required>
                                <option value="">Seleccione una categoría</option>
                                <option value="fauna" {{ old('categoria') === 'fauna' ? 'selected' : '' }}>Fauna</option>
                                <option value="flora" {{ old('categoria') === 'flora' ? 'selected' : '' }}>Flora</option>
                                <option value="ecosistema" {{ old('categoria') === 'ecosistema' ? 'selected' : '' }}>Ecosistema</option>
                                <option value="conservacion" {{ old('categoria') === 'conservacion' ? 'selected' : '' }}>Conservación</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label"><i class="fas fa-info-circle"></i> Descripción</label>
                        <textarea name="descripcion" id="descripcion" class="form-control" style="height: 120px;" required>{{ old('descripcion') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="video" class="form-label"><i class="fas fa-video"></i> Subir Video</label>
                        <input type="file" name="video" id="video" class="form-control" accept="video/*" required>
                        <small class="text-muted">Formatos permitidos: MP4, WEBM, OGG. Tamaño máximo recomendado: 50MB.</small>

                        <div class="mt-3" id="preview" style="display: none;">
                            <video id="videoPreview" width="300" height="180" controls></video>
                        </div>
                    </div>

                    <div class="text-left">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Video
                        </button>

                        <a href="{{ route('panelConcientizaciones.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection

@push('css')
<style>
    .btn-primary {
        background-color: #2f55d4 !important; 
        border-color: #2f55d4 !important;
        box-shadow: none !important; 
        background-image: none !important;
        color: #ffffff !important;
    }

    /* Estado al pasar el mouse */
    .btn-primary:hover, 
    .btn-primary:focus, 
    .btn-primary:active {
        background-color: #2442a8 !important; 
        border-color: #2442a8 !important;
        box-shadow: none !important;
    }
</style>
@endpush

@section('scripts')
<script>
    // Script para la previsualización del video
    document.getElementById('video').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview');
        const video = document.getElementById('videoPreview');

        if (file) {
            const url = URL.createObjectURL(file);
            video.src = url;
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    });
    
</script>

@endsection
