@extends('layouts.auth_app')

@section('title')

@endsection
@section('content')
    <style>
        input:focus, button:focus, select:focus, textarea:focus {
            outline: none !important;
            box-shadow: none !important;
            border-color: transparent !important;
        }

        /* Estilo para el logo */
        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 15px;
        }

        .logo-container img {
            max-width: 170px;
            height: auto;
        }

        /* Estilo para mensajes de error personalizados */
        .alert-custom {
            background-color: #f8d7da;
            color: #0047ab;
            border: 1px solid #f5c6cb;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 20px;
        }
    </style>

    <div style="background-image: url('{{ asset('img/loros.jpg') }}'); background-size: cover; background-position: center; height: 100vh; width: 100vw; display: flex; justify-content: center; align-items: center; margin: 0; padding: 0; position: fixed; top: 0; left: 0;">
        <div class="card " style="background-color: rgba(255, 255, 255, 0.85); box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3); border-radius: 15px; width: 100%; max-width: 400px;">

            <div class="logo-container mt-3"> 
                <img src="{{ asset('img/MARCA GESTION.png') }}" width="250px" alt="Ingeniería de Sistemas Logo 2">
            </div>

            <h3 style="text-align: center;color:#0047ab; font-weight: 800;">SISTEMA DE INFORMACIÓN DE ÁREAS PROTEGIDAS</h3>

            <p style="text-align: center;color:#0047ab; font-weight: 800;">Programa Gestión de la Biodiversidad (PGB)</p>

            <div class="card-header" style="background-color: #0047ab; border: none;font-weight: 700;">
                <h4 style="color: white;">Inicio de Sesión</h4>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Mensaje de cuenta bloqueada -->
                    @if(session('blocked'))
                        <div class="alert alert-danger p-0">
                            <ul>
                                <li>{{ session('blocked') }}</li>
                            </ul>
                        </div>
                    @endif

                    <!-- Mensaje de intentos fallidos -->
                    @if(session('attempts'))
                        <div class="alert alert-warning p-0">
                            <ul>
                                <li>{{ session('attempts') }}</li>
                            </ul>
                        </div>
                    @endif

                    <!-- Errores de validación -->
                    @if ($errors->any())
                        <div class="alert alert-danger p-0">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="email" style="color: #0047ab; font-weight: 980;">Correo Electrónico</label>

                        <input aria-describedby="emailHelpBlock" id="email" type="email"
                            class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                            placeholder="Introduce tu correo electrónico" tabindex="1"
                            value="{{ (Cookie::get('email') !== null) ? Cookie::get('email') : old('email') }}" autofocus
                            required style="background-color: #beb9b9; color: #131212; border-color: #ffffff;">
                        <div class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="d-block">
                    <label for="password" class="control-label" style="color: #0047ab; font-weight: 980;">Contraseña</label>

                        

                        </div>
                        <div class="input-group">
                            <input aria-describedby="passwordHelpBlock" id="password" type="password"
                                value="{{ (Cookie::get('password') !== null) ? Cookie::get('password') : null }}"
                                placeholder="Introduce tu contraseña"
                                class="form-control{{ $errors->has('password') ? ' is-invalid': '' }}" name="password"
                                tabindex="2" required style="background-color: #beb9b9; color: #0a0a0a; border-color: #ffffff;">
                            <div class="input-group-append">
                                <!-- Botón para mostrar/ocultar contraseña si lo deseas -->
                            </div>
                        </div>
                        <div class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-lg btn-block" tabindex="6" style="background-color: #0047ab; color: white; border-radius: 15px;font-weight: 980;">
                            Iniciar Sesión
                        </button>
                    </div>

                    @if(session('remaining_attempts'))
                        <div class="text-center" style="color: #ffcc00; margin-top: 10px;">
                            Intentos restantes: {{ session('remaining_attempts') }}
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

        <script>
    document.addEventListener('DOMContentLoaded', () => {
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const iconPassword = document.querySelector('#icon-password');

        if (!togglePassword || !password || !iconPassword) return;

        togglePassword.addEventListener('click', () => {
        const isPassword = password.getAttribute('type') === 'password';
        password.setAttribute('type', isPassword ? 'text' : 'password');

        iconPassword.classList.toggle('bi-eye-fill', !isPassword);
        iconPassword.classList.toggle('bi-eye-slash-fill', isPassword);
        });
    });
    </script>

@endsection
