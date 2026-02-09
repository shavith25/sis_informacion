@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"><i class="fas fa-pen text-primary mr-2"></i> 
                Editar Documento: <span class="text-primary">{{ $documento->titulo }}</span>
            </h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('home') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('documentos.index') }}">Documentos</a></div>
                <div class="breadcrumb-item">Editar</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-file-signature text-primary mr-2"></i> Editar Documento: {{ $documento->titulo }}</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('documentos.update', $documento) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="titulo"><i class="fas fa-heading"></i> Título <span class="text-danger">*</span></label>
                                        <input type="text" name="titulo" id="titulo" class="form-control" value="{{ old('titulo', $documento->titulo) }}" required>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="numero_documento"><i class="fas fa-hashtag"></i> Número de Documento <span class="text-danger">*</span></label>
                                        <input type="text" name="numero_documento" id="numero_documento" class="form-control" value="{{ old('numero_documento', $documento->numero_documento) }}" required>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="fecha_publicacion"><i class="fas fa-calendar-alt"></i> Fecha de Publicación <span class="text-danger">*</span></label>
                                        <input type="date" name="fecha_publicacion" id="fecha_publicacion" class="form-control"
                                            value="{{ old('fecha_publicacion', $documento->fecha_publicacion ? \Carbon\Carbon::parse($documento->fecha_publicacion)->format('Y-m-d') : '') }}" required>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="fecha_emision"><i class="fas fa-calendar-alt"></i> Fecha de Emisión <span class="text-danger">*</span></label>
                                        <input type="date" name="fecha_emision" id="fecha_emision" class="form-control"
                                            value="{{ old('fecha_emision', $documento->fecha_emision ? \Carbon\Carbon::parse($documento->fecha_emision)->format('Y-m-d') : '') }}" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="resumen"><i class="fas fa-book-open"></i> Resumen <span class="text-danger">*</span></label>
                                        <textarea name="resumen" id="resumen" style="height: 110px;" class="form-control" required>{{ old('resumen', $documento->resumen) }}</textarea>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="icono"><i class="fas fa-image"></i> Icono (Opcional)</label>
                                        <div class="custom-file">
                                            <input type="file" name="icono" id="icono" class="custom-file-input" accept="image/jpeg,image/png,image/jpg">
                                            <label class="custom-file-label" for="icono">
                                                @if($documento->icono)
                                                    Cambiar imagen (actual: {{ basename($documento->icono) }})
                                                @else
                                                    Seleccionar imagen (JPEG, PNG, JPG)
                                                @endif
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Tamaño máximo: 2MB</small>
                                        @if($documento->icono)
                                            <div class="mt-2">
                                                <img src="{{ Storage::url($documento->icono) }}" alt="Icono actual" style="max-height: 60px;" class="img-thumbnail">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="pdf"><i class="fas fa-file-pdf"></i> Documento PDF</label>
                                        <div class="custom-file">
                                            <input type="file" name="pdf" id="pdf" class="custom-file-input" accept="application/pdf">
                                            <label class="custom-file-label" for="pdf">
                                                @if($documento->pdf)
                                                    Cambiar PDF (actual: {{ basename($documento->pdf) }})
                                                @else
                                                    Seleccionar archivo PDF
                                                @endif
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Tamaño máximo: 10MB</small>
                                        @if($documento->pdf)
                                            <div class="mt-2">
                                                <a href="{{ Storage::url($documento->pdf) }}" target="_blank" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye mr-1"></i> Ver PDF actual
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group text-left">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-sync-alt"></i> Actualizar Documento</button>
                                    <a href="{{ route('documentos.index') }}" class="btn btn-light ml-2"><i class="fas fa-times"></i> Cancelar</a>
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
    .btn-primary {
        background-color: #2f55d4 !important;
        border-color: #2f55d4 !important;
        box-shadow: none !important; 
        color: #ffffff !important;
        background-image: none !important;
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

@push('js')
    <script>
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    </script>
@endpush
