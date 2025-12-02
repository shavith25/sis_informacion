<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <img class="navbar-brand-full app-header-logo" style="text-align: center;" src="{{ asset('img/MARCA GESTION.png') }}" width="60"
            alt="Infyom Logo" style="display: block; margin: 0 auto;">
        <a href="{{ url('/') }}"></a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ url('/') }}" class="small-sidebar-text">
            <!--<img class="navbar-brand-full" src="{{ asset('img/imagen2.png') }}" width="45px" alt=""/>-->
        </a>
    </div>
    <ul class="sidebar-menu">
        @include('layouts.menu')
    </ul>
</aside>
