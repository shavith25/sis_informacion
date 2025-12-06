@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1 style="color: #6c757d; font-weight: 700;">
                <i class="fas fa-user-edit mr-2"></i> Editar Usuario: {{ $user->name }}

                @if (!$user->roles->isEmpty())
                    <small class="text-muted" style="font-size: 0.6em; vertical-align: middle;">
                        ({{ $user->roles->first()->name }})
                    </small>
                @endif
            </h1>
        </div>
        <div class="section-body section-body-adjusted">
            <div class="row">
                <div class="col-lg-10 col-md-12 col-sm-12">
                    <div class="card card-custom-form">
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-warning alert-validation" role="alert">
                                    <strong>¡Revise los campos!</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li><span class="badge badge-danger">{{ $error }}</span></li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {!! Form::model($user, ['method' => 'PATCH', 'route' => ['usuarios.update', $user], 'files' => true]) !!}

                            <div class="form-scroll-container">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <h5 class="form-section-title">Información Principal</h5>

                                        <div class="form-group">
                                            <label for="name" class="form-label-custom"
                                                style="font-size: 14px; font-weight: bold;"><i class="fas fa-user"></i>
                                                Nombre del Usuario</label>
                                            {!! Form::text('name', null, ['class' => 'form-control form-control-custom']) !!}
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label for="email" class="form-label-custom"
                                                style="font-size: 14px; font-weight: bold;"><i class="fas fa-envelope"></i>
                                                Correo Electrónico</label>
                                            {!! Form::text('email', null, ['class' => 'form-control form-control-custom']) !!}
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-12 mt-3">
                                        <h5 class="form-section-title">Seguridad y Roles</h5>

                                        <div class="form-group">
                                            <label for="password" class="form-label-custom"><i class="fas fa-lock"></i>
                                                Contraseña (Dejar vacío si no desea cambiar)</label>
                                            {!! Form::password('password', ['class' => 'form-control form-control-custom']) !!}
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label for="confirm-password" class="form-label-custom"><i
                                                    class="fas fa-lock"></i> Confirmar contraseña</label>
                                            {!! Form::password('confirm-password', ['class' => 'form-control form-control-custom']) !!}
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-12 mt-3">
                                        <div class="form-group">
                                            <label for="roles" class="form-label-custom"
                                                style="font-size: 14px; font-weight: bold;"><i class="fas fa-users"></i>
                                                Roles</label>
                                            {!! Form::select('roles[]', $roles, $user->roles->pluck('name')->all(), [
                                                'class' => 'form-control select-custom',
                                                'multiple' => 'multiple',
                                                'size' => 5,
                                            ]) !!}
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                                        <h5 class="form-section-title">Imagen de Perfil</h5>
                                        @if ($user->url_image)
                                            <div class="form-group">
                                                <label class="form-label-custom">Imagen actual:</label><br>
                                                <img src="{{ asset('storage/' . $user->url_image) }}" alt="Imagen actual"
                                                    class="current-image-preview">
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <label for="url_image" class="form-label-custom">🖼️ Imagen (jpg, png, jpeg) |
                                                Opcional</label>
                                            {!! Form::file('url_image', ['class' => 'form-control-file', 'accept' => 'image/png, image/jpeg, image/jpg']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 text-right pt-3 border-top mt-4">
                                    <button type="submit" class="btn btn-primary btn-action-custom"><i
                                        class="fas fa-save"></i> Actualizar
                                    </button>
                                    
                                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary btn-action-custom"><i
                                        class="fas fa-times"></i> Cancelar
                                    </a>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .section-header h1 {
            color: #6c757d !important;
            /* Gris oscuro */
            font-size: 24px;
            font-weight: 700;
            text-shadow: none;
        }

        /* Asegura que el icono herede el color */
        .section-header h1 i {
            color: #6c757d !important;
        }

        .section-header {
            margin-bottom: 0 !important;
        }

        .page__heading-custom {
            padding: 0 0 10px 0 !important;
            margin-bottom: 0 !important;
        }

        .section-body-adjusted {
            padding-top: 15px !important;
        }

        /* AJUSTES DE SCROLL Y FOOTER */
        .form-scroll-container {
            max-height: 560px;
            overflow-y: auto;
            padding-right: 15px;
            padding-left: 15px;
        }

        /* Reduciendo campos*/
        .form-group {
            margin-bottom: 10px;
        }

        .section-body {
            padding-bottom: 70px;
        }

        /* Estilos Generales y Componentes (Resto de estilos) */
        .page__heading-custom {
            font-weight: 700;
            color: #343a40;
        }

        .card-custom-form {
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
            border: none;
        }

        /* bloque Roles */
        .select-custom[multiple] {
            min-height: 130px;
            height: auto !important;
        }

        .form-label-custom {
            /* Forzar a la etiqueta a tomar toda la línea */
            display: block !important;
            margin-bottom: 5px;
            /* Espacio entre label y campo */
        }

        .form-control-custom {
            /* Asegurar que el input ocupe el 100% de la columna */
            width: 100% !important;
            box-sizing: border-box;
        }

        .alert-validation {
            background-color: #fff3cd;
            border-left: 5px solid #ffc107;
            color: #555;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-validation strong {
            color: #343a40;
            font-weight: bold;
            margin-right: 5px;
        }

        .alert-validation .badge-danger {
            background-color: #343a40;
            color: white;
            font-weight: normal;
            margin-right: 5px;
        }

        .form-section-title {
            font-weight: 700;
            color: #343a40;
            border-bottom: 5px solid #e9ecef;
            padding-bottom: 5px;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .form-label-custom {
            font-weight: 600;
            color: #555;
            font-size: 0.9rem;
        }

        .current-image-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #f8f9fa;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
            margin-top: 5px;
        }

        .btn-action-custom {
            border-radius: 80px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
        }

        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
            transition: background-color 0.2s;
        }

        .btn-primary:hover {
            background-color: #1e8449;
            border-color: #1e8449;
        }

        .btn-secondary {
            background-color: #e9ecef;
            border-color: #ced4da;
            color: #555;
        }

        .btn-secondary:hover {
            background-color: #ced4da;
        }
    </style>
@endsection
