@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading text-center"><i class="fas fa-users-cog mr-2"></i> Gestión de Roles</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('home') }}">Inicio</a></div>
                <div class="breadcrumb-item">Roles</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-users-cog mr-2"></i> Listado de Roles</h4>
                            <div class="card-header-action">
                                <a href="{{ route('roles.create') }}" class="btn btn-primary btn-create">
                                    <i class="fas fa-plus mr-1"></i> Nuevo Rol
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
                                        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                                    </div>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible show fade">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert">
                                            <span>&times;</span>
                                        </button>
                                        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                                    </div>
                                </div>
                            @endif

                            @if ($errors->has('name'))
                                <div class="alert alert-danger alert-dismissible show fade">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert">
                                            <span>&times;</span>
                                        </button>
                                        <i class="fas fa-exclamation-circle mr-2"></i> {{ $errors->first('name') }}
                                    </div>
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="table-roles">
                                    <thead>
                                        <tr class="bg-light text-white">
                                            <th width="70%">Nombre del Rol</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($roles as $role)
                                            <tr>
                                                <td>
                                                    <span class="badge badge-light" style="font-size: 1em;">
                                                        <i class="fas fa-user-tag mr-2"></i> {{ $role->name }}
                                                    </span>
                                                </td>

                                                <td class="text-center">
                                                    <a href="{{ route('roles.edit', $role->id) }}"
                                                        class="btn btn-icon btn-sm btn-action-blue mr-1" data-toggle="tooltip"
                                                        title="Editar rol">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <button class="btn btn-icon btn-sm btn-danger" data-toggle="tooltip"
                                                        title="Eliminar rol"
                                                        onclick="confirmDelete('{{ $role->id }}', '{{ $role->name }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    
                                                    <form id="delete-form-{{ $role->id }}"
                                                        action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                                        style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="float-right mt-3">
                                {!! $roles->links() !!}
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
        transition: opacity 0.3s ease;
    }

    .btn-create:hover, .btn-create:focus, .btn-create:active {
        background-color: #2f55d4 !important;
        color: #ffffff !important;
        box-shadow: none !important;
        opacity: 0.9;
    }

    /* --- Botones de Acción en la Tabla (Circulares) --- */
    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50% !important; /* Círculo perfecto */
        border: none !important;
        box-shadow: none !important;
        transition: transform 0.2s ease, opacity 0.2s;
    }
    
    /* Estilo Azul (Editar) */
    .btn-action-blue {
        background-color: #2f55d4 !important;
        color: #ffffff !important;
    }
    
    .btn-action-blue:hover {
        background-color: #2f55d4 !important;
        color: #ffffff !important;
        opacity: 0.85;
        transform: translateY(-2px); /* Pequeño efecto de elevación */
    }

    .btn-action-red {
        background-color: #fc544b !important; /* Rojo suave */
        color: #ffffff !important;
    }

    .btn-action-red:hover {
        background-color: #fc544b !important;
        color: #ffffff !important;
        opacity: 0.85;
        transform: translateY(-2px);
    }

    .btn-action-red {
        background-color: #2f55d4 !important; 
        color: #ffffff !important;
    }

    .btn-action-red:hover {
        background-color: #2f55d4 !important;
        opacity: 0.9;
    }

    /* Otros estilos de la tabla */
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }
    .badge-light {
        background-color: #f8f9fa;
        color: #444;
        font-weight: 500;
        padding: 0.5em 1em;
        border: 1px solid #eee;
    }
    </style>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });

        function confirmDelete(id, name) {
            console.log('entra')
            Swal.fire({
                title: '¿Eliminar Rol?',
                html: `Estás a punto de eliminar el rol <strong>${name}</strong>. ¿Deseas continuar?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6777ef',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });

        }
    </script>
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: '{{ session('success') }}',
            });
        </script>
    @endif
@endpush
