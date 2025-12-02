<form class="form-inline mr-auto" action="#">
    <ul class="navbar-nav nav-option-collapse mr-2">
        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
    </ul>
</form>

<ul class="navbar-nav navbar-right">

    @auth
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">

                @if (Auth::user()->url_image)
                    <img alt="image" src="{{ asset('storage/' . Auth::user()->url_image) }}" class="rounded-circle mr-1"
                        style="width: 30px; height: 30px; object-fit: cover;">
                @else
                    <img alt="image" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random"
                        class="rounded-circle mr-1" style="width: 30px; height: 30px;">
                @endif

                <div class="d-sm-none d-lg-inline-block">
                    Bienvenido {{ Auth::user()->first_name }}
                </div>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-title">
                    Bienvenido, {{ Auth::user()->name }}
                </div>

                <a href="#" class="dropdown-item has-icon text-danger"
                onclick="event.preventDefault(); localStorage.clear(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                </a>
                
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
    @endauth

    @guest
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <div class="d-sm-none d-lg-inline-block">{{ __('messages.common.hello') }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-title">{{ __('messages.common.login') }} / {{ __('messages.common.register') }}</div>
                <a href="{{ route('login') }}" class="dropdown-item has-icon">
                    <i class="fas fa-sign-in-alt"></i> {{ __('messages.common.login') }}
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('register') }}" class="dropdown-item has-icon">
                    <i class="fas fa-user-plus"></i> {{ __('messages.common.register') }}
                </a>
            </div>
        </li>
    @endguest

</ul>