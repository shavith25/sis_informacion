@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1><i class="fas fa-user-plus" style="margin-right: 10px;"></i> Registrar Nuevo Usuario</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('usuarios.index') }}">Usuarios</a></div>
                <div class="breadcrumb-item">Crear</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row justify-content-left">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="card card-fixed-height">

                        <div class="card-header">
                            <h4><i class="fas fa-user-plus text-primary"></i> Formulario de Registro</h4>
                        </div>

                        <div class="card-body p-0">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible show fade m-3">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        <strong>¡Revise el campo Correo Electrónico!</strong>, la cuenta ya existe !</strong>
                                    </div>
                                </div>
                            @endif

                            {!! Form::open(['route' => 'usuarios.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

                            <div class="form-scroll-container">
                                <div class="p-4">
                                    <div class="form-divider">
                                        <h6 class="form-section-title">Información de Perfil</h6>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name" class="font-weight-bold">
                                                    <i class="fas fa-user text-primary"></i> Nombre del usuario <span
                                                        class="text-danger">*</span>
                                                </label>
                                                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Ej: Juan Perez', 'required']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email" class="font-weight-bold">
                                                    <i class="fas fa-envelope text-primary"></i> Correo Electrónico <span
                                                        class="text-danger">*</span>
                                                </label>
                                                {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Ej: juan@ejemplo.com', 'required']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-divider mt-4">
                                        <h6 class="form-section-title">Seguridad</h6>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password" class="font-weight-bold">
                                                    <i class="fas fa-lock text-primary"></i> Contraseña <span
                                                        class="text-danger">*</span>
                                                </label>
                                                {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Mínimo 8 caracteres', 'required']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="confirm-password" class="font-weight-bold">
                                                    <i class="fas fa-lock text-primary"></i> Confirmar contraseña <span
                                                        class="text-danger">*</span>
                                                </label>
                                                {!! Form::password('confirm-password', [
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Repetir contraseña',
                                                    'required',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-divider mt-4">
                                        <h6 class="form-section-title">Asignación de Roles</h6>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="roles" class="font-weight-bold">
                                                    <i class="fas fa-users-cog text-primary"></i> Seleccionar Roles <span
                                                        class="text-danger">*</span>
                                                </label>
                                                {!! Form::select(
                                                    'roles[]',
                                                    $roles,
                                                    [],
                                                    ['class' => 'form-control select2', 'multiple' => 'multiple', 'style' => 'width:100%'],
                                                ) !!}
                                                <small class="form-text text-muted">Puede seleccionar múltiples
                                                    roles.</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-divider mt-4">
                                        <h6 class="form-section-title">Foto Perfil (Opcional)</h6>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="url_image" class="font-weight-bold"><i
                                                        class="fas fa-image text-primary"></i> Subir Imagen</label>
                                                <div class="custom-file">
                                                    {!! Form::file('url_image', [
                                                        'class' => 'custom-file-input',
                                                        'id' => 'customFile',
                                                        'accept' => 'image/png, image/jpeg, image/jpg',
                                                    ]) !!}
                                                    <label class="custom-file-label" for="customFile">Elegir
                                                        archivo...</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3"></div>
                                </div>
                            </div>
                            <div class="card-footer text-right bg-whitesmoke border-top">
                                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary mr-2">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>

                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> Registrar Usuario
                                </button>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('css')
    <style>
        .section-header h1 {
            color: #6c757d !important;
            font-size: 24px;
            font-weight: 700;
            text-shadow: none;
        }

        /* Estilos Base */
        .section-header h1 {
            color: #34395e;
            font-size: 24px;
            font-weight: 700;
        }

        /* Estilo del Card */
        .card-fixed-height {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.03);
            border-radius: 5px;
            border: none;
            display: flex;
            flex-direction: column;
        }

        .form-scroll-container {
            max-height: 60vh;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .btn-primary {
            background-color: #2f55d4 !important;
            border-color: #2f55d4 !important;
            color: #fff !important;
            box-shadow: none !important;
            border-radius: 5px;
            font-weight: 600;
            transition: opacity 0.3s ease;
        }

        /* Estados Interactivos (Hover, Focus, Active) */
        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active,
        .btn-primary:visited {
            background-color: #2f55d4 !important;
            border-color: #2f55d4 !important;
            color: #fff !important;
            box-shadow: none !important;
            outline: none !important;

            /* Efecto sutil de opacidad */
            opacity: 0.9;
            transform: none !important;
        }

        /* Estilización del Scrollbar (Opcional - Estilo moderno) */
        .form-scroll-container::-webkit-scrollbar {
            width: 8px;
        }

        .form-scroll-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .form-scroll-container::-webkit-scrollbar-thumb {
            background: #c5caff;
            border-radius: 4px;
        }

        .form-scroll-container::-webkit-scrollbar-thumb:hover {
            background: #aeb4f5;
        }

        /* Inputs y Elementos del Formulario */
        .form-divider {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .form-section-title {
            font-weight: 700;
            color: #6777ef;
            margin: 0;
            padding-right: 15px;
        }

        .form-divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #e4e6fc;
        }

        .form-control {
            border-radius: 0.25rem;
            height: 42px;
        }

        .form-control[multiple],
        .select-multiple-custom {
            height: auto !important;
            /* Permite que crezca */
            min-height: 150px;
            /* Le damos una altura mínima grande */
            padding: 10px;
        }

        .select2-container .select2-selection--multiple {
            min-height: 42px !important;
            height: auto !important;
        }

        .form-control:focus {
            border-color: #6777ef;
            box-shadow: 0 0 0 0.2rem rgba(103, 119, 239, .25);
        }

        /* Botones */
        .btn-primary {
            background-color: #6777ef;
            border-color: #6777ef;
            box-shadow: 0 2px 6px #acb5f6;
            color: #fff;
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active {
            background-color: #394eea !important;
            border-color: #394eea !important;
            color: #fff !important;
        }

        .btn-secondary {
            background-color: #cdd3d8;
            border-color: #cdd3d8;
            color: #212529;
        }

        .custom-file-label::after {
            content: "Navegar";
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    </script>
@endpush
