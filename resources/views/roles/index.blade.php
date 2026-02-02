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
                <div class="col-lg-8"> <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-list mr-2"></i> Listado de Roles</h4>
                            <div class="card-header-action">
                                <a href="{{ route('roles.create') }}" class="btn btn-create">
                                    <i class="fas fa-plus mr-1"></i> Nuevo Rol
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="table-roles">
                                    <thead style="background-color: #6777ef;">
                                        <tr class="text-white">
                                            <th width="70%" style="color:white;">Nombre del Rol</th>
                                            <th class="text-center" style="color:white;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($roles as $role)
                                            <tr>
                                                <td>
                                                    <span class="badge badge-light" style="font-size: 0.9em; border: 1px solid #eee;">
                                                        <i class="fas fa-user-tag mr-2 text-primary"></i> {{ $role->name }}
                                                    </span>
                                                </td>

                                                {{-- CORRECCIÓN 1: Usar Crypt para coincidir con el Controlador --}}
                                                @php
                                                    $idEncriptado = \Illuminate\Support\Facades\Crypt::encryptString($role->id);
                                                @endphp

                                                <td class="text-center">
                                                    {{-- Botón Editar --}}
                                                    <a href="{{ route('roles.edit', $idEncriptado) }}"
                                                        class="btn btn-icon btn-action-blue mr-1" data-toggle="tooltip"
                                                        title="Editar rol">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>

                                                    {{-- Botón Eliminar --}}
                                                    {{-- Nota: El ID del form usa el ID real para el JS, pero el ACTION usa el encriptado --}}
                                                    <button class="btn btn-icon btn-action-red" data-toggle="tooltip"
                                                        title="Eliminar rol"
                                                        onclick="confirmDelete('{{ $role->id }}', '{{ $role->name }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    
                                                    {{-- CORRECCIÓN 2: La ruta destroy recibe el ID encriptado --}}
                                                    <form id="delete-form-{{ $role->id }}"
                                                        action="{{ route('roles.destroy', $idEncriptado) }}" method="POST"
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
    /* Botón Nuevo Rol */
    .btn-create {
        background-color: #2f55d4 !important;
        border-color: #2f55d4 !important;
        color: #ffffff !important;
        border-radius: 50px;
        padding: 0.5rem 1.25rem;
        font-weight: 600;
        box-shadow: 0 2px 6px rgba(47, 85, 212, 0.4) !important;
        transition: all 0.3s ease;
    }

    .btn-create:hover {
        background-color: #2442a8 !important;
        transform: translateY(-2px);
    }

    /* Botones de Acción Circulares */
    .btn-icon {
        width: 35px;
        height: 35px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50% !important; 
        border: none !important;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1) !important;
        transition: all 0.2s ease;
    }
    
    /* Editar (Azul) */
    .btn-action-blue {
        background-color: #2f55d4 !important;
        color: #ffffff !important;
    }
    
    .btn-action-blue:hover {
        background-color: #1e3a8a !important;
        color: #ffffff !important;
        transform: scale(1.1); 
    }

    /* CORRECCIÓN 3: Eliminar estilo duplicado que ponía el botón rojo en azul */
    /* Eliminar (Rojo) */
    .btn-action-red {
        background-color: #fc544b !important;
        color: #ffffff !important;
    }

    .btn-action-red:hover {
        background-color: #d63026 !important;
        color: #ffffff !important;
        transform: scale(1.1);
    }

    /* Tabla */
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        border: none;
    }
    .table td {
        vertical-align: middle;
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
            Swal.fire({
                title: '¿Eliminar Rol?',
                html: `Estás a punto de eliminar el rol <strong>${name}</strong>.<br>Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#fc544b',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash"></i> Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }
    </script>
@endpush