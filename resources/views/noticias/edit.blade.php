@extends('layouts.app')

@section('title', 'Editar Noticia')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Edición de Noticias</h3>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Inicio</a></div>
            <div class="breadcrumb-item"><a href="{{ route('noticias.index') }}">Noticias</a></div>
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

                {{-- 2. Tarjeta con Altura Calculada (Sin Scroll Externo) --}}
                <div class="card card-fixed-scroll">
                    <div class="card-header border-bottom">
                        <h4 class="text-dark m-0">
                            <i class="fas fa-pen text-primary mr-2"></i> Editar Noticia: {{ strip_tags($noticia->titulo) }}
                        </h4>
                    </div>
                    
                    {{-- 3. Scroll Interno --}}
                    <div class="card-body scroll-content">
                        <form action="{{ route('noticias.update', $noticia) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            {{-- Título y Subtítulo --}}
                            <div class="row d-flex align-items-end">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="titulo" class="form-label"><i class="fas fa-heading"></i> Título</label>
                                        <input id="titulo" type="hidden" name="titulo" value="{{ old('titulo', $noticia->titulo) }}">
                                        <div class="sticky-toolbar-wrapper">
                                            <trix-editor id="trix-titulo-editor" input="titulo" class="form-control"></trix-editor>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="subtitulo" class="form-label"><i class="fas fa-quote-right"></i> Subtítulo</label>
                                        <input type="text" name="subtitulo" id="subtitulo" class="form-control" 
                                            value="{{ old('subtitulo', $noticia->subtitulo) }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label"><i class="fas fa-align-left"></i> Descripción Completa</label>
                                <textarea name="descripcion" id="descripcion" class="form-control" style="height: 150px;" required>{{ old('descripcion', $noticia->descripcion) }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="autor" class="form-label"><i class="fas fa-user"></i> Autor</label>
                                    <input type="text" name="autor" id="autor" class="form-control" 
                                        value="{{ old('autor', $noticia->autor) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_publicacion" class="form-label"><i class="fas fa-calendar-alt"></i> Fecha publicación</label>
                                    <input type="date" name="fecha_publicacion" id="fecha_publicacion" class="form-control" 
                                        value="{{ old('fecha_publicacion', $noticia->fecha_publicacion->format('Y-m-d')) }}" required>
                                </div>
                            </div>

                            <hr>

                            @if($noticia->imagenes->count())
                                <div class="mb-4">
                                    <label class="form-label font-weight-bold text-dark">Imágenes Actuales</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($noticia->imagenes as $imagen)
                                            <div class="position-relative imagen-container mr-2 mb-2">
                                                <div class="preview-item">
                                                    <img src="{{ asset('storage/' . $imagen->ruta) }}" alt="Imagen">
                                                </div>
                                                <button type="button" class="btn btn-sm btn-danger position-absolute eliminar-imagen" 
                                                        style="top: -5px; right: -5px; border-radius: 50%; width: 25px; height: 25px; padding: 0; font-size: 12px;"
                                                        data-id="{{ $imagen->id }}">✕</button>
                                                <input type="hidden" name="eliminar_imagenes[]" value="" class="input-eliminar">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Nuevas Imágenes --}}
                            <div class="mb-4">
                                <label for="imagenes" class="form-label"><i class="fas fa-images"></i> Agregar Nuevas Imágenes</label>
                                <div class="custom-file">
                                    <input type="file" name="imagenes[]" id="imagenes" class="form-control pt-1" multiple accept="image/*">
                                </div>
                                <small class="text-muted mt-1 d-block">Selecciona una o más imágenes nuevas si deseas agregarlas.</small>
                                <div id="preview-nuevas" class="d-flex flex-wrap gap-2 mt-3"></div>
                            </div>

                            <div class="d-flex justify-content-right mt-4 mb-2">
                                <a href="{{ route('noticias.index') }}" class="btn btn-secondary mr-2">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-sync-alt"></i> Actualizar Noticia
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
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    <style>
        .btn-primary { 
            background-color: #2f55d4 !important; 
            border-color: #2f55d4 !important;
            box-shadow: none !important; 
            color: #fff !important;
        }
        .btn-primary:hover { 
            background-color: #2442a8 !important; 
            border-color: #2442a8 !important;
        }

        /* --- LÓGICA DE SCROLL --- */
        .card-fixed-scroll {
            height: calc(100vh - 240px); 
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            border: none;
            margin-bottom: 0 !important;
        }

        .card-header {
            flex-shrink: 0;
            background: #fff;
            padding: 15px 25px;
            z-index: 2;
        }

        .scroll-content {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 20px 25px;
            background-color: #fff;
        }

        /* Scrollbar */
        .scroll-content::-webkit-scrollbar { width: 8px; }
        .scroll-content::-webkit-scrollbar-track { background: #f8f9fa; }
        .scroll-content::-webkit-scrollbar-thumb { background: #cdd3d8; border-radius: 4px; }
        .scroll-content::-webkit-scrollbar-thumb:hover { background: #6777ef; }

        /* --- ESTILOS GENERALES --- */
        .form-label { font-weight: 700; text-transform: uppercase; font-size: 0.75rem; color: #6c757d; margin-bottom: 0.5rem; }
        .btn { border-radius: 4px; padding: 0.5rem 1.5rem; font-weight: 600; }
        
        /* Trix Fixes */
        .sticky-toolbar-wrapper { position: relative; }
        trix-toolbar { 
            position: sticky; 
            top: 0; 
            z-index: 100; 
            background: #fff; 
            border-bottom: 1px solid #eee;
        }

        trix-editor { min-height: 45px; border-color: #e4e6fc; }
        
        #trix-titulo-editor + trix-toolbar .trix-button-group { display: none; }
        #trix-titulo-editor + trix-toolbar .trix-button-group--text-tools { display: inline-block; }

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
            background: rgba(220, 53, 69, 0.9); color: white; 
            border: none; border-radius: 50%; 
            width: 20px; height: 20px; 
            font-size: 12px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
        }
    </style>
@endpush

@push('js')
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.eliminar-imagen').forEach(btn => {
                btn.addEventListener('click', function () {
                    const container = this.closest('.imagen-container');
                    const hiddenInput = container.querySelector('.input-eliminar');

                    hiddenInput.value = this.dataset.id;
                    container.style.opacity = "0.4";
                    this.remove(); 
                });
            });

            // Lógica Previsualizar Nuevas
            const input = document.getElementById('imagenes');
            const previewContainer = document.getElementById('preview-nuevas');
            if(input && previewContainer) {
                input.addEventListener('change', function() {
                    previewContainer.innerHTML = ''; 
                    Array.from(this.files).forEach((file) => {
                        if (!file.type.startsWith('image/')) return;
                        const reader = new FileReader();
                        const item = document.createElement('div');
                        item.className = 'preview-item mr-2 mb-2';
                        
                        // Envolver en un div relativo para el botón de borrar
                        const wrapper = document.createElement('div');
                        wrapper.className = 'position-relative';

                        reader.onload = function(e) {
                            item.innerHTML = `<img src="${e.target.result}">`;
                            const btn = document.createElement('button');
                            btn.className = 'remove-preview';
                            btn.innerHTML = '×';
                            btn.type = 'button';
                            btn.onclick = function() { wrapper.remove(); };
                            
                            wrapper.appendChild(item);
                            wrapper.appendChild(btn);
                            previewContainer.appendChild(wrapper);
                        };
                        reader.readAsDataURL(file);
                    });
                });
            }
        });
    </script>
@endpush