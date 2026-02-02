    <!DOCTYPE html>
    <html lang="es">
    <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Programa Gestión de la Biodiversidad (PGB)</title>

    <!-- CSS base -->
    <link rel="stylesheet" href="{{ asset('css/style-public-areas.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* =========================
        PALETA (COCHABAMBA)
        ========================== */
        :root{
        --brand:#007FC6;          /* navbar */
        --brand-dark:#005E93;
        --brand-darker:#00446A;
        --brand-soft:#37B7FF;

        --accent:#22c55e;         /* naturaleza */
        --accent-dark:#16a34a;

        --ink:#0f172a;
        --muted: rgba(15,23,42,.68);
        --card:#ffffff;

        --shadow: 0 18px 50px rgba(2,6,23,.12);
        --shadow-soft: 0 12px 34px rgba(2,6,23,.08);
        --radius: 18px;
        }

        html{ scroll-behavior:smooth; }
        body{
        font-family: system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif;
        color: var(--ink);
        background:
            radial-gradient(800px 380px at 12% 8%, rgba(0,127,198,.10), transparent 60%),
            radial-gradient(760px 340px at 88% 14%, rgba(34,197,94,.08), transparent 60%),
            #fff;
        }

        /* =========================
        HEADER / NAV (PRO)
        ========================== */
        .topbar{
        position: sticky;
        top: 0;
        z-index: 1300;
        background: rgba(0,127,198,.92);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(255,255,255,.18);
        box-shadow: 0 10px 24px rgba(0,0,0,.10);
        }

        .topbar-inner{
        max-width: 1500px;
        margin: 0 auto;
        padding: .98rem 2rem;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap: 1rem;
        }

        .brand{
        display:flex;
        align-items:center;
        gap:.75rem;
        text-decoration:none;
        color:#fff;
        font-weight:900;
        letter-spacing:.02em;
        white-space:nowrap;
        }

        .brand img{
        height: 42px;
        width: auto;
        display:block;
        filter: drop-shadow(0 10px 18px rgba(0,0,0,.15));
        }

        .navpro{
        display:flex;
        align-items:center;
        gap: .5rem;
        flex-wrap: wrap;
        margin:0;
        padding:0;
        list-style:none;
        }

        .navpro a{
        display:inline-flex;
        align-items:center;
        gap:.55rem;
        padding: .55rem .75rem;
        border-radius: 999px;
        color:#fff;
        text-decoration:none;
        font-weight: 800;
        font-size: .92rem;
        border: 1px solid rgba(255,255,255,.16);
        background: rgba(255,255,255,.08);
        transition: transform .18s ease, background .18s ease, border-color .18s ease;
        }

        .navpro a:hover{
        background: rgba(255,255,255,.18);
        border-color: rgba(255,255,255,.28);
        transform: translateY(-1px);
        }

        .navpro i{ opacity: .95; }

        /* Search */
        .search-wrap{
        width: min(320px, 100%);
        position: relative;
        }

        .search-wrap input{
        border-radius: 999px;
        padding-left: 42px;
        border: 1px solid rgba(255,255,255,.28);
        background: rgba(255,255,255,.14);
        color: #fff;
        box-shadow: none !important;
        }
        .search-wrap input::placeholder{ color: rgba(255,255,255,.8); }

        .search-icon{
        position:absolute;
        top: 50%;
        left: 14px;
        transform: translateY(-50%);
        color: rgba(255,255,255,.95);
        pointer-events:none;
        }

        #search-results{
        border-radius: 14px;
        overflow:hidden;
        margin-top: .5rem;
        box-shadow: var(--shadow);
        cursor:pointer;
        }
        #search-results li:hover{ background-color:#f8f9fa; }

        /* Responsive header */
        @media (max-width: 992px){
        .topbar-inner{
            flex-direction: column;
            align-items: stretch;
        }
        .brand{
            justify-content:center;
        }
        .navpro{
            justify-content:center;
        }
        .search-wrap{
            margin: 0 auto;
        }
        }

        /* =========================
        HERO MAP
        ========================== */
        .hero{
        max-width: 1800px;
        margin: 0 auto;
        padding: 1.25rem 1rem 0;
        }

        .hero-card{
        border-radius: calc(var(--radius) + 6px);
        overflow:hidden;
        border: 1px solid rgba(2,6,23,.08);
        background:#fff;
        box-shadow: var(--shadow-soft);
        position: relative;
        }

        .hero-top{
        padding: 1rem 1rem .75rem;
        display:flex;
        align-items:flex-end;
        justify-content:space-between;
        gap: 1rem;
        background:
            linear-gradient(90deg, rgba(0,127,198,.12), rgba(34,197,94,.06)),
            #fff;
        border-bottom: 1px solid rgba(2,6,23,.06);
        }

        .hero-title{
        margin:0;
        font-weight: 950;
        letter-spacing: -.03em;
        color: var(--brand-darker);
        line-height:1.1;
        font-size: clamp(1.15rem, 2.2vw, 1.55rem);
        }

        .hero-sub{
        margin: .25rem 0 0;
        color: var(--muted);
        max-width: 70ch;
        font-size: .95rem;
        }

        .hero-pill{
        display:inline-flex;
        align-items:center;
        gap:.55rem;
        padding: .5rem .75rem;
        border-radius: 999px;
        font-weight: 900;
        font-size: .85rem;
        color: #fff;
        background: linear-gradient(90deg, var(--brand-dark), var(--brand), var(--brand-soft));
        box-shadow: 0 14px 30px rgba(0,127,198,.22);
        white-space:nowrap;
        }

        .map-shell{
        height: 720px;
        background: #e3f2fd;
        position: relative;
        }
        @media (max-width: 768px){
        .map-shell{ height: 560px; }
        .hero-pill{ display:none; }
        }

        #map{ width: 100%; height: 100%; }

        /* Controls floating (más pro) */
        .map-controls{
        position:absolute;
        top: 14px;
        left: 14px;
        z-index: 999;
        display:flex;
        flex-direction: column;
        gap: 10px;
        padding: 10px;
        border-radius: 16px;
        background: rgba(255,255,255,.88);
        border: 1px solid rgba(2,6,23,.08);
        box-shadow: 0 18px 50px rgba(2,6,23,.18);
        backdrop-filter: blur(8px);
        }

        .map-controls .btn{
        width: 46px;
        height: 44px;
        border-radius: 14px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight: 900;
        border: 1px solid rgba(2,6,23,.10);
        }

        .map-controls .btn:hover{
        transform: translateY(-1px);
        box-shadow: 0 14px 28px rgba(2,6,23,.12);
        }

        /* Layer panel */
        .layer-container{ width: 100%; }
        .layer-button{
        width: 46px; height: 44px;
        font-size: 18px;
        border-radius: 14px;
        line-height:1;
        padding:0;
        }
        .layer-panel{
        margin-top: 8px;
        width: 230px;
        border-radius: 16px;
        background: rgba(255,255,255,.94);
        border: 1px solid rgba(2,6,23,.10);
        box-shadow: var(--shadow);
        padding: 12px;
        display:none;
        }
        .layer-panel.show{ display:block; }

        /* =========================
        SECTIONS
        ========================== */
        main.container{
        max-width: 1200px;
        }

        .section-wrap{
        margin-top: 2.75rem;
        }

        .section-head{
        display:flex;
        align-items:flex-end;
        justify-content:space-between;
        gap: 1rem;
        margin-bottom: 1.1rem;
        }

        .section-title{
        margin:0;
        padding-bottom: .65rem;
        font-weight: 950;
        letter-spacing: -.03em;
        color: var(--brand-darker);
        border-bottom: 1px solid rgba(2,6,23,.08);
        position: relative;
        }

        .section-title::after{
        content:"";
        position:absolute;
        left:0;
        bottom:-2px;
        width: 110px;
        height: 5px;
        border-radius: 999px;
        background: linear-gradient(90deg, var(--brand), var(--brand-soft), var(--accent));
        box-shadow: 0 10px 26px rgba(0,127,198,.18);
        }

        .section-note{
        color: var(--muted);
        margin:0;
        font-size: .95rem;
        }

        /* =========================
        CARDS (UNIFICADAS)
        ========================== */
        .grid{
        display:grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 1.25rem;
        }
        .grid > .cardpro{ grid-column: span 4; }
        @media (max-width: 992px){ .grid > .cardpro{ grid-column: span 6; } }
        @media (max-width: 576px){ .grid > .cardpro{ grid-column: span 12; } }

        .cardpro{
        border-radius: 20px;
        overflow:hidden;
        background:#fff;
        border: 1px solid rgba(2,6,23,.08);
        box-shadow: var(--shadow-soft);
        transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
        position: relative;
        }

        .cardpro:hover{
        transform: translateY(-7px);
        box-shadow: var(--shadow);
        border-color: rgba(2,6,23,.14);
        }

        /* Borde degradado premium */
        .cardpro::before{
        content:"";
        position:absolute;
        inset:0;
        padding: 1px;
        border-radius: 20px;
        background: linear-gradient(135deg, rgba(0,127,198,.55), rgba(55,183,255,.40), rgba(34,197,94,.35));
        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
        -webkit-mask-composite: xor;
                mask-composite: exclude;
        pointer-events:none;
        opacity: .55;
        transition: opacity .22s ease;
        }
        .cardpro:hover::before{ opacity: .95; }

        .card-link{
        display:block;
        color: inherit;
        text-decoration:none;
        height:100%;
        }

        .card-media{
        position: relative;
        height: 200px;
        background:#eef2f7;
        overflow:hidden;
        }
        .card-media img{
        width:100%;
        height:100%;
        object-fit: cover;
        transform: scale(1);
        transition: transform .45s ease;
        }
        .cardpro:hover .card-media img{ transform: scale(1.08); }

        .card-media::after{
        content:"";
        position:absolute;
        inset:0;
        background: linear-gradient(to top, rgba(2,6,23,.55), rgba(2,6,23,0) 65%);
        opacity: .65;
        transition: opacity .22s ease;
        }
        .cardpro:hover .card-media::after{ opacity: .75; }

        .badgepro{
        position:absolute;
        top: 14px;
        left: 14px;
        display:inline-flex;
        align-items:center;
        gap: .5rem;
        padding: .5rem .75rem;
        border-radius: 999px;
        color:#fff;
        font-weight: 950;
        font-size: .83rem;
        background: linear-gradient(90deg, var(--accent-dark), var(--brand));
        border: 1px solid rgba(255,255,255,.30);
        box-shadow: 0 12px 26px rgba(0,127,198,.16);
        backdrop-filter: blur(6px);
        }

        .card-bodypro{
        padding: 1rem 1rem 1.1rem;
        display:flex;
        flex-direction: column;
        gap: .65rem;
        }

        .card-titlepro{
        margin:0;
        font-size: 1.05rem;
        font-weight: 950;
        letter-spacing: -.02em;
        line-height: 1.2;
        text-transform: uppercase;
        color: var(--brand-darker);
        }

        .card-desc{
        margin:0;
        color: var(--muted);
        line-height:1.5;
        font-size: .95rem;
        }

        .meta-row{
        display:flex;
        flex-wrap: wrap;
        gap: .55rem;
        margin-top: .1rem;
        }

        .pill{
        display:inline-flex;
        align-items:center;
        gap: .45rem;
        padding: .45rem .65rem;
        border-radius: 999px;
        font-size: .82rem;
        font-weight: 850;
        border: 1px solid rgba(0,127,198,.14);
        background: rgba(0,127,198,.06);
        color: rgba(2,6,23,.78);
        }
        .pill i.fa-map{ color: var(--brand-dark); }
        .pill i.fa-location-crosshairs{ color: var(--accent-dark); }
        .pill i.fa-triangle-exclamation{ color: #dc2626; }
        .pill i.fa-calendar{ color: var(--brand-dark); }

        .card-footerpro{
        display:flex;
        justify-content:flex-end;
        margin-top: .2rem;
        }

        .cta{
        display:inline-flex;
        align-items:center;
        gap: .55rem;
        padding: .55rem .85rem;
        border-radius: 999px;
        font-weight: 950;
        color:#fff;
        background: linear-gradient(90deg, var(--brand-dark), var(--brand), var(--brand-soft));
        box-shadow: 0 16px 34px rgba(0,127,198,.22);
        transition: transform .22s ease, box-shadow .22s ease, filter .22s ease;
        }
        .cardpro:hover .cta{
        transform: translateX(4px);
        box-shadow: 0 20px 42px rgba(0,127,198,.28);
        filter: saturate(1.03);
        }

        /* =========================
        CONCIENTIZACIÓN (CALL OUT)
        ========================== */
        .awareness{
        border-radius: 22px;
        padding: 2.2rem 1.5rem;
        background:
            linear-gradient(90deg, rgba(0,127,198,.12), rgba(34,197,94,.08)),
            #fff;
        border: 1px solid rgba(2,6,23,.08);
        box-shadow: var(--shadow-soft);
        position: relative;
        overflow:hidden;
        text-align:left;
        }

        .awareness::before{
        content:"";
        position:absolute;
        inset:-60px;
        background:
            radial-gradient(300px 220px at 20% 20%, rgba(0,127,198,.20), transparent 70%),
            radial-gradient(320px 240px at 85% 35%, rgba(34,197,94,.14), transparent 70%);
        filter: blur(14px);
        opacity: .9;
        pointer-events:none;
        }

        .awareness > *{ position: relative; z-index: 1; }

        .awareness h2{
        margin:0;
        font-weight: 950;
        letter-spacing: -.03em;
        color: var(--brand-darker);
        }

        .awareness p{
        margin: .85rem 0 0;
        color: rgba(2,6,23,.78);
        line-height: 1.75;
        font-size: 1.05rem;
        }

        .awareness .btn{
        border-radius: 999px;
        font-weight: 950;
        padding: .8rem 1.15rem;
        background: linear-gradient(90deg, var(--accent-dark), var(--accent));
        border: none;
        box-shadow: 0 16px 34px rgba(22,163,74,.22);
        }
        .awareness .btn:hover{
        transform: translateY(-1px);
        box-shadow: 0 20px 44px rgba(22,163,74,.28);
        }

        /* =========================
        FOOTER (mantiene azul)
        ========================== */
        footer{
        background: linear-gradient(180deg, var(--brand), var(--brand-dark));
        color: white;
        padding-top: 50px;
        padding-bottom: 20px;
        position: relative;
        margin-top: 3rem;
        }
        footer::before{
        content:"";
        position:absolute;
        inset:0;
        background:
            radial-gradient(680px 260px at 15% 10%, rgba(255,255,255,.14), transparent 60%),
            radial-gradient(620px 240px at 90% 20%, rgba(255,255,255,.10), transparent 60%);
        pointer-events:none;
        opacity:.7;
        }
        footer .container{ position: relative; z-index: 1; }

        .footer-title{
        font-weight: 900;
        text-transform: uppercase;
        border-bottom: 1px solid rgba(255,255,255,.35);
        padding-bottom: 6px;
        margin-bottom: 18px;
        display: inline-block;
        font-size: 1rem;
        letter-spacing: .02em;
        }
        .contact-list{
        list-style: none;
        padding: 0;
        margin: 0;
        font-size: 0.95rem;
        }
        .contact-list li{
        margin-bottom: 14px;
        display:flex;
        align-items:flex-start;
        }
        .contact-list i{
        color: #ffd166;
        margin-right: 10px;
        font-size: 1.1rem;
        margin-top: 3px;
        }
        .social-box{
        display:inline-flex;
        justify-content:center;
        align-items:center;
        width: 46px;
        height: 46px;
        border: 1px solid rgba(255,255,255,.55);
        border-radius: 14px;
        color: white;
        margin-right: 10px;
        text-decoration:none;
        font-size: 1.3rem;
        transition: all .22s ease;
        background: rgba(255,255,255,.06);
        }
        .social-box:hover{
        background-color: white;
        color: var(--brand-dark);
        transform: translateY(-2px);
        box-shadow: 0 18px 36px rgba(0,0,0,.18);
        }
        .footer-logo{ max-height: 80px; margin-bottom: 15px; }

        .copyright-line{
        border-top: 1px solid rgba(255,255,255,.28);
        margin-top: 40px;
        padding-top: 18px;
        text-align:center;
        font-size: .85rem;
        color: rgba(255,255,255,.92);
        }

        /* WhatsApp */
        .whatsapp-float{
        position: fixed;
        bottom: 28px;
        right: 28px;
        z-index: 1050;
        transition: transform .22s ease;
        }
        .whatsapp-float:hover{ transform: scale(1.08); }
        .whatsapp-float img{ width: 78px; height: 78px; }

        @media (max-width: 768px){
        .footer-col{ text-align:center; margin-bottom: 28px; }
        .contact-list li{ justify-content:center; }
        }
    </style>
    </head>

    <body>

    <!-- HEADER PRO -->
    <header class="topbar">
        <div class="topbar-inner">
        <a class="brand" href="#inicio">
            <img src="{{ url('img/logo3.png') }}" alt="Programa Gestión de la Biodiversidad (PGB)">
        </a>

        <ul class="navpro">
            <li><a href="#inicio"><i class="bi bi-house-fill"></i> INICIO</a></li>
            <li><a href="#areas"><i class="fa-solid fa-tree"></i> ÁREAS</a></li>
            <li><a href="/limites"><i class="fa-solid fa-border-all"></i> LÍMITES</a></li>
            <li><a href="#especies"><i class="fa-solid fa-leaf"></i> ESPECIES</a></li>
            <li><a href="#noticias"><i class="fa-solid fa-bullhorn"></i> NOTICIAS</a></li>
            <li><a href="#conciencia"><i class="fa-solid fa-hand-holding-heart"></i> CONCIENCIA</a></li>
        </ul>

        <div class="search-wrap">
            <i class="fa-solid fa-magnifying-glass search-icon"></i>
            <input type="text" class="form-control" id="search-box" placeholder="Buscar ubicación...">
            <ul id="search-results" class="list-group position-absolute w-100"
                style="z-index:1200; max-height:240px; overflow-y:auto; display:none;"></ul>
        </div>
        </div>
    </header>

    <!-- HERO MAP -->
    <section id="inicio" class="hero">
        <div class="hero-card">
        <div class="hero-top">
            <div>
            <h1 class="hero-title">Mapa Interactivo de Biodiversidad</h1>
            <p class="hero-sub">Explora áreas protegidas, ubica zonas clave y cambia el mapa base según tu necesidad.</p>
            </div>
            <div class="hero-pill"><i class="fa-solid fa-location-dot"></i> Cochabamba • Bolivia</div>
        </div>

        <div class="map-shell">
            <div id="map"></div>

            <!-- Controles pro -->
            <div class="map-controls">
            <button class="btn btn-light text-dark" onclick="map.zoomIn()" title="Acercar">
                <i class="fa-solid fa-plus"></i>
            </button>
            <button class="btn btn-light text-dark" onclick="map.zoomOut()" title="Alejar">
                <i class="fa-solid fa-minus"></i>
            </button>
            <button class="btn btn-light" onclick="centerMap()" title="Centrar en Cochabamba">
                <i class="fa-solid fa-crosshairs"></i>
            </button>

            <div class="layer-container" onmouseleave="hideLayerPanel()">
                <button id="layer-button" class="btn btn-light layer-button" onmouseenter="showLayerPanel()" title="Capas">
                <i class="fa-solid fa-layer-group"></i>
                </button>

                <div id="layer-panel" class="layer-panel">
                <div class="mb-2 fw-bold" style="color: var(--brand-darker);">
                    <i class="fa-solid fa-map me-1"></i> Mapa base
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="basemap" id="radioHibrido" value="hibrido" checked>
                    <label class="form-check-label" for="radioHibrido">Híbrido</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="basemap" id="radioOSM" value="osm">
                    <label class="form-check-label" for="radioOSM">OpenStreetMap</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="basemap" id="radioTopo" value="topo">
                    <label class="form-check-label" for="radioTopo">OpenTopoMap</label>
                </div>
                </div>
            </div>
            </div>

        </div>
        </div>
    </section>

    <main class="container">

        <!-- ÁREAS -->
        <section id="areas" class="section-wrap">
            <div class="section-head">
                <h2 class="section-title">Áreas Protegidas Destacadas</h2>
            </div>

        <div class="grid">
            @forelse($zonas as $zona)
            <div class="cardpro">
                <a class="card-link"
                href="{{ route('detalle.show', ['tipo' => 'zona', 'id' => Illuminate\Support\Facades\Crypt::encrypt($zona->id)]) }}">

                <div class="card-media">
                    @if ($zona->imagenes->count())
                    <img src="{{ asset('storage/' . $zona->imagenes->first()->url) }}" alt="{{ $zona->nombre }}" loading="lazy">
                    @endif
                    <div class="badgepro"><i class="fa-solid fa-tree"></i> Protegida</div>
                </div>

                <div class="card-bodypro">
                    <h3 class="card-titlepro">{{ $zona->nombre }}</h3>
                    <p class="card-desc">{{ \Illuminate\Support\Str::limit($zona->descripcion, 200, '...') }}</p>

                    <div class="meta-row">
                    <span class="pill"><i class="fa-solid fa-map"></i> {{ $zona->area ? $zona->area->nombre : 'Área no especificada' }}</span>
                    <span class="pill"><i class="fa-solid fa-location-crosshairs"></i> {{ $zona->tipo_coordenada ?? 'Tipo no definido' }}</span>
                    </div>

                    <div class="card-footerpro">
                    <span class="cta">Explorar <i class="fa-solid fa-arrow-right"></i></span>
                    </div>
                </div>
                </a>
            </div>
            @empty
            <div class="alert alert-info w-100 text-center">No hay zonas protegidas disponibles.</div>
            @endforelse
        </div>
        </section>

        <!-- ESPECIES -->
        <section id="especies" class="section-wrap">
            <div class="section-head">
                <h2 class="section-title">Especies en Peligro Crítico</h2>
            </div>

        <div class="grid">
            @forelse($datos as $dato)
            <div class="cardpro">
                <a class="card-link"
                href="{{ route('detalle.show', ['tipo' => 'dato', 'id' => Illuminate\Support\Facades\Crypt::encrypt($dato->id)]) }}">

                <div class="card-media">
                    @if ($dato->imagenes->count())
                    <img src="{{ asset('storage/' . $dato->imagenes->first()->path) }}" alt="{{ $dato->especies_peligro }}" loading="lazy">
                    @endif
                    <div class="badgepro"><i class="fa-solid fa-leaf"></i> Crítico</div>
                </div>

                <div class="card-bodypro">
                    <h3 class="card-titlepro">{{ $dato->especies_peligro }}</h3>
                    <p class="card-desc">{{ \Illuminate\Support\Str::limit($dato->flora_fauna ?: $dato->otros_datos, 200, '...') }}</p>

                    <div class="meta-row">
                    <span class="pill"><i class="fa-solid fa-triangle-exclamation"></i> En Peligro Crítico</span>
                    <span class="pill"><i class="fa-solid fa-map"></i> {{ $dato->provincia }}, {{ $dato->zona ? $dato->zona->nombre : 'Bolivia' }}</span>
                    </div>

                    <div class="card-footerpro">
                    <span class="cta">Saber más <i class="fa-solid fa-arrow-right"></i></span>
                    </div>
                </div>
                </a>
            </div>
            @empty
            <div class="alert alert-info w-100 text-center">No hay especies registradas.</div>
            @endforelse
        </div>
        </section>

        <!-- NOTICIAS -->
        <section id="noticias" class="section-wrap">
            <div class="section-head">
                <h2 class="section-title">Últimas Noticias</h2>
            </div>

        <div class="grid">
            @forelse($noticias as $noticia)
            <div class="cardpro">
                <a class="card-link"
                href="{{ route('detalle.show', ['tipo' => 'noticia', 'id' => Illuminate\Support\Facades\Crypt::encrypt($noticia->id)]) }}">

                <div class="card-media">
                    @if ($noticia->imagenes->count())
                    <img src="{{ asset('storage/' . $noticia->imagenes->first()->ruta) }}" alt="{{ $noticia->titulo }}" loading="lazy">
                    @endif
                    <div class="badgepro"><i class="fa-solid fa-bullhorn"></i> Noticia</div>
                </div>

                <div class="card-bodypro">
                    <h3 class="card-titlepro">{!! $noticia->titulo !!}</h3>
                    <p class="card-desc">{{ \Illuminate\Support\Str::limit($noticia->descripcion, 200, '...') }}</p>

                    <div class="meta-row">
                    <span class="pill"><i class="fa-solid fa-calendar"></i> {{ $noticia->fecha_publicacion->format('Y-m-d') }}</span>
                    <span class="pill"><i class="fa-solid fa-circle-info"></i> {{ $noticia->subtitulo }}</span>
                    </div>

                    <div class="card-footerpro">
                    <span class="cta">Leer más <i class="fa-solid fa-arrow-right"></i></span>
                    </div>
                </div>
                </a>
            </div>
            @empty
            <div class="alert alert-info w-100 text-center">No hay noticias disponibles.</div>
            @endforelse
        </div>
        </section>

        <!-- CONCIENCIA -->
        <section id="conciencia" class="section-wrap">
        <div class="awareness">
            <h2>Protejamos Nuestra Biodiversidad</h2>
            <p>
            Cochabamba es un corazón ecológico que alberga una extraordinaria variedad de vida.
            Tu participación es clave para conservar nuestros bosques, ríos y especies.
            </p>
            <p class="mt-3 mb-0"><i class="fas fa-map-marker-alt me-2"></i> Cochabamba, Bolivia</p>

            <div class="mt-4">
            <a href="/concientizacion" class="btn btn-success btn-lg">
                <i class="fa-solid fa-hand-holding-heart me-2"></i> Cómo ayudar
            </a>
            </div>
        </div>
        </section>

    </main>

    <!-- FOOTER -->
    <footer>
        <div class="container">
        <div class="row">
            <div class="col-lg-4 footer-col mb-4 mb-lg-0">
            <h5 class="footer-title">INFORMACIÓN DE CONTACTO</h5>
            <ul class="contact-list">
                <li><i class="fas fa-envelope"></i><span>gobernaciondecochabamba@<br>gobernaciondecochabamba.bo</span></li>
                <li><i class="fas fa-phone-alt"></i><span>+ (591) 71701056</span></li>
                <li><i class="fas fa-map-marker-alt"></i><span>Av. Aroma N°: O-327<br>Plaza San Sebastián<br>Edificio del Órgano Ejecutivo</span></li>
            </ul>
            </div>

            <div class="col-lg-4 footer-col mb-4 mb-lg-0 text-center">
            <img src="{{ url('img/logo3.png') }}" alt="Cochabamba Unir Trabajar y Crecer" class="img-fluid footer-logo">
            <h5 class="mt-2 fw-bold text-white">Programa Gestión de la Biodiversidad (PGB)</h5>
            <p class="small text-white-50 mt-2">
                Plataforma dedicada a la conservación de la biodiversidad y la prevención de los ecosistemas.
            </p>
            </div>

            <div class="col-lg-4 footer-col text-lg-end text-center mt-lg-0">
            <h5 class="footer-title">REDES SOCIALES</h5>
            <div class="mt-2">
                <a href="https://www.facebook.com/GobernacionDeCochabamba" target="_blank" class="social-box"><i class="fab fa-facebook-f"></i></a>
                <a href="https://www.youtube.com/@gobernaciondecochabamba8326" target="_blank" class="social-box"><i class="fab fa-youtube"></i></a>
                <a href="https://www.tiktok.com/@gobernaciondecochabamba" target="_blank" class="social-box"><i class="fab fa-tiktok"></i></a>
            </div>
            </div>
        </div>

        <div class="copyright-line">
            Programa Gestión de la Biodiversidad (PGB) © {{ date('Y') }} Todos los derechos reservados | Gobierno Autónomo Departamental de Cochabamba.
        </div>
        </div>

        <a href="https://wa.me/59171701056" target="_blank" class="whatsapp-float">
        <img src="{{ asset('img/wsp.png') }}" alt="WhatsApp">
        </a>
    </footer>

    <!-- JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let map;
        let zonas_j = @json($zonas);
        let tileLayers = {
        osm: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: 'OSM' }),
        hibrido: L.layerGroup([
            L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: 'Esri' }),
            L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Transportation/MapServer/tile/{z}/{y}/{x}')
        ]),
        topo: L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', { attribution: 'OpenTopoMap' })
        };
        let currentLayer;

        document.addEventListener('DOMContentLoaded', function () {
        initMap();
        initSearch();
        });

        function initMap() {
        map = L.map('map', { zoomControl: false, minZoom: 9 }).setView([-17.3895, -66.1568], 13);

        const drawnItems = new L.FeatureGroup();
        currentLayer = tileLayers.hibrido;
        currentLayer.addTo(map);
        map.addLayer(drawnItems);

        if (zonas_j) {
            zonas_j.forEach(element => {
            if (element.historial) {
                element.historial.forEach(poli => {
                if (Array.isArray(poli.coordenadas)) {
                    poli.coordenadas.forEach(subpoli => {
                    if (subpoli.tipo === 'poligono' && Array.isArray(subpoli.coordenadas)) {
                        const latlngs = subpoli.coordenadas.map(coord => [coord.lat, coord.lng]);
                        L.polygon(latlngs, {
                        color: 'yellow',
                        weight: 2,
                        fillColor: 'rgba(0,128,0,0.8)',
                        fillOpacity: 0.5
                        }).addTo(drawnItems).bindPopup(`Área protegida: ${element.nombre}`);
                    }
                    });
                }
                });
            }
            });

            if (drawnItems.getLayers().length > 0) map.fitBounds(drawnItems.getBounds());
        }

        document.querySelectorAll('input[name="basemap"]').forEach(radio => {
            radio.addEventListener('change', function () {
            if (tileLayers[this.value]) {
                map.removeLayer(currentLayer);
                currentLayer = tileLayers[this.value];
                currentLayer.addTo(map);
            }
            });
        });
        }

        function centerMap() { map.setView([-17.3895, -66.1568], 13); }

        function initSearch() {
        var boliviaBbox = [-69.6459, -22.898, -57.453, -9.676];
        var resultsList = document.getElementById("search-results");
        var searchBox = document.getElementById("search-box");

        searchBox.addEventListener("input", async function () {
            const query = this.value.trim();
            resultsList.innerHTML = "";

            if (query.length < 3) { resultsList.style.display = "none"; return; }

            try {
            const url = `https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&bbox=${boliviaBbox.join(',')}&limit=5`;
            const res = await fetch(url).then(r => r.json());

            if (!res.features.length) { resultsList.style.display = "none"; return; }

            res.features.forEach(f => {
                const li = document.createElement("li");
                li.className = "list-group-item list-group-item-action";
                li.textContent = f.properties.name + (f.properties.city ? `, ${f.properties.city}` : "");
                li.onclick = () => {
                const [lng, lat] = f.geometry.coordinates;
                map.setView([lat, lng], 14);
                L.marker([lat, lng]).addTo(map).bindPopup(f.properties.name).openPopup();
                resultsList.style.display = "none";
                searchBox.value = li.textContent;
                };
                resultsList.appendChild(li);
            });

            resultsList.style.display = "block";
            } catch (e) { console.error(e); }
        });

        document.addEventListener("click", (e) => {
            if (!resultsList.contains(e.target) && e.target !== searchBox) resultsList.style.display = "none";
        });
        }

        function showLayerPanel() {
        document.getElementById('layer-panel').classList.add('show');
        document.getElementById('layer-button').style.display = 'none';
        }
        function hideLayerPanel() {
        document.getElementById('layer-panel').classList.remove('show');
        document.getElementById('layer-button').style.display = 'flex';
        }
    </script>

    </body>
    </html>
