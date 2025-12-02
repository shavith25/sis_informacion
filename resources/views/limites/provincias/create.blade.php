@extends('layouts.app')

@section('title', 'Crear Nueva Provincia')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1 style="color: #6c757d; font-weight: 700;">
                <i class="fas fa-plus mr-2"></i> Crear Nueva Provincia
            </h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('limites.provincias.index') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="#">Límites</a></div>
                <div class="breadcrumb-item"><a href="{{ route('limites.provincias.index') }}">Provincias</a></div>
                <div class="breadcrumb-item">Crear</div>
            </div>
        </div>

        <div class="section-body">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-plus-circle text-primary"></i> Formulario de Registro</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('limites.provincias.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="departamento_id" class="font-weight-bold">
                                        <i class="fas fa-map text-primary"></i> Departamento <span
                                            class="text-danger">*</span>
                                    </label>
                                    <select name="id_departamento" id="departamento_id" class="form-control" required>
                                        <option value="">-- Seleccione el departamento --</option>
                                        @foreach ($departamentos as $departamento)
                                            <option value="{{ $departamento->id }}"
                                                {{ old('id_departamento') == $departamento->id ? 'selected' : '' }}>
                                                {{ $departamento->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre" class="font-weight-bold">
                                        <i class="fas fa-map-marker-alt text-danger"></i> Nombre <span
                                            class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="nombre" id="nombre" class="form-control"
                                        value="{{ old('nombre') }}" placeholder="Ej: Chapare" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="descripcion" class="font-weight-bold">
                                <i class="fas fa-info-circle"></i> Descripción
                            </label>
                            <textarea name="descripcion" id="descripcion" class="form-control" style="height: 80px;"
                                placeholder="Descripción de la provincia...">{{ old('descripcion') }}</textarea>
                        </div>

                        <div class="form-group mt-3">
                            <label for="geometria" class="font-weight-bold">
                                <i class="fas fa-draw-polygon"></i> Geometría (GeoJSON) <span class="text-danger">*</span>
                            </label>
                            <input type="hidden" name="geometria" id="geometria">

                            <div id="jsoneditor" style="height: 300px; border: 1px solid #ddd;"></div>

                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Ingresa la geometría en formato GeoJSON válido. Ejemplo:
                                <code>{"type":"Polygon",...}</code>
                            </small>
                        </div>

                        <div class="form-group mt-4">
                            <label for="media" class="font-weight-bold"><i class="fas fa-images"></i> Subir Medios
                                (Imágenes/Videos)</label>
                            <div class="custom-file">
                                <input type="file" name="media[]" id="media" class="form-control" multiple
                                    accept="image/*,video/*">
                            </div>
                            <small class="text-muted">Puedes subir múltiples imágenes o videos. Mantén presionada Ctrl (Cmd
                                en Mac) para seleccionar varios archivos.</small>
                        </div>

                        <div class="form-group mt-5 d-flex">
                            <a href="{{ route('limites.provincias.index') }}" class="btn btn-secondary btn-lg mr-2">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>

                            <button type="submit" class="btn btn-primary btn-lg" id="btn-guardar-provincia">
                                <i class="fas fa-save"></i> Guardar Provincia
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

            // Datos iniciales vacíos para creación
            const initialData = {};

            // Configuración del editor
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
                    console.warn('JSON inválido o vacío');

                    hiddenInput.value = JSON.stringify({});
                }
            });
        });
    </script>

    <style>
        body {
            overflow: hidden;
        }

        .card {
            height: calc(100vh - 230px) !important;
            display: flex;
            flex-direction: column;
            margin-bottom: 0 !important;
        }

        /* Habilitar scroll dentro del cuerpo de la tarjeta */
        .card-body {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding-right: 10px;
        }

        /* Barra de scroll estilizada (opcional) */
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
            background: #6777ef;
        }

        .section-header h1 {
            color: #6c757d !important;
            /* Gris oscuro */
            font-size: 24px;
            font-weight: 700;
            text-shadow: none;
        }

        /* Para que el icono herede el mismo color */
        .section-header h1 i {
            color: #6c757d !important;
        }

        .form-control:focus {
            border-color: #6777ef;
            box-shadow: none;
        }

        /* Estilo azul para el editor JSON */
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

        /* ESTILO PARA EL BOTÓN GUARDAR PROVINCIA (Azul Sólido) */
        #btn-guardar-provincia {
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
            color: white !important;
            box-shadow: none !important;
            background-image: none !important;
        }

        /* Eliminar el fondo blanco al pasar el mouse (Hover) */
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
