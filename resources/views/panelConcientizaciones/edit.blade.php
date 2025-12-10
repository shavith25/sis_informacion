@extends('layouts.app')

@section('title', 'Editar Video de Concientización')

@section('content')
<section class="section">
    
    <div class="section-header">
        <h3 class="page__heading"><i class="fas fa-film text-primary mr-2"></i> Editar Video de Concientización</h3>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Inicio</a></div>
            <div class="breadcrumb-item"><a href="{{ route('panelConcientizaciones.index') }}">Concientización</a></div>
            <div class="breadcrumb-item">Editar</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
        
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                {{-- Mensajes de Error --}}
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
                        <strong><i class="fas fa-exclamation-triangle"></i> Revisa los errores:</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <ul class="mb-0 mt-1 pl-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card card-fixed-scroll">
                    <div class="card-header border-bottom">
                        <h4 class="text-primary m-0"><i class="fas fa-edit text-primary mr-2"></i> Editar Video</h4>
                    </div>
                    
                    <div class="card-body scroll-content">
                        <form action="{{ route('panelConcientizaciones.update', $concientizacion) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="titulo" class="form-label"><i class="fas fa-heading"></i> Título</label>
                                        {{-- CORRECCIÓN: Value con old() y datos de la BD --}}
                                        <input type="text" name="titulo" id="titulo" class="form-control" 
                                                value="{{ old('titulo', $concientizacion->titulo) }}" required>
                                    </div>
                                </div>

                                {{-- CATEGORÍA --}}
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="categoria" class="form-label"><i class="fas fa-tags"></i> Categoría</label>
                                        <select name="categoria" id="categoria" class="form-control" required>
                                            <option value="">Seleccione una categoría</option>
                                            <option value="fauna" {{ old('categoria', $concientizacion->categoria) == 'fauna' ? 'selected' : '' }}>Fauna</option>
                                            <option value="flora" {{ old('categoria', $concientizacion->categoria) == 'flora' ? 'selected' : '' }}>Flora</option>
                                            <option value="ecosistema" {{ old('categoria', $concientizacion->categoria) == 'ecosistema' ? 'selected' : '' }}>Ecosistema</option>
                                            <option value="conservacion" {{ old('categoria', $concientizacion->categoria) == 'conservacion' ? 'selected' : '' }}>Conservación</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- DESCRIPCIÓN --}}
                            <div class="mb-3">
                                <label for="descripcion" class="form-label"><i class="fas fa-info-circle"></i> Descripción</label>
                                <textarea name="descripcion" id="descripcion" class="form-control" style="height: 80px;" required>{{ old('descripcion', $concientizacion->descripcion) }}</textarea>
                            </div>

                            {{-- SECCIÓN DE VIDEOS --}}
                            <div class="row mt-4">
                                <div class="col-lg-6 border-right pr-lg-4 mb-4 mb-lg-0">
                                    <label class="form-label fw-bold text-dark mb-2">
                                        <i class="fas fa-video text-primary mr-1"></i> VIDEO ACTUAL
                                    </label>
                                    <div class="video-container bg-light rounded border p-2">
                                        @if($concientizacion->video_path)
                                            <video controls class="w-100 rounded" style="max-height: 300px;">
                                                <source src="{{ asset('storage/' . $concientizacion->video_path) }}" type="video/mp4">
                                                Tu navegador no soporta videos.
                                            </video>
                                        @else
                                            <div class="text-center p-5 text-muted">Sin video actual</div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Reemplazar Video --}}
                                <div class="col-lg-6 pl-lg-4">
                                    <label for="video" class="form-label fw-bold text-success mb-2">
                                        <i class="fas fa-upload mr-1"></i> REEMPLAZAR VIDEO (OPCIONAL)
                                    </label>
                                    
                                    <div class="input-group mb-2">
                                        <div class="custom-file">
                                            <input type="file" name="video" id="video" class="custom-file-input" accept="video/*">
                                            <label class="custom-file-label" for="video">Elegir archivo</label>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mb-3">Si no seleccionas uno nuevo, se conservará el actual.</small>

                                    {{-- Previsualización JS --}}
                                    <div id="preview-container" class="bg-light rounded border p-2" style="display: none;">
                                        <p class="text-center text-success small mb-2">Previsualización del Nuevo Video:</p>
                                        <video id="videoPreview" controls class="w-100 rounded" style="max-height: 300px;"></video>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-left mt-4 mb-2 border-top pt-3">
                                <a href="{{ route('panelConcientizaciones.index') }}" class="btn btn-secondary mr-2">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-sync-alt"></i> Actualizar Video
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('css')
    <style>
        .card-fixed-scroll {
            height: calc(100vh - 240px); 
            display: flex; flex-direction: column; overflow: hidden;
            border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.05); border: none; margin-bottom: 0 !important;
        }
        .card-header { flex-shrink: 0; background: #fff; padding: 15px 25px; z-index: 2; }
        .scroll-content { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 20px 25px; background-color: #fff; }
        
        /* Scrollbar */
        .scroll-content::-webkit-scrollbar { width: 8px; }
        .scroll-content::-webkit-scrollbar-track { background: #f8f9fa; }
        .scroll-content::-webkit-scrollbar-thumb { background: #cdd3d8; border-radius: 4px; }
        .scroll-content::-webkit-scrollbar-thumb:hover { background: #6777ef; }

        .form-label { font-weight: 700; text-transform: uppercase; font-size: 0.75rem; color: #6c757d; margin-bottom: 0.5rem; }
        .btn { border-radius: 4px; padding: 0.5rem 1.5rem; font-weight: 600; }
        
        .btn-primary { background-color: #2f55d4 !important; border-color: #2f55d4 !important; box-shadow: none !important; color: #fff !important; }
        .btn-primary:hover { background-color: #2442a8 !important; border-color: #2442a8 !important; }

        .video-container video { background-color: #000; }
        @media (min-width: 992px) {
            .border-right { border-right: 1px solid #eee; }
        }
    </style>
@endpush

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });

            const videoInput = document.getElementById('video');
            const previewContainer = document.getElementById('preview-container');
            const videoPreview = document.getElementById('videoPreview');

            if(videoInput) {
                videoInput.addEventListener('change', function(event) {
                    const file = event.target.files[0];

                    if (file) {
                        const fileURL = URL.createObjectURL(file);
                        videoPreview.src = fileURL;
                        previewContainer.style.display = 'block';
                        videoPreview.load();
                        videoPreview.play();
                    } else {
                        previewContainer.style.display = 'none';
                        videoPreview.src = "";
                    }
                });
            }
        });
    </script>
@endpush