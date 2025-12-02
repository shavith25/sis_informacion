@extends('layouts.landing')

@section('content')
<section class="hero-section" style="background-image: url('{{ asset('img/imagen1.jpg') }}');">
    <div class="hero-overlay"></div>
    <img src="{{ asset('img/imagen1.png') }}" alt="Logo Áreas Protegidas" class="hero-logo">
</section>

<section class="welcome-section">
    <div class="container">
        <div class="welcome-content">
            <div class="welcome-text">
                <h2 class="welcome-title">Bienvenidas/os a <strong>«MAP»</strong></h2>

                <p>MAP es una Plataforma que permite acceder a datos claros y sencillos que reflejan la situación actual de las Áreas Protegidas (AP) en las diferentes provincias de Cochabamba, y tiene por objetivo el monitoreo permanente de la aplicación de políticas públicas ambientales sobre Áreas Protegidas, así como la divulgación de herramientas enfocadas en la necesaria participación ciudadana para los procesos de toma de decisiones.</p>

                <p>Nuestro sistema integra información actualizada sobre biodiversidad, estado de conservación, amenazas y oportunidades de las áreas protegidas de la región. Con tecnología geoespacial avanzada, ofrecemos visualización interactiva de datos ambientales clave para investigadores, gestores públicos y la comunidad en general.</p>

                <p>Te invitamos a recorrerla a través de las diferentes secciones, utilizar las herramientas disponibles, ayudarnos a sumar nueva información, o realizar un aporte para contribuir con la continuidad de nuestro proyecto.</p>

                <p>¡Gracias por visitarnos y ser parte de esta iniciativa para la conservación de nuestro patrimonio natural!</p>
            </div>
        </div>
    </div>
</section>

<section class="zones-section">
    <div class="container">
        <h2 class="section-title">Zonas Destacadas</h2>
        <div id="zonasCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                @foreach($zonasDestacadas->chunk(3) as $key => $chunk)
                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                    <div class="d-flex justify-content-center">
                        @foreach($chunk as $zona)
                        <div class="zone-card card">
                            <a href="{{ $zona->imagenes ? asset('storage/'.$zona->imagenes[0]) : asset('img/logo.png') }}"
                            data-lightbox="zonas-destacadas"
                            data-title="{{ $zona->nombre }} - {{ $zona->area->area }}"
                            data-alt="{{ $zona->nombre }}">
                                <img src="{{ $zona->imagenes ? asset('storage/'.$zona->imagenes[0]) : asset('img/logo.png') }}"
                                    class="card-img-top"
                                    alt="{{ $zona->nombre }}">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title">{{ $zona->nombre }}</h5>
                                <p class="card-text">{{ Str::limit($zona->descripcion, 80) }}</p>
                                <a href="{{ route('zonas.detalle', $zona) }}" class="btn btn-explore">
                                    <i class="fas fa-binoculars"></i> Explorar
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            <a class="carousel-control-prev" href="#zonasCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Anterior</span>
            </a>
            <a class="carousel-control-next" href="#zonasCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Siguiente</span>
            </a>
        </div>
    </div>
</section>

<section class="stats-section py-5">
    <div class="container">
        <h2 class="section-title text-white mb-5">Nuestro Impacto</h2>
        <div class="row justify-content-center">
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-card text-center p-4 rounded-lg shadow">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-map-marked-alt fa-3x text-primary"></i>
                    </div>
                    <h3 class="stat-number display-4 font-weight-bold mb-2">10,000</h3>
                    <p class="stat-label text-uppercase font-weight-bold mb-0">Hectáreas Protegidas</p>
                    <div class="stat-divider mx-auto my-3"></div>
                    <p class="stat-description small">Áreas conservadas bajo protección especial</p>
                </div>
            </div>

            <div class="col-md-3 col-6 mb-4">
                <div class="stat-card text-center p-4 rounded-lg shadow">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-layer-group fa-3x text-primary"></i>
                    </div>
                    <h3 class="stat-number display-4 font-weight-bold mb-2">{{ $areas->count() }}</h3>
                    <p class="stat-label text-uppercase font-weight-bold mb-0">Áreas Registradas</p>
                    <div class="stat-divider mx-auto my-3"></div>
                    <p class="stat-description small">Ecosistemas únicos en nuestro sistema</p>
                </div>
            </div>

            <div class="col-md-3 col-6 mb-4">
                <div class="stat-card text-center p-4 rounded-lg shadow">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-shield-alt fa-3x text-primary"></i>
                    </div>
                    <h3 class="stat-number display-4 font-weight-bold mb-2">{{ $zonasDestacadas->count() }}</h3>
                    <p class="stat-label text-uppercase font-weight-bold mb-0">Zonas Protegidas</p>
                    <div class="stat-divider mx-auto my-3"></div>
                    <p class="stat-description small">Sitios con protección activa</p>
                </div>
            </div>

            <div class="col-md-3 col-6 mb-4">
                <div class="stat-card text-center p-4 rounded-lg shadow">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-paw fa-3x text-primary"></i>
                    </div>
                    <h3 class="stat-number display-4 font-weight-bold mb-2">15+</h3>
                    <p class="stat-label text-uppercase font-weight-bold mb-0">Especies Protegidas</p>
                    <div class="stat-divider mx-auto my-3"></div>
                    <p class="stat-description small">Fauna en peligro conservada</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
