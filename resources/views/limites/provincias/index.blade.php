@extends('layouts.app')

@section('title', 'Gestión de Áreas')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading"><i class="fas fa-city mr-2"></i> Gestión de Provincias</h3>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Inicio</a></div>
            <div class="breadcrumb-item">Provincias</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-list mr-2"></i> Listado de Provincias</h4>
                        <div class="card-header-action d-flex" style="gap:10px; align-items:center;">
                            <input type="text" id="buscador" class="form-control" 
                                placeholder="Buscar..." 
                                style="width: 250px;">

                            <a href="{{ route('limites.provincias.create') }}" class="btn btn-primary p-2" id="btn-nueva-provincia">
                                <i class="fas fa-plus"></i> Nueva Provincia
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert">
                                        <span>&times;</span>
                                    </button>
                                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                                </div>
                            </div>
                        @endif

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

                    <div class="table-responsive" style="height: calc(100vh - 375px)">
                            <table class="table table-striped table-hover" id="table-areas">
                                <thead>
                                    <tr class="bg-light text-white">
                                        <th style="position: sticky; top: 0; background: rgb(211, 211, 211); z-index: 10;">Id</th>
                                        <th style="position: sticky; top: 0; background: rgb(211, 211, 211); z-index: 10;">Departamento</th>
                                        <th style="position: sticky; top: 0; background: rgb(211, 211, 211); z-index: 10;">Nombre</th>
                                        <th style="position: sticky; top: 0; background: rgb(211, 211, 211); z-index: 10;">Descripción</th>
                                        <th style="position: sticky; top: 0; background: rgb(211, 211, 211); z-index: 10;">Geometria</th>
                                        <th style="position: sticky; top: 0; background: rgb(211, 211, 211); z-index: 10;">Tipo Geometria</th>
                                        <th style="position: sticky; top: 0; background: rgb(211, 211, 211); z-index: 10;">Imagen</th>
                                        <th style="position: sticky; top: 0; background: rgb(211, 211, 211); z-index: 10;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($provincias as $provincia)
                                        <tr>
                                            <td>{{ $provincia->id }}</td>
                                            <td>{{ $provincia->departamento?->nombre }}</td>
                                            <td>{{ $provincia->nombre }}</td>
                                            <td>{{ $provincia->descripcion ?? 'N/A' }}</td>
                                        <td>{{ substr(json_encode($provincia->geometria), 0, 60) }}...</td>
                                            <td>{{ $provincia->tipo_geometria }}</td>
                                            <td>
                                                @if($provincia->media->count())
                                                    <img src="{{ asset('storage/' . $provincia->media->first()->archivo) }}"
                                                        class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;"
                                                        alt="Imagen">
                                                @else
                                                    Sin imagen
                                                @endif

                                            </td>
                                            <td class="text-center" style="display:flex;align-items: center;gap:5px;">
                                                <a href="{{ route('limites.provincias.edit', $provincia) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"> Editar</i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No hay provincias registradas.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>


                        <div class="float-right mt-3">
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const buscador = document.getElementById("buscador");
    const filas = document.querySelectorAll("#table-areas tbody tr");

    buscador.addEventListener("keyup", function() {
        const texto = this.value.toLowerCase();

        filas.forEach(fila => {
            const contenidoFila = fila.textContent.toLowerCase();
            fila.style.display = contenidoFila.includes(texto) ? "" : "none";
        });
    });
});
</script>
@endsection

@push('css')
<style>
    .table th {
        font-weight: 700;
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

    /* ESTILO PARA EL BOTÓN NUEVA PROVINCIA */
    #btn-nueva-provincia {
        background-color: #0d6efd !important; 
        border-color: #0d6efd !important;
        color: white !important;
        box-shadow: none !important;
        background-image: none !important;
        text-decoration: none !important; 
    }

    /* Eliminar el fondo blanco al pasar el mouse (Hover) */
    #btn-nueva-provincia:hover, 
    #btn-nueva-provincia:focus, 
    #btn-nueva-provincia:active {
        background-color: #0b5ed7 !important; 
        border-color: #0a58ca !important;
        color: white !important;
        opacity: 1 !important;
    }
</style>
@endpush

