@role('Administrador')
<li class="{{ Request::is('home') ? 'active' : '' }}">
    <a class="nav-link" href="/home">
        <i class="fas fa-building"></i><span>Inicio</span>
    </a>
</li>
@endrole

@can('ver-usuario')
<li class="{{ Request::is('usuarios*') ? 'active' : '' }}">
    <a class="nav-link" href="/usuarios">
        <i class="fas fa-users"></i><span>Usuarios</span>
    </a>
</li>
@endcan

@can('ver-rol')
<li class="{{ Request::is('roles*') ? 'active' : '' }}">
    <a class="nav-link" href="/roles">
        <i class="fas fa-user-cog"></i><span>Roles</span>
    </a>
</li>
@endcan

@can('ver-area')
<li class="{{ Request::is('areas*') ? 'active' : '' }}">
    <a class="nav-link" href="/areas">
        <i class="fas fa-th"></i><span>Áreas Protegidas</span>
    </a>
</li>
@endcan

@can('ver-zona')
<li class="{{ Request::is('zonas*') && !Request::is('zonas/mapa') ? 'active' : '' }}">
    <a class="nav-link" href="/zonas">
        <i class="fas fa-map"></i><span>Zonas</span>
    </a>
</li>
@endcan

@can('ver-mapa')
<li class="{{ Request::is('zonas/mapa') ? 'active' : '' }}">
    <a class="nav-link" href="/zonas/mapa">
        <i class="fas fa-map-signs"></i><span>Mapa</span>
    </a>
</li>
@endcan

@can('ver-mapa') 
<li class="{{ Request::is('mapa-areas*') ? 'active' : '' }}">
    <a class="nav-link" href="/mapa-areas">
        <i class="fas fa-globe"></i><span>Mapa áreas</span>
    </a>
</li>
@endcan

@can('ver-municipio') 
<li class="dropdown {{ Request::is('limites*') ? 'active' : '' }}" id="limites-li">
    <a href="#" class="nav-link has-dropdown" id="limites-link">
        <i class="fas fa-map-marked-alt"></i><span>Limites</span>
    </a>
    <ul class="dropdown-menu">
        <li class="{{ Request::is('limites/departamentos*') ? 'active' : '' }}">
            <a class="nav-link" href="/limites/departamentos">
                <i class="fas fa-map mr-2"></i> <span>Departamentos</span>
            </a>
        </li>
        <li class="{{ Request::is('limites/provincias*') ? 'active' : '' }}">
            <a class="nav-link" href="/limites/provincias">
                <i class="fas fa-city mr-2"></i> <span>Provincias</span>
            </a>
        </li>
        <li class="{{ Request::is('limites/municipios*') ? 'active' : '' }}">
            <a class="nav-link" href="/limites/municipios">
                <i class="fas fa-map-pin mr-2"></i> <span>Municipios</span>
            </a>
        </li>
    </ul>
</li>
@endcan

@can('ver-especie')
<li class="{{ Request::is('especies*') ? 'active' : '' }}">
    <a class="nav-link" href="/especies">
        <i class="fas fa-leaf"></i><span>Especies</span>
    </a>
</li>
@endcan

@can('ver-noticia')
<li class="{{ Request::is('noticias*') ? 'active' : '' }}">
    <a class="nav-link" href="/noticias">
        <i class="fas fa-newspaper"></i><span>Noticias</span>
    </a>
</li>
@endcan

@can('ver-documento')
<li class="{{ Request::is('documentos*') ? 'active' : '' }}">
    <a class="nav-link" href="/documentos">
        <i class="fas fa-balance-scale"></i><span>Marco Normativo</span>
    </a>
</li>
@endcan

@can('ver-panel-concientizacion')
<li class="{{ Request::is('panel-concientizaciones*') ? 'active' : '' }}">
    <a class="nav-link" href="/panel-concientizaciones">
        <i class="fas fa-video"></i><span>Panel Concientización</span>
    </a>
</li>
@endcan

@can('ver-comentarios')
<li class="{{ Request::is('admin/comentarios*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.comentarios.index') }}">
        <i class="fas fa-comments"></i><span>Revisar Comentarios</span>
    </a>
</li>
@endcan

@can('ver-sugerencias')
<li class="{{ Request::is('admin/sugerencias*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.sugerencias.index') }}">
        <i class="fas fa-lightbulb"></i><span>Revisar Sugerencias</span>
    </a>
</li>
@endcan

@can('ver-reportes')
<li class="{{ Request::is('admin/reportes-ambientales*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.reportes_ambientales.index') }}">
        <i class="fas fa-chart-line"></i><span>Reportes Ambientales</span>
    </a>
</li>
@endcan

@can('ver-media')
<li class="{{ Request::is('admin/media*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.media.index') }}">
        <i class="fas fa-photo-video"></i><span>Revisar Media</span>
    </a>
</li>
@endcan

@can('ver-panel-ayuda')
<li class="{{ Request::is('ayuda*') ? 'active' : '' }}">
    <a class="nav-link" href="/ayuda">
        <i class="fas fa-tools"></i><span>Panel de Ayuda</span>
    </a>
</li>
@endcan

@can('ver-reporte')
<li class="{{ Request::is('reportes') ? 'active' : '' }}">
    <a class="nav-link" href="/reportes">
        <i class="fas fa-file-alt"></i><span>Reportes</span>
    </a>
</li>
@endcan

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const limitesLink = document.getElementById('limites-link');
        const limitesLi = document.getElementById('limites-li');

        if(limitesLink && limitesLi) {
            limitesLink.addEventListener('click', function(e) {
                e.preventDefault();
                limitesLi.classList.toggle('active');
            });
        }
    });
</script>

<style>
    .main-sidebar .sidebar-menu li a.nav-link {
        color: #6777ef !important; 
        font-weight: 500;
        background-color: transparent !important; 
    }
    
    .main-sidebar .sidebar-menu li.active a.nav-link {
        color: #6777ef !important; 
        background-color: transparent !important; 
        box-shadow: none !important; 
        font-weight: 700; 
    }

    .main-sidebar .sidebar-menu li a.nav-link:hover {
        background-color: rgba(0,0,0,0.05) !important; 
        color: #6777ef !important;
    }

    .main-sidebar .sidebar-menu li.dropdown .dropdown-menu li a {
        color: #6777ef !important;
        background-color: transparent !important;
        padding-left: 35px; 
        display: flex; 
        align-items: center; 
    }
    
    .main-sidebar .sidebar-menu li.dropdown .dropdown-menu li a i {
        margin-right: 10px; 
        width: 20px; 
        text-align: center;
    }
    
    .main-sidebar .sidebar-menu li.dropdown .dropdown-menu li.active a {
        font-weight: 700;
        color: #6777ef !important;
    }

    .main-sidebar .sidebar-menu li.dropdown.active .dropdown-menu {
        display: block !important;
        animation: slideDown 0.3s ease-in-out;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>