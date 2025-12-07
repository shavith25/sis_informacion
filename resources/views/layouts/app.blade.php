<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema Áreas Protegidas</title>

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>

    <link href="//fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/@fortawesome/fontawesome-free/css/all.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@1.3.1/dist/trix.css">

    <link rel="stylesheet" href="{{ asset('web/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('web/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ayuda.css') }}">

    @stack('css')
    @yield('page_css')

    <style>
        /* Estilos generales y del Sidebar */
        .main-wrapper { display: flex; flex-direction: row; }
        .main-sidebar { transition: width 0.3s ease; }
        .main-only-buttons{ width: 65px; }
        .main-content { flex-grow: 1; transition: margin-left 0.3s ease; margin-left: 260px; }
        .sidebar-hidden { overflow: hidden; transition: width 0.3s ease; }
        .expanded-content { margin-left: 90px !important; transition: margin 0.3s ease; }
        .class-navbar-hidden{ left: 60px !important; }
        .side-menus-hidden span { display: none; }
        .side-menus-hidden a { color: white !important; background: #6777ef !important; }
        .side-menus-hidden a i { font-size: 20px !important; }
        
        /* Loader */
        #loader {
            display: none; position: fixed; top: 0; left: 0;
            width: 100%; height: 100%; background: rgba(0,0,0,0.6);
            z-index: 9999; justify-content: center; align-items: center;
        }
        .spinner {
            border: 8px solid #f3f3f3; border-top: 8px solid #3498db;
            border-radius: 50%; width: 60px; height: 60px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        /* Estilo Responsive */
        @media (max-width: 767px) {
            .nav-option-collapse { right: 70px; position: absolute; }
            .nav-option-collapse.active { position: static; }
            .expanded-content { margin-left: 10px !important; }
            .class-navbar-hidden{ left: 0 !important; }
            .main-only-buttons { width:0 !important; }
            .main-sidebar { overflow: hidden; }
            .main-sidebar.active { width: 260px; position: fixed; }    
            .main-content { margin-left: 5px; }
            .main-navbar { width: 100% !important; }
        }
        
        .sidebar-brand{ text-align: center; }
        .sidebar-brand .app-header-logo { display: block; margin: 0 auto; }
        
        /* Ajuste SweetAlert Toast */
        .colored-toast.swal2-icon-success {
            background-color: #ffffff !important;
            border-left: 6px solid #28a745 !important;
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

        <div class="main-content">
            @yield('content')
        </div>

        <footer class="main-footer">
            @include('layouts.footer')
        </footer>
    </div>
</div>

@auth
    @include('profile.change_password')
    @include('profile.edit_profile')
@endauth

<div id="loader"><div class="spinner"></div></div>

<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.nicescroll.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" src="https://unpkg.com/trix@1.3.1/dist/trix.js"></script>

<script src="{{ asset('web/js/stisla.js') }}"></script>
<script src="{{ asset('web/js/scripts.js') }}"></script>
<script src="{{ mix('assets/js/profile.js') }}"></script>
<script src="{{ mix('assets/js/custom/custom.js') }}"></script>

<script>
    window.routes = {
        'comentarios.index': '{{ route("public.comentarios.index") }}',
        'comentarios.store': '{{ route("public.comentarios.store") }}',
        'sugerencias.index': '{{ route("public.sugerencias.index") }}',
        'sugerencias.store': '{{ route("public.sugerencias.store") }}',
        'reportes.index': '{{ route("public.reportes.index") }}',
        'reportes.store': '{{ route("public.reportes.store") }}',
        'archivos.index': '{{ route("public.archivos.index") }}',
        'archivos.store': '{{ route("public.archivos.store") }}'
    };

    function showLoader() { document.getElementById('loader').style.display = 'flex'; }
    function hideLoader() { document.getElementById('loader').style.display = 'none'; }
    
    let loggedInUser = @json(Auth::user() ?? null);
    let loginUrl = '{{ route('login') }}';
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.querySelector('[data-toggle="sidebar"]');
        if(toggleBtn){
            const sidebar = document.getElementById('sidebar-wrapper'); 
            const navbar = document.getElementById('navbar');
            const sidebarMain = document.querySelector('.main-sidebar');
            const mainContent = document.querySelector('.main-content');
            const sidebarMenu = document.querySelector('.sidebar-menu');
            const navOption = document.querySelector('.nav-option-collapse');

            toggleBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if(sidebar) sidebar.classList.toggle('sidebar-hidden');
                if(sidebarMain) {
                    sidebarMain.classList.toggle('sidebar-hidden');
                    sidebarMain.classList.toggle('main-only-buttons');
                }
                if(navbar) navbar.classList.toggle('class-navbar-hidden');
                if(sidebarMenu) sidebarMenu.classList.toggle('side-menus-hidden');
                if(mainContent) mainContent.classList.toggle('expanded-content');
                if(navOption) navOption.classList.toggle('active');
            });
        }
    });
</script>

<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });


    @if (session('success') || session('toast_success'))
        Toast.fire({
            icon: 'success',
            title: "{{ session('success') ?? session('toast_success') }}",
            customClass: { popup: 'colored-toast' }
        });
    @endif

    @if (session('error') || session('toast_error'))
        Toast.fire({
            icon: 'error',
            title: "{{ session('error') ?? session('toast_error') }}"
        });
    @endif
    
    @if ($errors->any())
        Toast.fire({
            icon: 'error',
            title: 'Por favor revise el formulario.'
        });
    @endif
</script>

@stack('js')
@yield('page_js')
@yield('scripts')
@stack('scripts')

</body>
</html>