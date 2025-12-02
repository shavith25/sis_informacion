@extends('layouts.app')

@section('title', 'Gestión de Áreas')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"><i class="fas fa-map-pin mr-2"></i> Gestión de Municipios</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('home') }}">Inicio</a></div>
                <div class="breadcrumb-item">Municipios</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-list mr-2"></i> Listado de Municipios</h4>
                            <div class="card-header-action d-flex" style="gap:10px; align-items:center;">
                                <input type="text" id="buscador" class="form-control" placeholder="Buscar municipios..."
                                    style="width: 250px;">

                                <div class="card-header-action">
                                    <a href="{{ route('limites.municipios.create') }}" class="btn btn-primary btn-create">
                                        <i class="fas fa-plus mr-1"></i> Nuevo Municipio
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Alertas -->
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible show fade">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert">
                                            <span>&times;</span>
                                        </button>
                                        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                                    </div>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible show fade">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert">
                                            <span>&times;</span>
                                        </button>
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <ul class="mb-0 pl-3">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            <div class="table-responsive" style="height: calc(100vh - 450px)">
                                <table class="table table-striped table-hover" id="table-areas">
                                    <thead>
                                        <tr class="bg-light text-white">
                                            <th
                                                style="position: sticky; top: 0; background: rgb(211, 211, 211); z-index: 10;">
                                                Id</th>
                                            <th
                                                style="position: sticky; top: 0; background: rgb(211, 211, 211); z-index: 10;">
                                                Provincia</th>
                                            <th
                                                style="position: sticky; top: 0; background: rgb(211, 211, 211); z-index: 10;">
                                                Nombre</th>
                                            <th
                                                style="position: sticky; top: 0; background: rgb(211, 211, 211); z-index: 10;">
                                                Descripción</th>
                                            <th
                                                style="position: sticky; top: 0; background: rgb(211, 211, 211); z-index: 10;">
                                                Geometria</th>
                                            <th
                                                style="position: sticky; top: 0; background: rgb(211, 211, 211); z-index: 10;">
                                                Tipo Geometria</th>
                                            <th
                                                style="position: sticky; top: 0; background: rgb(211, 211, 211); z-index: 10;">
                                                Imagen</th>
                                            <th style="position: sticky; top: 0; background: rgb(211, 211, 211); z-index: 10;"
                                                class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($municipios as $municipio)
                                            <tr>
                                                <td>{{ $municipio->id }}</td>
                                                <td>{{ $municipio->provincia?->nombre }}</td>
                                                <td>{{ $municipio->nombre }}</td>
                                                <td>{{ $municipio->descripcion ?? 'N/A' }}</td>
                                                <td>{{ substr(json_encode($municipio->geometria), 0, 60) }}...</td>
                                                <td>{{ $municipio->tipo_geometria }}</td>
                                                <td>
                                                    @if ($municipio->media->count())
                                                        <img src="{{ asset('storage/' . $municipio->media->first()->archivo) }}"
                                                            class="img-thumbnail"
                                                            style="width: 80px; height: 80px; object-fit: cover;"
                                                            alt="Imagen">
                                                    @else
                                                        Sin imagen
                                                    @endif

                                                </td>

                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center" style="gap: 5px;">
                                                        <a href="{{ route('limites.municipios.edit', $municipio->id) }}"
                                                            class="btn btn-sm btn-warning">
                                                            <i class="fas fa-edit"> Editar</i>
                                                        </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">No hay municipios registrados.</td>
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
@endsection

@push('css')
    <style>
        .btn-create {
            background-color: #2f55d4 !important;
            border-color: #2f55d4 !important;
            color: #ffffff !important;
            border-radius: 50px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            box-shadow: none !important;
            text-decoration: none !important;
            transition: opacity 0.3s ease;
        }

        /* Estados interactivos (Hover, Focus, Active) */
        .btn-create:hover,
        .btn-create:focus,
        .btn-create:active,
        .btn-create:visited {
            background-color: #2f55d4 !important;
            border-color: #2f55d4 !important;
            color: #ffffff !important;
            box-shadow: none !important;
            outline: none !important;
            text-decoration: none !important;
            opacity: 0.9;
            transform: none !important;
        }

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
    </style>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const buscador = document.getElementById("buscador");
            const filas = document.querySelectorAll("#table-areas tbody tr");

            if (buscador) {
                buscador.addEventListener("keyup", function() {
                    const texto = this.value.toLowerCase();

                    filas.forEach(fila => {
                        if (fila.querySelectorAll('td').length > 1) {
                            const contenidoFila = fila.textContent.toLowerCase();
                            fila.style.display = contenidoFila.includes(texto) ? "" : "none";
                        }
                    });
                });
            }
        });

        function confirmarEliminar(id, tipo) {
            Swal.fire({
                title: `¿Eliminar ${tipo}?`,
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-delete-' + id).submit();
                }
            });
        }
    </script>
@endpush
