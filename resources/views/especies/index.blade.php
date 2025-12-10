@extends('layouts.app')

@section('title', 'Gestión de Especies')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading"><i class="fas fa-leaf mr-2"></i> Gestión de Especies</h3>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Inicio</a></div>
            <div class="breadcrumb-item">Especies</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-list mr-2"></i> Listado de Especies</h4>
                        <div class="card-header-action">
                            <a href="{{ route('especies.create') }}" class="p-2 icon-left btn-primary">
                                <i class="fas fa-plus"></i> Nueva Especie
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert">
                                        <span>&times;</span>
                                    </button>
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <ul class="mb-0 pl-3">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <div class="table-responsive" style="height: calc(100vh - 500px)">
                            <table class="table table-striped table-hover" id="table-areas">
                                <thead>
                                    <tr class="bg-light text-white">
                                        <th style="position: sticky; top: 0; background: rgb(211, 211, 211); z-index: 10;">Título</th>
                                        <th style="position: sticky; top: 0; background: rgb(211, 211, 211); z-index: 10;">Tipo</th>
                                        <th style="position: sticky; top: 0; background: rgb(211, 211, 211); z-index: 10;">Descripción</th>
                                        <th style="position: sticky; top: 0; background: rgb(211, 211, 211); z-index: 10;">Imagen</th>
                                        <th style="position: sticky; top: 0; background: rgb(211, 211, 211); z-index: 10;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                            <tbody>
                                    @forelse($especies as $especie)
                                        <tr>
                                            <td>{!! $especie->titulo !!}</td>
                                            <td>
                                                @if($especie->tipo === 'emblematica')
                                                    <span class="badge bg-success text-white">Emblemática</span>
                                                @elseif($especie->tipo === 'vulnerable')
                                                    <span class="badge bg-warning text-dark">Vulnerable</span>
                                                @endif
                                            </td>
                                            <td>{{ $especie->descripcion }}</td>
                                            <td>
                                                @if($especie->imagenes->count())
                                                    <img src="{{ asset('storage/' . $especie->imagenes->first()->url) }}"
                                                        class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;"
                                                        alt="Imagen Especie">
                                                        
                                                @else
                                                    <span class="text-muted">Sin imagen</span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <div style="display: flex; justify-content: center; align-items: center; gap: 10px;">
                                                    <a href="{{ route('especies.edit', $especie) }}" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"> Editar</i>
                                                    </a>
                                                    <form action="{{ route('especies.destroy', $especie) }}"
                                                        method="POST" class="form-delete" style="margin: 0;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                                onclick="confirmarEliminar(this, {{ $especie->id }})">
                                                            <i class="fas fa-trash"> Borrar</i>
                                                        </button>
                                                    </form>
                                                </div>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No hay especies registradas.</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                            <script>
                                function confirmarEliminar(button, id) {
                                    Swal.fire({
                                        title: '¿Eliminar especie?',
                                        text: "Esta acción no se puede deshacer.",
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#e3342f',
                                        cancelButtonColor: '#6c757d',
                                        confirmButtonText: 'Sí, eliminar',
                                        cancelButtonText: 'Cancelar'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            button.closest('form').submit();
                                        }
                                    });
                                }
                                </script>
                        </div>


                        <div class="float-right mt-3">
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('css')
<style>
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }

    .empty-state {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 30px;
    }

    .empty-state-icon {
        font-size: 3rem;
        color: #6777ef;
        margin-bottom: 1rem;
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

    button[disabled] {
    opacity: 0.5;           
    cursor: not-allowed !important;      
    pointer-events: none;     
    }
    
    button[disabled]:hover {
        background-color: inherit;
        color: inherit;
    }

    /* Estilo para mantener el color azul del botón al hacer clic */
    .btn-primary {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
        color: white !important;
    }

    .btn-primary:hover,
    .btn-primary:focus,
    .btn-primary:active,
    .btn-primary.active {
        background-color: #0b5ed7 !important;
        border-color: #0b5ed7 !important;
        color: white !important;
        box-shadow: none !important;
        outline: none !important;
    }

    .btn-primary:active {
        transform: none !important;
    }
</style>
@endpush
