@extends('layouts.app')

@section('content')
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-custom">
                <div class="modal-header modal-header-status text-white">
                    <h5 class="modal-title" id="statusModalLabel"><i class="fas fa-exclamation-circle mr-2"></i> Confirmar
                        Cambio de Estado</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center py-6">
                    <p class="text-muted" style="font-weight: bold; font-size: 20px;">
                        ¿Estás seguro que deseas <span id="statusActionText" class="font-weight-bold"></span> este usuario?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i
                            class="fas fa-times"></i> Cancelar
                    </button>

                    <button type="button" id="confirmStatusChange" class="btn btn-primary btn-sm"><i
                            class="fas fa-check"></i> Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación para Eliminar (Estilo mejorado y más dramático) -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-custom">
                <div class="modal-header modal-header-danger">
                    <h5 class="modal-title text-white" id="deleteModalLabel"><i class="fas fa-trash-alt mr-2"></i> Confirmar
                        Eliminación</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-exclamation-triangle fa-4x text-danger mb-3 animated bounceIn"></i>
                    <h4 class="mb-3 text-dark">¡Atención! Estás a punto de eliminar a este usuario.</h4>
                    <p class="text-muted" style="font-weight: bold; font-size: 20px;">
                        Esta acción no se puede deshacer. ¿Deseas continuar?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i
                            class="fas fa-times"></i> Cancelar</button>
                    <button type="button" id="confirmDelete" class="btn btn-danger btn-sm"><i class="fas fa-check"></i>
                        Eliminar Permanentemente</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección Principal de Gestión de Usuarios -->
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"><i class="fas fa-users mr-2"></i> Gestión de Usuarios</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('home') }}">Inicio</a></div>
                <div class="breadcrumb-item">Usuarios</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-custom">
                        <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                            <h4 class="m-0"><i class="fas fa-list-ul mr-2"></i> Listado de Usuarios</h4>
                            <div class="card-header-action">
                                <a href="{{ route('usuarios.create') }}" class="btn btn-primary btn-create"
                                    id="btnNuevoUsuario">
                                    <i class="fas fa-plus mr-1"></i> Nuevo Usuario
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table-container">
                                <table class="table table-striped table-hover table-custom" id="table-1">
                                    <thead>
                                        <tr>
                                            <th style="display: none;">ID</th>
                                            <th>Nombre</th>
                                            <th>Correo Electrónico</th>
                                            <th>Rol</th>
                                            <th>Estado</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($usuarios as $usuario)
                                            <tr id="user-row-{{ $usuario->getRouteKey() }}">
                                                <td style="display: none;">{{ $usuario->id }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="user-avatar mr-3">
                                                            @if ($usuario->url_image)
                                                                <img src="{{ asset('storage/' . $usuario->url_image) }}"
                                                                    alt="avatar" class="img-fluid">
                                                            @else
                                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($usuario->name) }}&background=28a745&color=ffffff"
                                                                    alt="avatar" class="img-fluid">
                                                            @endif
                                                        </div>
                                                        <strong
                                                            class="text-dark font-weight-bold">{{ $usuario->name }}</strong>
                                                    </div>
                                                </td>
                                                <td>{{ $usuario->email }}</td>
                                                <td>
                                                    @if (!empty($usuario->getRoleNames()))
                                                        @foreach ($usuario->getRoleNames() as $rolName)
                                                            <span class="badge badge-rol">{{ $rolName }}</span>
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-lg badge-{{ $usuario->estado ? 'success' : 'secondary' }}"
                                                        id="status-badge-{{ $usuario->getRouteKey() }}">
                                                        {{ $usuario->estado ? 'Activo' : 'Inactivo' }}
                                                    </span>
                                                </td>
                                                <td class="text-center actions-column">
                                                    <div class="btn-group">
                                                        <a href="{{ route('usuarios.edit', $usuario) }}"
                                                            class="btn btn-icon btn-sm btn-info" data-toggle="tooltip"
                                                            title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        @if (!$usuario->hasRole('Gerente'))
                                                            <button type="button"
                                                                class="btn btn-icon btn-sm {{ $usuario->estado ? 'btn-warning' : 'btn-success' }} change-status-btn"
                                                                data-toggle="modal" data-target="#statusModal"
                                                                data-user-id="{{ $usuario->getRouteKey() }}"
                                                                data-status="{{ $usuario->estado ? 'desactivar' : 'activar' }}"
                                                                title="{{ $usuario->estado ? 'Desactivar' : 'Activar' }}">
                                                                <i
                                                                    class="fas {{ $usuario->estado ? 'fa-user-times' : 'fa-user-check' }}"></i>
                                                            </button>
                                                            <button type="button"
                                                                class="btn btn-icon btn-sm btn-danger delete-btn"
                                                                data-toggle="modal" data-target="#deleteModal"
                                                                data-user-id="{{ $usuario->getRouteKey() }}"
                                                                title="Eliminar">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="pagination-container">
                                {!! $usuarios->links() !!}
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
        #btnNuevoUsuario {
            background-color: #2f55d4 !important;
            border-color: #2f55d4 !important;
            color: #ffffff !important;
            box-shadow: none !important;
            outline: none !important;
        }

        /* Estados al pasar el mouse o hacer click (Mantiene el mismo estilo) */
        #btnNuevoUsuario:hover,
        #btnNuevoUsuario:focus,
        #btnNuevoUsuario:active,
        #btnNuevoUsuario:visited {
            background-color: #2f55d4 !important;
            border-color: #2f55d4 !important;
            color: #ffffff !important;
            box-shadow: none !important;
            opacity: 1 !important;
        }

        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: box-shadow 0.3s ease;
        }

        .card-custom:hover {
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
        }

        /* Encabezado de la tarjeta con gradiente sutil */
        .card-header-custom {
            background: #6c757d;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 1.5rem;
        }

        .card-header-custom h4 {
            color: #343a40;
            font-weight: 700;
        }

        /* Botón de Nueva Entidad */
        .btn-create {
            background-color: #2f55d4;
            border-color: #2f55d4;
            color: white !important;
            border-radius: 50px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            transition: opacity all 0.3s ease;
            box-shadow: none !important;
        }

        .btn-create:hover,
        .btn-create:focus,
        .btn-create:active {
            background-color: #0069d9 !important;
            border-color: #0062cc !important;
            color: white !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-create:hover {
            background-color: #1e8449;
            border-color: #1e8449;
            transform: translateY(-1px);
        }

        .table-container {
            overflow-x: auto;
            max-height: calc(100vh - 350px);
            overflow-y: auto;
        }

        /* Estilos de la tabla */
        .table-custom {
            margin-bottom: 0;
        }

        .table-custom thead th {
            background-color: #6c757d;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            border-top: none;
            border-bottom: none;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table-custom tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .table-custom tbody tr:hover {
            background-color: #e9ecef;
        }

        .table-custom td {
            vertical-align: middle;
            font-weight: 500;
            color: #555;
            border-color: #eee;
        }

        /* Avatares */
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Badges de Roles y Estado */
        .badge-rol {
            background-color: #e0f7fa;
            color: #00796b;
            font-weight: 600;
            border: 1px solid #b2ebf2;
            padding: 0.4em 0.7em;
            border-radius: 15px;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }

        /* Botones de Acción (estilo circular) */
        .btn-group .btn {
            border-radius: 50px;
            width: 34px;
            height: 34px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 2px;
            transition: all 0.2s ease;
        }

        /* Estilo específico para el botón de Confirmar Estado */
        #confirmStatusChange {
            background-color: #007bff !important;
            border-color: #007bff !important;
            color: #ffffff !important;
            box-shadow: 0 2px 6px rgba(0, 123, 255, 0.4) !important;
            transition: all 0.3s;
        }

        /* Efecto al pasar el mouse (Hover) */
        #confirmStatusChange:hover {
            background-color: #0056b3 !important;
            border-color: #0056b3 !important;
            transform: translateY(-1px);
        }

        /* Paginación */
        .pagination-container {
            padding: 1.5rem;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        /* Estilos del Modal */
        .modal-content-custom {
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .modal-header-status {
            background-color: #007bff;
            border-bottom: none;
            border-radius: 10px 10px 0 0;
        }

        .modal-header-danger {
            background-color: #dc3545;
            border-bottom: none;
            border-radius: 10px 10px 0 0;
        }

        /* Animación para el icono de peligro */
        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }

            50% {
                opacity: 1;
                transform: scale(1.05);
            }

            70% {
                transform: scale(0.9);
            }

            100% {
                transform: scale(1);
            }

        }

        .animated.bounceIn {
            animation-name: bounceIn;
            animation-duration: 0.8s;
            animation-fill-mode: both;
        }

        /* Responsive: Ajustes para pantallas pequeñas */
        @media (max-width: 768px) {
            .section-header-breadcrumb {
                display: none;
            }

            .card-header-custom {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .card-header-custom .card-header-action {
                margin-top: 10px;
            }

            .actions-column {
                min-width: 150px;
            }
        }
    </style>
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let targetUserId = null;
            let targetStatus = null;
            let targetButton = null;

            // --- LÓGICA DE CAMBIO DE ESTADO ---
            $('.change-status-btn').on('click', function() {
                targetButton = $(this);
                targetUserId = $(this).data('user-id');
                targetStatus = $(this).data('status');

                const actionText = targetStatus === 'activar' ? 'activar' : 'desactivar';
                $('#statusActionText').text(actionText);

                const confirmBtn = $('#confirmStatusChange');
                confirmBtn.removeClass('btn-success btn-warning');
                confirmBtn.addClass(targetStatus === 'activar' ? 'btn-success' : 'btn-warning');
            });

            $('#confirmStatusChange').on('click', function() {
                const btnConfirm = $(this);
                btnConfirm.prop('disabled', true).text('Procesando...');

                $.ajax({
                    url: '/usuarios/' + targetUserId + '/change-status',
                    type: 'PATCH',
                    success: function(response) {
                        const badge = $('#status-badge-' + targetUserId);

                        if (targetStatus === 'activar') {
                            badge.removeClass('badge-secondary').addClass('badge-success').text('Activo');
                            targetButton.data('status', 'desactivar');
                            targetButton.attr('title', 'Desactivar');
                            targetButton.removeClass('btn-success').addClass('btn-warning');
                            targetButton.find('i').removeClass('fa-user-check').addClass('fa-user-times');
                        } else {
                            badge.removeClass('badge-success').addClass('badge-secondary').text('Inactivo');
                            targetButton.data('status', 'activar');
                            targetButton.attr('title', 'Activar');
                            targetButton.removeClass('btn-warning').addClass('btn-success');
                            targetButton.find('i').removeClass('fa-user-times').addClass('fa-user-check');
                        }

                        $('#statusModal').modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: '¡Estado Actualizado!',
                            text: response.message || 'El estado del usuario ha sido modificado correctamente.',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 4000,
                            timerProgressBar: true
                        });

                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        $('#statusModal').modal('hide');

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo cambiar el estado del usuario.',
                        });
                    },
                    complete: function() {
                        btnConfirm.prop('disabled', false).html('<i class="fas fa-check"></i> Confirmar');
                    }
                });
            });

            // --- LÓGICA DE ELIMINACIÓN ---
            $('.delete-btn').on('click', function() {
                targetUserId = $(this).data('user-id');
            });

            $('#confirmDelete').on('click', function() {
                const btnConfirm = $(this);
                btnConfirm.prop('disabled', true).text('Eliminando...');

                $.ajax({
                    url: '/usuarios/' + targetUserId,
                    type: 'DELETE',
                    success: function(response) {
                        $('#user-row-' + targetUserId).fadeOut(500, function() {
                            $(this).remove();
                        });
                        $('#deleteModal').modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: response.message || 'Usuario eliminado correctamente.',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        let mensaje = 'Error al eliminar el usuario.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            mensaje = xhr.responseJSON.message;
                        }

                        $('#deleteModal').modal('hide');

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: mensaje,
                        });
                    },
                    complete: function() {
                        btnConfirm.prop('disabled', false).html('<i class="fas fa-check"></i> Eliminar Permanentemente');
                    }
                });
            });
        });
    </script>
@endpush
