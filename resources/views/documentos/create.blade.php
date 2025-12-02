@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"><i class="fas fa-file-medical text-primary mr-2"></i> Crear Nuevo Documento</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('home') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('documentos.index') }}">Documentos</a></div>
                <div class="breadcrumb-item">Nuevo</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-edit text-primary mr-2"></i> Formulario de Documento</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="titulo"><i class="fas fa-heading"></i> Título <span class="text-danger">*</span></label>
                                        <input type="text" name="titulo" id="titulo" class="form-control" placeholder="Escriba el título del documento" required>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="numero_documento"><i class="fas fa-hashtag"></i> Número de Documento <span class="text-danger">*</span></label>
                                        <input type="text" name="numero_documento" id="numero_documento" class="form-control" required>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="fecha_publicacion"><i class="fas fa-calendar-alt"></i> Fecha de Publicación <span class="text-danger">*</span></label>
                                        <input type="date" name="fecha_publicacion" id="fecha_publicacion" class="form-control" required>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="fecha_emision"><i class="fas fa-calendar-alt"></i> Fecha de Emisión <span class="text-danger">*</span></label>
                                        <input type="date" name="fecha_emision" id="fecha_emision" class="form-control" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="resumen"><i class="fas fa-book-open"></i> Resumen <span class="text-danger">*</span></label>
                                    <textarea name="resumen" id="resumen" style="height: 80px; width: 100%;" class="form-control" required></textarea>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="icono"><i class="fas fa-image"></i> Icono (Opcional)</label>
                                        <div class="custom-file">
                                            <input type="file" name="icono" id="icono" class="custom-file-input" accept="image/jpeg,image/png,image/jpg">
                                            <label class="custom-file-label" for="icono" id="icono-label">Seleccionar una imagen</label>
                                        </div>
                                        <small class="form-text text-muted">Formatos: JPEG, PNG, JPG (Max. 2MB)</small>
                                        <div id="icono-preview" class="mt-2 d-none">
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle"></i> Imagen seleccionada
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="pdf"><i class="fas fa-file-pdf"></i> Documento PDF <span class="text-danger">*</span></label>
                                        <div class="custom-file">
                                            <input type="file" name="pdf" id="pdf" class="custom-file-input" accept="application/pdf" required>
                                            <label class="custom-file-label" for="pdf" id="pdf-label">Seleccionar PDF</label>
                                        </div>
                                        <small class="form-text text-muted">Tamaño máximo: 10MB</small>
                                        <div id="pdf-preview" class="mt-2 d-none">
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle"></i> PDF seleccionado
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <br>

                                <div class="form-group text-left">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Documento</button>
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
        background-image: none !important; 
        color: #ffffff !important;
        opacity: 1 !important;
    }

    /* Estado al pasar el mouse */
    .btn-primary:hover, 
    .btn-primary:focus, 
    .btn-primary:active {
        background-color: #2442a8 !important; 
        border-color: #2442a8 !important;
        box-shadow: none !important;
        transform: none !important; 
    }
</style>
@endpush

@push('js')
<script>
    // Script simplificado y corregido para jQuery estándar
    $(document).ready(function() {
        // Función para actualizar el nombre del archivo en el input
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            var label = $(this).siblings('.custom-file-label');
            var previewId = $(this).attr('id') + '-preview';
            
            if (fileName) {
                label.addClass("selected").html(fileName);
                $('#' + previewId).removeClass('d-none');
            } else {
                label.removeClass("selected").html('Seleccionar archivo');
                $('#' + previewId).addClass('d-none');
            }
        });
    });
</script>
@endpush