<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema Áreas Protegidas</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    
    <!-- Bootstrap 4.1.1 (Versión de tu plantilla Stisla) -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    
    <!-- Fuentes y Íconos -->
    <link href="//fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/@fortawesome/fontawesome-free/css/all.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/iziToast.min.css') }}">
    <link href="{{ asset('assets/css/sweetalert.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <!-- Trix Editor -->
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@1.3.1/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@1.3.1/dist/trix.js"></script>
    
    @stack('css')
    @yield('page_css')
    
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('web/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('web/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ayuda.css') }}">

    <style>
        #toast-container > .toast:before {
            position: absolute;
            left: 17px;
            top: 25px;
            font-family: 'Ionicons';
            font-size: 24px;
            line-height: 18px;
            color: #ff535300;
        }

        .main-wrapper {
            display: flex;
            flex-direction: row;
        }

        .main-sidebar {
            transition: width 0.3s ease;
        }

        .main-only-buttons{
            width: 65px;
        }

        .main-content {
            flex-grow: 1;
            transition: margin-left 0.3s ease;
            margin-left: 260px;
        }

        .sidebar-hidden {
            overflow: hidden;
            transition: width 0.3s ease;
        }

        .expanded-content {
            margin-left: 90px !important;
            transition: margin 0.3s ease;
        }

        .class-navbar-hidden{
            left: 60px !important;
        }

        .side-menus-hidden span {
            display: none;
        }

        .side-menus-hidden a {
            color: white !important;
            background: #6777ef !important;
        }

        .side-menus-hidden a i {
            font-size: 20px !important;
        }
        
        @media (max-width: 767px) {
            .nav-option-collapse {
                right: 70px;
                position: absolute;
            }

            .nav-option-collapse.active {
                position: static;
            }

            .expanded-content {
                margin-left: 10px !important;
                transition: margin 0.3s ease;
            }

            .class-navbar-hidden{
                left: 0 !important;
            }

            .main-only-buttons {
                width:0 !important;
            }

            .main-sidebar {
                overflow: hidden;
                transition: width 0.3s ease;
            }

            .main-sidebar.active {
                width: 260px;
                position: fixed;
            }    
            
            .main-content {
                margin-left: 5px;
                transition: margin-left 0.3s ease;
            }

            .main-navbar {
                width: 100% !important;
            }
        }
        
        /* Estilo para centrar el logo principal */
        .sidebar-brand{
            text-align: center;
        }

        .sidebar-brand .app-header-logo {
            display: block;
            margin: 0 auto;
        }
        
        /* Loader */
        #loader {
            display: none; 
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.6); 
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .spinner {
            border: 8px solid #f3f3f3;   
            border-top: 8px solid #3498db; 
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0%   { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<body>
<div id="app">
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <nav id="navbar" class="navbar navbar-expand-lg main-navbar">
            @include('layouts.header')
        </nav>

        <div class="main-sidebar main-sidebar-postion">
            @include('layouts.sidebar')
        </div>

        <!-- Main Content -->
        <div class="main-content">
            @yield('content')
        </div>

        <footer class="main-footer">
            @include('layouts.footer')
        </footer>
    </div>
</div>

<script>
    window.routes = {
        // Comentarios
        'comentarios.index': '{{ route("public.comentarios.index") }}',
        'comentarios.store': '{{ route("public.comentarios.store") }}',

        // Sugerencias
        'sugerencias.index': '{{ route("public.sugerencias.index") }}',
        'sugerencias.store': '{{ route("public.sugerencias.store") }}',

        // Reportes
        'reportes.index': '{{ route("public.reportes.index") }}',
        'reportes.store': '{{ route("public.reportes.store") }}',
        
        // Archivos de comunidad
        'archivos.index': '{{ route("public.archivos.index") }}',
        'archivos.store': '{{ route("public.archivos.store") }}'
    };
</script>

@include('profile.change_password')
@include('profile.edit_profile')

<!-- LOADER -->
<div id="loader">
    <div class="spinner"></div>
</div>

<script>
    function showLoader() {
        document.getElementById('loader').style.display = 'flex';
    }
    function hideLoader() {
        document.getElementById('loader').style.display = 'none';
    }
</script>

<!-- JAVASCRIPT (SOLO UNA VEZ) -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.nicescroll.js') }}"></script>

<script src="{{ asset('assets/js/iziToast.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{ asset('web/js/stisla.js') }}"></script>
<script src="{{ asset('web/js/scripts.js') }}"></script>

<script src="{{ mix('assets/js/profile.js') }}"></script>
<script src="{{ mix('assets/js/custom/custom.js') }}"></script>

<!--<script src="{{ asset('js/leaflet-image.js') }}"></script>-->
<script src="{{ asset('js/comentarios-publicos.js') }}"></script>

@stack('js')
@yield('page_js')
@yield('scripts')
@stack('scripts')

<script>
    let loggedInUser = @json(\Illuminate\Support\Facades\Auth::user());
    let loginUrl = '{{ route('login') }}';
    const userUrl = '{{ url('users') }}';
    
    // Loading button plugin (removed from BS4)
    (function ($) {
        $.fn.button = function (action) {
            if (action === 'loading' && this.data('loading-text')) {
                this.data('original-text', this.html()).html(this.data('loading-text')).prop('disabled', true);
            }
            if (action === 'reset' && this.data('original-text')) {
                this.html(this.data('original-text')).prop('disabled', false);
            }
        };
    }(jQuery));

    $(document).ready(function() {
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        let currentUserId = null;
        let currentStatusAction = null;

        $(document).on('click', '.change-status-btn', function() {
            currentUserId = $(this).data('user-id');
            currentStatusAction = $(this).data('status');
            $('#statusActionText').text(currentStatusAction);
        });

        $('#confirmStatusChange').click(function() {
            if (!currentUserId) return;

            $.ajax({
                url: `/usuarios/${currentUserId}/change-status`,
                type: 'PATCH',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $('#confirmStatusChange').prop('disabled', true).text('Procesando...');
                },
                success: function(response) {
                    toastr.success(response.message || 'Estado cambiado correctamente');

                    const badge = $(`#status-badge-${currentUserId}`);
                    const button = $(`.change-status-btn[data-user-id="${currentUserId}"]`);

                    if (badge.hasClass('badge-success')) {
                        badge.removeClass('badge-success').addClass('badge-danger').text('Inactivo');
                        button.removeClass('btn-warning').addClass('btn-success')
                            .data('status', 'activar')
                            .attr('title', 'Activar')
                            .find('i').removeClass('fa-user-times').addClass('fa-user-check');
                    } else {
                        badge.removeClass('badge-danger').addClass('badge-success').text('Activo');
                        button.removeClass('btn-success').addClass('btn-warning')
                            .data('status', 'desactivar')
                            .attr('title', 'Desactivar')
                            .find('i').removeClass('fa-user-check').addClass('fa-user-times');
                    }

                    $('#statusModal').modal('hide');
                },
                error: function(xhr) {
                    let errorMessage = 'Error al cambiar el estado';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    toastr.error(errorMessage);
                },
                complete: function() {
                    $('#confirmStatusChange').prop('disabled', false).text('Confirmar');
                }
            });
        });

        $(document).on('click', '.delete-btn', function() {
            currentUserId = $(this).data('user-id');
        });

        $('#confirmDelete').click(function() {
            if (!currentUserId) return;

            $.ajax({
                url: `/usuarios/${currentUserId}`,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $('#confirmDelete').prop('disabled', true).text('Eliminando...');
                },
                success: function(response) {
                    toastr.success(response.message || 'Usuario eliminado correctamente');
                    $(`#user-row-${currentUserId}`).fadeOut(300, function() {
                        $(this).remove();
                    });
                    $('#deleteModal').modal('hide');
                },
                error: function(xhr) {
                    let errorMessage = 'Error al eliminar el usuario';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    toastr.error(errorMessage);
                },
                complete: function() {
                    $('#confirmDelete').prop('disabled', false).text('Eliminar');
                }
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.querySelector('[data-toggle="sidebar"]');
        const sidebar = document.getElementById('sidebar-wrapper');
        const navbar = document.getElementById('navbar');
        const sidebarMain = document.querySelector('.main-sidebar');
        const mainContent = document.querySelector('.main-content');
        const sidebarMenu = document.querySelector('.sidebar-menu');
        const navOption = document.querySelector('.nav-option-collapse');

        toggleBtn.addEventListener('click', function (e) {
            e.preventDefault();
            sidebar.classList.toggle('sidebar-hidden');
            sidebarMain.classList.toggle('sidebar-hidden');
            sidebarMain.classList.toggle('main-only-buttons');
            navbar.classList.toggle('class-navbar-hidden');
            sidebarMenu.classList.toggle('side-menus-hidden');
            mainContent.classList.toggle('expanded-content');
            navOption.classList.toggle('active');
        });
    });
</script>

<script>
    @if (session('toast_success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('toast_success') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true
        });
    @endif

    @if (session('toast_error'))
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: '{{ session('toast_error') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true
        });
    @endif
</script>

</body>
</html>