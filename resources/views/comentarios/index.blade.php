@extends('layouts.app')

@section('title', 'Revisar Comentarios')

@section('content')
    <section class="section">
        {{-- EL HEADER SE QUEDA QUIETO PORQUE NO HAY SCROLL GENERAL --}}
        <div class="section-header">
            <h3 class="page__heading"><i class="fas fa-comments mr-2"></i> Revisar Comentarios</h3>
        </div>
        
        <div class="section-body">
            <div class="row">
                {{-- Columna 1: Pendientes --}}
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm full-height-card">
                        <div class="card-header bg-light">
                            <h4 class="text-primary">
                                <i class="fas fa-tasks mr-2"></i> Comentarios Pendientes ({{ $comentariosPendientes->count() }})
                            </h4>
                        </div>
                        
                        {{-- ÁREA DE SCROLL INTERNO --}}
                        <div class="card-body custom-scroll-area">
                            @forelse($comentariosPendientes as $comentario)
                                <div class="comment-item border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong><i class="fas fa-user text-secondary mr-1"></i> {{ $comentario->nombre }}</strong>
                                        <small class="text-muted"><i class="fas fa-calendar-alt mr-1"></i> {{ $comentario->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <p class="mb-3 text-justify">{{ $comentario->comentario }}</p>

                                    <div class="d-flex justify-content-end">
                                        <form action="{{ route('admin.comentarios.approve', $comentario->id) }}" method="POST" class="d-inline mr-2">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-check mr-1"></i> Aprobar
                                            </button>
                                        </form>

                                        <form id="delete-pending-{{ $comentario->id }}" action="{{ route('admin.comentarios.destroy', $comentario->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger btn-delete" data-form-id="delete-pending-{{ $comentario->id }}">
                                                <i class="fas fa-trash mr-1"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                                    <i class="fas fa-clipboard-check fa-3x mb-3 text-success opacity-50"></i>
                                    <p class="mb-0">¡Todo limpio! No hay comentarios pendientes.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Columna 2: Aprobados --}}
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm full-height-card">
                        <div class="card-header bg-light">
                            <h4 class="text-success">
                                <i class="fas fa-check-circle mr-2"></i> Comentarios Aprobados ({{ $comentariosAprobados->count() }})
                            </h4>
                        </div>
                        
                        {{-- ÁREA DE SCROLL INTERNO --}}
                        <div class="card-body custom-scroll-area">
                            @forelse($comentariosAprobados as $comentario)
                                <div class="comment-item border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong><i class="fas fa-user text-secondary mr-1"></i> {{ $comentario->nombre }}</strong>
                                        <small class="text-muted"><i class="fas fa-calendar-alt mr-1"></i> {{ $comentario->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <p class="mb-3 text-justify text-muted">{{ $comentario->comentario }}</p>

                                    <div class="d-flex justify-content-end">
                                        <form id="delete-approved-{{ $comentario->id }}" action="{{ route('admin.comentarios.destroy', $comentario->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-form-id="delete-approved-{{ $comentario->id }}">
                                                <i class="fas fa-trash mr-1"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                                    <i class="fas fa-folder-open fa-3x mb-3 opacity-50"></i>
                                    <p class="mb-0">No hay comentarios aprobados aún.</p>
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
        height: calc(100vh - 340px); 
        overflow-y: auto; 
        padding-right: 10px;
    }

    .full-height-card {
        height: 100%;
        overflow: hidden; 
        border: none;
        border-radius: 8px;
    }

    .custom-scroll-area::-webkit-scrollbar { width: 6px; }
    .custom-scroll-area::-webkit-scrollbar-track { background: #f1f1f1; }
    .custom-scroll-area::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
    .custom-scroll-area::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
    
    .card-header {
        border-bottom: 1px solid #eee;
        background-color: #fff;
    }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
</script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const deleteButtons = document.querySelectorAll('.btn-delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                const formId = event.currentTarget.getAttribute('data-form-id');
                
                Swal.fire({
                    title: '¿Eliminar comentario?',
                    text: "Esta acción es irreversible.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
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