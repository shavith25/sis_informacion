@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"><i class="fas fa-landmark mr-2"></i> Gestión de Marco Normativo</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('home') }}">Inicio</a></div>
                <div class="breadcrumb-item">Documentos</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-balance-scale text-primary mr-2"></i> Listado de Leyes y Decretos</h4>
                            <div class="card-header-action">
                                <a href="{{ route('documentos.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-1"></i> Crear Nuevo Documento
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
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
                            <div class="row" style="height: calc(100vh - 425px);">
                                @foreach ($documentos as $documento)
                                    <div class="col-md-6 col-lg-3 mb-3" style="height: calc(100vh - 400px);">
                                        <div class="card document-card h-100">
                                            <div class="card-header bg-primary text-white text-center py-3">
                                                <div class="d-flex flex-column align-items-center">
                                                    <div class="rounded-circle overflow-hidden mb-2 d-flex justify-content-center align-items-center"
                                                        style="width: 60px; height: 60px; border: 3px solid white;">
                                                        @if ($documento->icono)
                                                            <img src="{{ Storage::url($documento->icono) }}" alt="Icono"
                                                                style="max-height: 54px; max-width: 54px; object-fit: contain;">
                                                        @else
                                                            <img src="{{ asset('img/logogaceta.jpg') }}" alt="Logo Gaceta"
                                                                style="max-height: 54px; max-width: 54px; object-fit: contain;">
                                                        @endif
                                                    </div>
                                                    <h6 class="mb-0">{{ $documento->titulo }}</h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text"><b>Resumen:</b>
                                                    {{ Str::limit($documento->resumen, 100, '...') }}
                                                </p>
                                                <div class="mt-3">
                                                    <p class="text-muted small mb-2">
                                                        <i class="fas fa-calendar-alt mr-1"></i>
                                                        Publicado:
                                                        {{ \Carbon\Carbon::parse($documento->fecha_publicacion)->format('d/m/Y') }}
                                                    </p>
                                                    <p class="text-muted small mb-2">
                                                        <i class="fas fa-file-signature mr-1"></i>
                                                        Número: {{ $documento->numero_documento }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-transparent">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge badge-light">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        {{ \Carbon\Carbon::parse($documento->fecha_emision)->format('d/m/Y') }}
                                                    </span>
                                                    <div>
                                                        <a href="{{ route('documentos.edit', $documento) }}"
                                                            class="btn btn-icon btn-sm btn-info mr-1" data-toggle="tooltip"
                                                            title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        @if ($documento->pdf)
                                                            <a href="{{ Storage::url($documento->pdf) }}" target="_blank"
                                                                class="btn btn-icon btn-sm btn-primary"
                                                                data-toggle="tooltip" title="Descargar PDF">
                                                                <i class="fas fa-download"></i>
                                                            </a>

                                                            <form
                                                                action="{{ route('documentos.destroy', $documento) }}"
                                                                method="POST" class="d-inline"
                                                                id="form-delete-{{ $documento->id }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button"
                                                                    class="btn btn-icon btn-sm btn-danger ml-1"
                                                                    data-toggle="tooltip" title="Eliminar"
                                                                    onclick="confirmarEliminar({{ $documento->id }})">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-center mt-4">
                                {!! $documentos->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .btn-primary {
            background-color: #2f55d4 !important;
            border-color: #2f55d4 !important;
            box-shadow: none !important;
            color: #fff !important;
            text-decoration: none !important;
        }

        .btn-primary:hover {
            background-color: #2442a8 !important;
            border-color: #2442a8 !important;
            box-shadow: none !important;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .document-card .card-header {
            background: linear-gradient(135deg, #6777ef 0%, #6777ef 100%);
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }

        .document-card .card-body {
            padding: 1.25rem;
        }

        .document-card .card-footer {
            padding: 0.75rem 1.25rem;
            border-top: 1px solid rgba(0, 0, 0, 0.03);
        }

        .object-fit-cover {
            object-fit: cover;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .form-control:focus,
        .custom-select:focus {
            border-color: #6777ef;
            box-shadow: 0 0 0 0.2rem rgba(103, 119, 239, 0.25);
        }
    </style>
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });

        function confirmarEliminar(documentoId) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esta acción!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, ¡eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-delete-' + documentoId).submit();
                }
            });
        }
    </script>
@endpush
