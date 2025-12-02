@extends('layouts.app')

@section('title', 'Revisar Reportes Ambientales')

@section('content')
    <section class="section">
        {{-- HEADER ESTÁTICO --}}
        <div class="section-header">
            <h3 class="page__heading"><i class="fas fa-leaf text-success mr-2"></i> Revisar Reportes Ambientales</h3>
        </div>

        <div class="section-body">
            <div class="row">
                
                {{-- COLUMNA 1: PENDIENTES --}}
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card shadow-sm full-height-card">
                        <div class="card-header bg-light border-bottom">
                            <h4 class="text-danger mb-0">
                                <i class="fas fa-exclamation-circle mr-2"></i> 
                                Pendientes de Aprobación ({{ $reportesPendientes->count() }})
                            </h4>
                        </div>
                        
                        {{-- BODY CON SCROLL INDEPENDIENTE --}}
                        <div class="card-body custom-scroll-area">
                            @forelse ($reportesPendientes as $reporte)
                                <div class="report-item border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong><i class="fas fa-user text-secondary mr-1"></i> {{ $reporte->nombre }}</strong>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt mr-1"></i> 
                                            {{ $reporte->created_at->format('d/m/Y') }}
                                        </small>
                                    </div>
                                    
                                    <h6 class="font-weight-bold text-dark">{{ $reporte->titulo }}</h6>
                                    <p class="mb-3 text-muted text-justify">{{ $reporte->contenido }}</p>
                                    
                                    <div class="d-flex justify-content-end gap-2">
                                        @can('aprobar-reportes')
                                            <form action="{{ route('admin.reportes_ambientales.approve', $reporte) }}" method="POST" class="mr-2">
                                                @csrf 
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success btn-sm shadow-none">
                                                    <i class="fas fa-check mr-1"></i> Aprobar
                                                </button>
                                            </form>
                                        @endcan

                                        @can('eliminar-reportes')
                                            <form id="delete-form-pending-{{ $reporte->id }}" action="{{ route('admin.reportes_ambientales.destroy', $reporte) }}" method="POST">
                                                @csrf 
                                                @method('DELETE')
                                                <button type="button" class="btn btn-outline-danger btn-sm btn-delete shadow-none" data-form-id="delete-form-pending-{{ $reporte->id }}">
                                                    <i class="fas fa-trash-alt mr-1"></i> Eliminar
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            @empty
                                <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                                    <i class="fas fa-check-circle fa-3x mb-3 opacity-50 text-success"></i>
                                    <p class="mb-0">¡Todo al día! No hay reportes pendientes.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- COLUMNA 2: APROBADOS --}}
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card shadow-sm full-height-card">
                        <div class="card-header bg-light border-bottom">
                            <h4 class="text-success mb-0">
                                <i class="fas fa-check-double mr-2"></i> 
                                Aprobados y Publicados ({{ $reportesAprobados->count() }})
                            </h4>
                        </div>
                        
                        {{-- BODY CON SCROLL INDEPENDIENTE --}}
                        <div class="card-body custom-scroll-area">
                            @forelse ($reportesAprobados as $reporte)
                                <div class="report-item border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong class="text-success"><i class="fas fa-user-check mr-1"></i> {{ $reporte->nombre }}</strong>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-check mr-1"></i> 
                                            Aprobado: {{ $reporte->updated_at->format('d/m/Y') }}
                                        </small>
                                    </div>
                                    
                                    <h6 class="font-weight-bold text-dark">{{ $reporte->titulo }}</h6>
                                    <p class="mb-3 text-muted text-justify">{{ $reporte->contenido }}</p>
                                    
                                    <div class="d-flex justify-content-end">
                                        @can('eliminar-reportes')
                                            <form id="delete-form-approved-{{ $reporte->id }}" action="{{ route('admin.reportes_ambientales.destroy', $reporte) }}" method="POST">
                                                @csrf 
                                                @method('DELETE')
                                                <button type="button" class="btn btn-outline-danger btn-sm btn-delete shadow-none" data-form-id="delete-form-approved-{{ $reporte->id }}">
                                                    <i class="fas fa-trash-alt mr-1"></i> Eliminar
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            @empty
                                <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                                    <i class="fas fa-file-alt fa-3x mb-3 opacity-50"></i>
                                    <p class="mb-0">No hay reportes publicados aún.</p>
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

    .custom-scroll-area::-webkit-scrollbar { width: 6px; }
    .custom-scroll-area::-webkit-scrollbar-track { background: #f8f9fa; }
    .custom-scroll-area::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 10px; }
    .custom-scroll-area::-webkit-scrollbar-thumb:hover { background: #a0aec0; }

    .report-item:last-child {
        border-bottom: none !important;
        margin-bottom: 0 !important;
        padding-bottom: 0;
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
                title: '{{ session("success") }}',
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
                        text: "¡Esta acción eliminará el reporte del sistema y del sitio web!",
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