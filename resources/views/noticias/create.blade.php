@extends('layouts.app')

@section('title', 'Crear Nueva Noticia')

@section('content')
<section class="section">
    
    <div class="section-header">
        <h3 class="page__heading">Creación de Noticias</h3>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Inicio</a></div>
            <div class="breadcrumb-item"><a href="{{ route('noticias.index') }}">Noticias</a></div>
            <div class="breadcrumb-item">Crear</div>
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

                <div class="card card-fixed-scroll">
                    <div class="card-header border-bottom">
                        <h4 class="text-dark m-0"><i class="fas fa-plus-circle text-primary mr-2"></i> Formulario de Creación</h4>
                    </div>
                    
                    <div class="card-body scroll-content">
                        <form action="{{ route('noticias.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row d-flex align-items-end">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="titulo" class="form-label"><i class="fas fa-heading"></i> Título</label>
                                        <input id="titulo" type="hidden" name="titulo" value="{{ old('titulo') }}">
                                        <div class="sticky-toolbar-wrapper">
                                            <trix-editor id="trix-titulo-editor" input="titulo" class="form-control" placeholder="Escribe el título..."></trix-editor>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="subtitulo" class="form-label"><i class="fas fa-quote-right"></i> Subtítulo</label>
                                        <input type="text" name="subtitulo" id="subtitulo" class="form-control" value="{{ old('subtitulo') }}" required placeholder="Resumen corto...">
                                    </div>
                                </div>
                            </div>

                            {{-- Descripción --}}
                            <div class="mb-3">
                                <label for="descripcion" class="form-label"><i class="fas fa-align-left"></i> Descripción Completa</label>
                                <textarea name="descripcion" id="descripcion" class="form-control" style="height: 150px;" required placeholder="Contenido de la noticia..."></textarea>
                            </div>

                            {{-- Autor y Fecha --}}
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="autor" class="form-label"><i class="fas fa-user"></i> Autor</label>
                                    <input type="text" name="autor" id="autor" class="form-control" value="{{ old('autor') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_publicacion" class="form-label"><i class="fas fa-calendar-alt"></i> Fecha publicación</label>
                                    <input type="date" name="fecha_publicacion" id="fecha_publicacion" class="form-control" value="{{ old('fecha_publicacion') }}" required>
                                </div>
                            </div>

                            <hr>

                            {{-- Imágenes --}}
                            <div class="mb-4">
                                <label for="imagenes" class="form-label"><i class="fas fa-images"></i> Galería de Imágenes</label>
                                <div class="custom-file">
                                    <input type="file" name="imagenes[]" id="imagenes" class="form-control pt-1" multiple accept="image/*">
                                </div>
                                <div id="preview-imagenes" class="d-flex flex-wrap gap-2 mt-3"></div>
                            </div>

                            {{-- Botones --}}
                            <div class="d-flex justify-content-right mt-4 mb-2">
                                <a href="{{ route('noticias.index') }}" class="btn btn-secondary mr-2">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar Noticia
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
            background-color: #2442a8 !important; /* Azul más oscuro al pasar mouse */
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

        .preview-item { position: relative; width: 80px; height: 80px; border: 1px solid #ddd; border-radius: 4px; overflow: hidden; }
        .preview-item img { width: 100%; height: 100%; object-fit: cover; }
        .remove-preview { position: absolute; top: 0; right: 0; background: red; color: white; border: none; width: 20px; height: 20px; font-size: 12px; cursor: pointer; }
    </style>
@endpush

@push('js')
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('imagenes');
            const previewContainer = document.getElementById('preview-imagenes');
            if(input && previewContainer) {
                input.addEventListener('change', function() {
                    previewContainer.innerHTML = ''; 
                    Array.from(this.files).forEach((file) => {
                        if (!file.type.startsWith('image/')) return;
                        const reader = new FileReader();
                        const item = document.createElement('div');
                        item.className = 'preview-item';
                        reader.onload = function(e) {
                            item.innerHTML = `<img src="${e.target.result}"><button type="button" class="remove-preview" onclick="this.parentElement.remove()">×</button>`;
                            previewContainer.appendChild(item);
                        };
                        reader.readAsDataURL(file);
                    });
                });
            }
        });
    </script>
@endpush