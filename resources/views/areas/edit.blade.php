@extends('layouts.app')

@section('title', 'Editar Área Protegida')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">
                <i class="fas fa-edit mr-2"></i> Editar Área Protegida: {{ $area->area }}
            </h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('areas.index') }}">Áreas Protegidas</a></div>
                <div class="breadcrumb-item active">Editar</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row justify-content-left">
                <div class="col-lg-12 col-md-12">
                    <div class="card shadow-lg custom-card-form">
                        <div class="card-header custom-card-header">
                            <h4>Formulario de Edición</h4>
                        </div>

                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Por favor, corrige los siguientes errores:
                                    <ul class="mb-0 mt-2 pl-4">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <form action="{{ route('areas.update', $area->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="area" class="required-label"
                                        style="font-size: 16px; font-weight: bold;">
                                        <i class="fas fa-map-marker-alt mr-1"></i> Nombre del Área Protegida
                                    </label>

                                    <input type="text" name="area" id="area"
                                        class="form-control @error('area') is-invalid @enderror"
                                        value="{{ old('area', $area->area) }}"
                                        placeholder="Escriba el nombre del área protegida..." required>
                                    @error('area')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="descripcion" style="font-size: 16px; font-weight: bold;">
                                        <i class="fas fa-info-circle mr-1"></i> Descripción Detallada
                                    </label>

                                    <textarea name="descripcion" id="descripcion" class="form-control @error('descripcion') is-invalid @enderror"
                                        style="height: 150px;" placeholder="Escribe una descripción del área protegida...">{{ old('descripcion', $area->descripcion) }}
                                </textarea>

                                    @error('descripcion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-start mt-4">
                                    <button type="submit" class="btn btn-primary btn-icon icon-left btn-action-custom">
                                        <i class="fas fa-sync-alt"></i> Actualizar Área Protegida
                                    </button>

                                    <a href="{{ route('areas.index') }}" class="btn btn-secondary btn-icon icon-left ml-2">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
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
        /* Estilo para el botón de acción (Azul Sólido) */
        .btn-action-custom {
            background-color: #2f55d4 !important;
            border-color: #2f55d4 !important;
            color: #ffffff !important;
            box-shadow: none !important;
            border-radius: 50px;
            padding: 0.55rem 1.5rem;
            font-weight: 600;
            transition: opacity 0.3s ease;
        }

        /* Estados Hover / Focus / Active */
        .btn-action-custom:hover,
        .btn-action-custom:focus,
        .btn-action-custom:active {
            background-color: #2f55d4 !important;
            border-color: #2f55d4 !important;
            color: #ffffff !important;
            box-shadow: none !important;
            outline: none !important;
            opacity: 0.9;
            transform: none !important;
        }

        /* Estilo de la Tarjeta */
        .custom-card-form {
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        /* Estilo del Header de la Tarjeta */
        .custom-card-header {
            /* Se mantiene el degradado para consistencia */
            background: linear-gradient(90deg, #6777ef 0%, #a9b5f5 100%);
            color: #ffffff !important;
            border-radius: 12px 12px 0 0 !important;
            padding: 1rem 1.5rem;
        }

        .custom-card-header h4 {
            color: #ffffff !important;
            font-weight: 600;
            margin-bottom: 0;
        }

        /* Estilo de las Etiquetas de Formulario */
        .col-form-label {
            font-weight: 600;
            color: #495057;
        }

        /* Etiqueta requerida */
        .required-label:after {
            content: " *";
            color: #dc3545;
            font-weight: bold;
        }

        /* Estilo de los Inputs */
        .form-control:focus {
            border-color: #6777ef;
            box-shadow: 0 0 0 0.2rem rgba(103, 119, 239, 0.25);
        }

        /* Botones */
        .btn-primary {
            background-color: #6777ef;
            border-color: #6777ef;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #475aeb;
            border-color: #475aeb;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
    </style>
@endpush
