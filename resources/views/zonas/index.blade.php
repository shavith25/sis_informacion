@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">
            <i class="fas fa-map-marked-alt mr-1"></i> Gestión de Zonas
        </h3>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></div>
            <div class="breadcrumb-item active">Zonas</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <a class="btn btn-primary btn-lg shadow-sm" href="{{ route('zonas.create') }}" style="background-color: #6777ef; border-color: #6777ef; color: #fff;">
                    <i class="fas fa-plus-circle"></i> Registrar Nueva Zona
                </a>

                @if(request()->has('search'))
                <a href="{{ route('zonas.index') }}" class="btn btn-outline-danger">
                    <i class="fas fa-times"></i> Limpiar Búsqueda
                </a>
                @endif
            </div>
        </div>

        <div class="row zona-card-list" style="max-height: calc(100vh - 250px); overflow-y: auto; padding: 5px;">
            @forelse ($zonas as $zona)
                
                {{-- ENCRIPTACIÓN MANUAL SEGURA --}}
                @php
                    try {
                        $idEncriptado = Illuminate\Support\Facades\Crypt::encryptString($zona->id);
                    } catch (\Exception $e) {
                        $idEncriptado = $zona->id; // Fallback
                    }
                @endphp

                <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm custom-zona-card">
                        
                        <div class="card-img-top-container bg-light">
                            @if($zona->imagenes->isNotEmpty())
                                <div id="carousel-{{ $zona->id }}" class="carousel slide h-100" data-ride="carousel">
                                    <div class="carousel-inner h-100">
                                        @foreach($zona->imagenes as $index => $imagen)
                                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }} h-100">
                                                <img src="{{ asset('storage/' . $imagen->url) }}"
                                                    class="d-block w-100 h-100"
                                                    style="object-fit: cover;"
                                                    alt="Imagen de {{ $zona->nombre }}"> 
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    @if($zona->imagenes->count() > 1)
                                        <a class="carousel-control-prev" href="#carousel-{{ $zona->id }}" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Anterior</span>
                                        </a>
                                        <a class="carousel-control-next" href="#carousel-{{ $zona->id }}" role="button" data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Siguiente</span>
                                        </a>
                                    @endif
                                </div>
                            @elseif($zona->videos->isNotEmpty())
                                <video class="w-100 h-100" style="object-fit: cover;" controls>
                                    <source src="{{ asset('storage/' . $zona->videos->first()->url) }}" type="video/mp4">
                                    Tu navegador no soporta videos.
                                </video>
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                    <i class="fas fa-image fa-3x"></i>
                                </div>
                            @endif
                        </div>

                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title font-weight-bold mb-0 text-primary text-uppercase">{{ $zona->nombre }}</h5>
                                <span class="badge badge-{{ $zona->estado ? 'success' : 'secondary' }}">
                                    {{ $zona->estado ? 'Activa' : 'Inactiva' }}
                                </span>
                            </div>

                            <p class="text-muted small mb-2">
                                <i class="fas fa-map-pin mr-1"></i> {{ $zona->area->area ?? 'Sin Área Protegida' }}
                            </p>

                            <p class="card-text text-dark mb-3" style="font-size: 0.9rem;">
                                {{ Str::limit($zona->descripcion ?? 'Sin descripción.', 100) }}
                            </p>

                            <div class="mt-auto d-flex justify-content-between border-top pt-3">
                                {{-- DETALLES --}}
                                <a href="{{ route('zonas.show', $idEncriptado) }}" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-eye"></i> Detalles
                                </a>
                                
                                <div>
                                    {{-- EDITAR --}}
                                    <a href="{{ route('zonas.edit', $idEncriptado) }}" class="btn btn-warning btn-sm text-white" title="Editar">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    
                                    {{-- CAMBIAR ESTADO --}}
                                    <button type="button" 
                                            class="btn btn-{{ $zona->estado ? 'danger' : 'success' }} btn-sm ml-1"
                                            onclick="confirmarEstado('{{ $idEncriptado }}', {{ $zona->estado }})" {{-- Pasamos ID encriptado --}}
                                            title="{{ $zona->estado ? 'Desactivar' : 'Activar' }}">
                                        <i class="fas {{ $zona->estado ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i> 
                                        {{ $zona->estado ? 'Desactivar' : 'Activar' }}
                                    </button>

                                    {{-- FORMULARIOS OCULTOS --}}
                                    {{-- Usamos un ID único basado en el loop para el selector JS, pero la ruta tiene el ID encriptado --}}
                                    <form id="form-status-{{ $loop->index }}" action="{{ route('zonas.change-status', $idEncriptado) }}" method="POST" style="display: none;">
                                        @csrf @method('PATCH')
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        No hay zonas registradas. ¡Crea una nueva!
                    </div>
                </div>
            @endforelse
        </div>
        
        @if(method_exists($zonas, 'links'))
            <div class="d-flex justify-content-center mt-3">
                {{ $zonas->links() }}
            </div>
        @endif
    </div>
</section>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmarEstado(idEncriptado, estadoActual) {
        // Encontramos el índice basado en el botón clickeado o buscamos el formulario correcto
        // Para simplificar, buscamos el formulario cuya acción termine en este ID encriptado
        const form = document.querySelector(`form[action*="${idEncriptado}"]`);
        
        const accion = estadoActual ? 'desactivar' : 'activar';
        const colorBtn = estadoActual ? '#d33' : '#28a745';
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: `Vas a ${accion} esta zona.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: colorBtn,
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, proceder',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed && form) {
                form.submit();
            }
        });
    }
</script>
@endpush

@push('css')
<style>
    .btn-primary {
        background-color: #6777ef !important;
        border-color: #6777ef !important;
        color: #fff !important;
        box-shadow: 0 2px 6px #acb5f6;
    }
    .btn-primary:hover {
        background-color: #394eea !important;
        box-shadow: 0 2px 6px #828db0;
    }
    .custom-zona-card {
        border: none;
        border-radius: 8px;
        overflow: hidden;
    }
    .custom-zona-card:hover {
        transform: translateY(-5px);
        transition: transform 0.3s;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .card-img-top-container {
        height: 200px;
        width: 100%;
        background-color: #e9ecef;
        position: relative;
    }
</style>
@endpush