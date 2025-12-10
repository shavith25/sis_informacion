@extends('layouts.app')

@section('content')
    <section class="section section-edit-role">
        <div class="section-header">
            <h1 style="color: #6c757d; font-weight: 700;">
                <i class="fas fa-user-tag mr-2"></i> Editar Rol: {{ $role->name }}
            </h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('home') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></div>
                <div class="breadcrumb-item">Editar</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row justify-content-left">
                <div class="col-12 col-md-10 col-lg-8">
                    {{-- Agregamos la clase para altura fija --}}
                    <div class="card card-fixed-height shadow-sm">

                        <div class="card-header border-bottom">
                            <h4><i class="fas fa-edit text-primary"></i> Formulario de Edición</h4>
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

                            {{-- ENCRIPTACIÓN MANUAL CON CRYPT DE LARAVEL --}}
                            @php
                                $idEncriptado = Illuminate\Support\Facades\Crypt::encryptString($role->id);
                            @endphp

                            {!! Form::model($role, ['method' => 'PATCH', 'route' => ['roles.update', $idEncriptado], 'class' => 'h-100 d-flex flex-column']) !!}

                            {{-- Contenedor con Scroll --}}
                            <div class="form-scroll-container">
                                <div class="p-4">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="name" class="font-weight-bold">
                                                    <i class="fas fa-user-tag text-primary"></i> Nombre del Rol <span class="text-danger">*</span>
                                                </label>
                                                {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-divider mt-4 mb-3">
                                        <h6 class="form-section-title"><i class="fas fa-key"></i> Permisos Asignados</h6>
                                        <hr>
                                    </div>

                                    <div class="permissions-container">
                                        @foreach ($permissionsByModule as $module => $permissions)
                                            <div class="card shadow-none border mb-3">
                                                <div class="card-header bg-light py-2" style="min-height: auto;">
                                                    <h6 class="mb-0 text-dark font-weight-bold" style="font-size: 0.95rem;">
                                                        {{ $module }}
                                                    </h6>
                                                </div>
                                                <div class="card-body py-3">
                                                    <div class="row">
                                                        @foreach ($permissions as $value)
                                                            <div class="col-md-6 col-lg-4 mb-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    {{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, ['class' => 'custom-control-input', 'id' => 'perm_' . $value->id]) }}
                                                                    <label class="custom-control-label" for="perm_{{ $value->id }}">
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
                            
                            {{-- Footer fijo al final de la tarjeta --}}
                            <div class="card-footer text-right bg-whitesmoke border-top">
                                <a href="{{ route('roles.index') }}" class="btn btn-secondary mr-2">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary btn-action-custom">
                                    <i class="fas fa-sync-alt mr-1"></i> Actualizar Rol
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
        .card-fixed-height {
            height: calc(100vh - 240px); 
            display: flex;
            flex-direction: column;
            overflow: hidden;
            margin-bottom: 0;
        }

        .card-body {
            flex: 1;
            overflow: hidden; 
            display: flex;
            flex-direction: column;
        }

        .form-scroll-container {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Personalización de la barra de desplazamiento */
        .form-scroll-container::-webkit-scrollbar {
            width: 8px;
        }

        .form-scroll-container::-webkit-scrollbar-track {
            background: #f1f1f1; 
        }

        .form-scroll-container::-webkit-scrollbar-thumb {
            background: #c1c1c1; 
            border-radius: 4px;
        }
        .form-scroll-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8; 
        }

        .card-footer {
            flex-shrink: 0; 
            z-index: 10;
        }
    </style>
@endpush