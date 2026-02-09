@extends('layouts.app')

@section('title', 'Revisar Sugerencias')

@section('content')
    <section class="section">
        {{-- EL HEADER SE QUEDA QUIETO (No se mueve porque eliminamos el scroll de la página) --}}
        <div class="section-header">
            <h3 class="page__heading"><i class="fas fa-lightbulb text-primary mr-2"></i> Revisar Sugerencias</h3>
        </div>

        <div class="section-body">
            <div class="row">

                {{-- COLUMNA 1: PENDIENTES --}}
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card shadow-sm full-height-card">
                        <div class="card-header bg-light border-bottom">
                            <h4 class="text-primary">
                                <i class="fas fa-clock mr-2"></i> Pendientes de Aprobación
                                ({{ $sugerenciasPendientes->count() }})
                            </h4>
                        </div>
                        
                        {{-- AQUI APLICAMOS EL SCROLL INTERNO --}}
                        <div class="card-body custom-scroll-area">
                            @forelse ($sugerenciasPendientes as $sugerencia)
                                <div class="comment-item border-bottom pb-3 mb-3">
                                    <div class="px-3 py-2 border-top">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                Por: {{ $sugerencia->nombre }}
                                            </small>
                                            <small class="text-muted">
                                                <i class="far fa-calendar-alt mr-1"></i>
                                                Fecha: {{ $sugerencia->created_at->format('d/m/Y') }}
                                            </small>
                                        </div>
                                    </div>

                                    <h6 class="font-weight-bold text-dark mb-1">{{ $sugerencia->titulo }}</h6>
                                    <p class="mb-3 text-muted text-justify">{{ $sugerencia->contenido }}</p>

                                    <div class="d-flex justify-content-end gap-2">
                                        <form action="{{ route('admin.sugerencias.approve', $sugerencia->id) }}" method="POST" class="d-inline mr-2">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm shadow-none" title="Aprobar">
                                                <i class="fas fa-check mr-1"></i> Aprobar
                                            </button>
                                        </form>

                                        <form id="delete-pending-{{ $sugerencia->id }}" action="{{ route('admin.sugerencias.destroy', $sugerencia->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-delete shadow-none" data-form-id="delete-pending-{{ $sugerencia->id }}" title="Eliminar">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                                    <i class="fas fa-clipboard-check fa-3x mb-3 opacity-50 text-primary"></i>
                                    <p class="mb-0">No hay sugerencias pendientes.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- COLUMNA 2: APROBADAS --}}
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card shadow-sm full-height-card">
                        <div class="card-header bg-light border-bottom">
                            <h4 class="text-success">
                                <i class="fas fa-check-circle mr-2"></i> Aprobadas y Archivadas
                                ({{ $sugerenciasAprobadas->count() }})
                            </h4>
                        </div>
                        
                        {{-- AQUI APLICAMOS EL SCROLL INTERNO --}}
                        <div class="card-body custom-scroll-area">
                            @forelse ($sugerenciasAprobadas as $sugerencia)
                                <div class="comment-item border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="text-success font-weight-bold">
                                            <i class="fas fa-user-check mr-1"></i> {{ $sugerencia->nombre }}
                                        </div>
                                        <div class="text-muted small">
                                            <i class="far fa-calendar-check mr-1"></i>
                                            Fecha: {{ $sugerencia->created_at->format('d/m/Y') }}
                                        </div>
                                    </div>

                                    <h6 class="font-weight-bold text-dark mb-1">{{ $sugerencia->titulo }}</h6>
                                    <p class="mb-3 text-muted text-justify">{{ $sugerencia->contenido }}</p>

                                    <div class="d-flex justify-content-end">
                                        <form id="delete-approved-{{ $sugerencia->id }}" action="{{ route('admin.sugerencias.destroy', $sugerencia->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-delete shadow-none" data-form-id="delete-approved-{{ $sugerencia->id }}" title="Eliminar del historial">
                                                <i class="fas fa-trash-alt mr-1"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                                    <i class="fas fa-archive fa-3x mb-3 opacity-50 text-success"></i>
                                    <p class="mb-0">No hay sugerencias archivadas.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('css')
    <style>
        .custom-scroll-area {
            height: calc(100vh - 350px); 
            overflow-y: auto; 
            padding-right: 10px;
        }

        .full-height-card {
            height: 100%; 
            border: none;
            border-radius: 8px;
            overflow: hidden;
        }

        /* Estilos del Scrollbar */
        .custom-scroll-area::-webkit-scrollbar { width: 6px; }
        .custom-scroll-area::-webkit-scrollbar-track { background: #f8f9fa; }
        .custom-scroll-area::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 10px; }
        .custom-scroll-area::-webkit-scrollbar-thumb:hover { background: #a0aec0; }

        .comment-item:last-child {
            border-bottom: none !important;
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
        }
    </style>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const formId = this.getAttribute('data-form-id');

                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Esta acción no se puede deshacer.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#fc544b',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(formId).submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush