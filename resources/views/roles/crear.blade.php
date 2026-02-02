@extends('layouts.app')

@section('content')
    <section class="section section-create-role">
        <div class="section-header">
            <h1 style="color: #6c757d; font-weight: 700;">
                <i class="fas fa-user-tag mr-2"></i> Crear Nuevo Rol
            </h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('home') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></div>
                <div class="breadcrumb-item">Crear</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row justify-content-left">
                <div class="col-12 col-md-10 col-lg-10">
                    <div class="card card-fixed-height">

                        <div class="card-header">
                            <h4><i class="fas fa-users-cog text-primary"></i> Formulario de Registro</h4>
                        </div>

                        <div class="card-body p-0">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible show fade m-3">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        <strong>¡Revise los campos!</strong>
                                        <ul class="mb-0 mt-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            {!! Form::open(['route' => 'roles.store', 'method' => 'POST']) !!}

                            <div class="form-scroll-container">
                                <div class="p-4">

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="name" class="font-weight-bold">
                                                    <i class="fas fa-user-tag text-primary"></i> Nombre del Rol <span
                                                        class="text-danger">*</span>
                                                </label>
                                                {!! Form::text('name', null, [
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Ej: Supervisor de Área Protegida',
                                                    'required',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-divider mt-4 mb-3">
                                        <h6 class="form-section-title"><i class="fas fa-key"></i> Asignación de Permisos
                                        </h6>
                                    </div>

                                    <div class="permissions-container">
                                        @foreach ($permissionsByModule as $module => $permissions)
                                            <div class="card shadow-sm border mb-3">
                                                <div class="card-header bg-light py-2" style="min-height: auto;">
                                                    <h6 class="mb-0 text-dark font-weight-bold" style="font-size: 0.95rem;">
                                                        {{ $module }}</h6>
                                                </div>
                                                <div class="card-body py-3">
                                                    <div class="row">
                                                        @foreach ($permissions as $value)
                                                            <div class="col-md-6 col-lg-4 mb-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    {{ Form::checkbox('permission[]', $value->id, false, ['class' => 'custom-control-input', 'id' => 'perm_' . $value->id]) }}
                                                                    <label class="custom-control-label"
                                                                        for="perm_{{ $value->id }}">
                                                                        {{ $value->name }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mb-2"></div>
                                </div>
                            </div>
                            <div class="card-footer text-right bg-whitesmoke border-top">
                                <a href="{{ route('roles.index') }}" class="btn btn-secondary mr-2">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>

                                <button type="submit" class="btn btn-primary btn-action-custom">
                                    <i class="fas fa-save mr-1"></i> Registrar Rol
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
        /* Estilos Base */
        .section-header h1 {
            color: #6c757d !important;
            font-size: 24px;
            font-weight: 700;
            text-shadow: none;
        }

        /* Estilo del Card Principal */
        .card-fixed-height {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.03);
            border-radius: 5px;
            border: none;
            display: flex;
            flex-direction: column;
            background: #fff;
        }

        .form-scroll-container {
            max-height: 60vh;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Scrollbar Estilizado */
        .form-scroll-container::-webkit-scrollbar {
            width: 10px;
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

        /* Divisores de Sección */
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

        /* Inputs y Checkboxes */
        .form-control {
            border-radius: 0.25rem;
            height: 42px;
        }

        .form-control:focus {
            border-color: #6777ef;
            box-shadow: 0 0 0 0.2rem rgba(103, 119, 239, .25);
        }

        .custom-control-label {
            cursor: pointer;
            user-select: none;
        }

        /* Estilo para el botón de acción (Azul Sólido) */
        .btn-action-custom {
            background-color: #2f55d4 !important;
            border-color: #2f55d4 !important;
            color: #ffffff !important;
            box-shadow: none !important;
            border-radius: 50px;
            padding: 0.55rem 1.5rem;
            font-weight: 600;
            transition: opacity 0.3s ease;
        }

        /* Estados Hover / Focus / Active */
        .btn-action-custom:hover,
        .btn-action-custom:focus,
        .btn-action-custom:active {
            background-color: #2f55d4 !important;
            border-color: #2f55d4 !important;
            color: #ffffff !important;
            box-shadow: none !important;
            outline: none !important;
            opacity: 0.9;
            transform: none !important;
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
    </style>
@endpush
