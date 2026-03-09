<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Protejamos nuestra biodiversidad - Cochabamba, Bolivia</title>

    {{-- Estilos (Bootstrap y FontAwesome) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/concientizacion.css') }}?v=1.1">

    <style>
        .action-btn {
            background-color: #2f55d4 !important;
            border: 1px solid #2f55d4 !important;
            box-shadow: none !important;
            background-image: none !important;
            color: #ffffff !important;
            opacity: 1 !important;
        }

        /* Efecto al pasar el mouse */
        .action-btn:hover,
        .action-btn:focus,
        .action-btn:active {
            background-color: #2442a8 !important;
            border-color: #2442a8 !important;
            box-shadow: none !important;
            transform: none !important;
        }

        /* Reset básico para asegurar pantalla completa */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        /* Estilos de Cabecera y Navegación */
        header {
            background: #9471f3;
            padding: 10px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            display: flex;
            align-items: center;
            font-size: 18px;
            font-weight: bold;
            margin-left: 10px;
            color: white;
        }

        .logo i {
            margin-right: 8px;
            color: white;
        }

        /* Navegación */
        nav ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        nav ul li {
            margin-left: 20px;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            font-weight: 650;
            transition: opacity 0.2s;
            font-size: 16px;
        }

        nav ul li a:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        /* Hero Section (Título Principal) */
        .hero {
            text-align: center;
            padding: 60px 20px;
            background: white;
            margin-bottom: 30px;
        }

        .hero h1 {
            color: #ddd;
            margin-bottom: 20px;
        }

        .hero p {
            max-width: 800px;
            margin: 0 auto 20px auto;
            line-height: 1.6;
            color: #ddd;
        }

        .location-tag {
            color: #ffffff;
            font-weight: bold;
        }

        /* Estilos de Comentarios */
        .comment {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
            background-color: #ffffff;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
            font-size: 0.9em;
            color: #555;
        }

        .comment-header strong i {
            color: #9471f3;
        }

        .replies {
            margin-left: 25px;
            margin-top: 10px;
            border-left: 3px solid #e9ecef;
            padding-left: 10px;
        }

        /* Contenedores Generales */
        .section-title {
            text-align: center;
            color: #9471f3;
            padding-bottom: 8px;
            border-bottom: 2px solid rgba(76, 175, 80, 0.5);
            margin-bottom: 30px;
            margin-top: 40px;
            font-size: 1.8em;
            font-weight: 700;
        }

        /* Tarjetas Aprobadas (Sugerencias/Reportes) */
        .card.suggestion-approved {
            border: 1px solid #9471f3;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.1);
        }

        .card.suggestion-approved .card-header {
            background-color: #9471f3;
            color: white;
            font-weight: bold;
        }

        .card.report-approved {
            border: 1px solid #dc3545;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.1);
        }

        .card.report-approved .card-header {
            background-color: #dc3545;
            color: white;
            font-weight: bold;
        }

        .card.video-approved {
            border: 1px solid #0d6efd;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.1);
        }

        .card.video-approved .card-header {
            background-color: #0d6efd;
            color: white;
            font-weight: bold;
        }

        .card.image-approved {
            border: 1px solid #ffc107;
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.1);
        }

        .card.image-approved .card-header {
            background-color: #ffc107;
            color: black;
            font-weight: bold;
        }

        .card.image-approved img {
            max-height: 250px;
            object-fit: cover;
        }

        /* Botones de Acción */
        .user-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .action-btn {
            border: none;
            background: white;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 120px;
        }

        .action-btn i {
            font-size: 24px;
            margin-bottom: 8px;
            color: #9471f3;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        /* FOOTER estilo institucional */
        .pgb-footer {
            background: linear-gradient(180deg, #0a86c5 0%, #0b5ea9 60%, #0a4f93 100%);
            color: #ffffff;
            padding: 48px 0 22px;
            position: relative;
            overflow: hidden;
        }

        .pgb-footer__col { margin-bottom: 32px}

        .pgb-footer__title {
            font-weight: 800;
            font-size: 13px;
            letter-spacing: .06em;
            text-transform: uppercase;
            margin-bottom: 16px;
            opacity: .95;
        }

        .pgb-footer__item {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            margin-bottom: 14px;
        }

        .pgb-footer__icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: rgba(255, 255, 255, .14);
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 34px;
            box-shadow: 0 10px 22px rgba(0, 0, 0, .10);
        }

        .pgb-footer__text {
            line-height: 1.35;
            font-size: 14px;
            opacity: .95;
        }

        /* Centro */
        .pgb-footer__brand {
            display: flex;
            align-items: center;
            flex-direction: column;
            gap: 18px;
            margin-bottom: 18px;
        }

        .pgb-footer__logo {
            width: 100px;
            height: 100px;
            object-fit: contain;
            border-radius: 28px;
            background: rgba(255, 255, 255, .08);
            padding: 12px;
        }

        .pgb-footer__brandname {
            font-weight: 1200;
            letter-spacing: .22em;
            font-size: 18px;
            line-height: 1.1;
        }

        .pgb-footer__brandslogan {
            font-weight: 700;
            letter-spacing: .08em;
            font-size: 12px;
            opacity: .95;
        }

        .pgb-footer__programname {
            font-weight: 900;
            font-size: 16px;
            margin-top: 8px;
        }

        .pgb-footer__programdesc {
            font-size: 14px;
            opacity: .95;
            max-width: 360px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Redes sociales */
        .pgb-footer__socialwrap {
            display: flex;
            gap: 22px;
            justify-content: center;
        }

        .pgb-footer__socialbtn {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, .22);
            background: rgba(255, 255, 255, .10);
            box-shadow: 0 10px 24px rgba(0, 0, 0, .12);
            transition: transform .15s ease, background .15s ease, border-color .15s ease;
        }

        .pgb-footer__socialbtn:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, .20);
            border-color: rgba(255, 255, 255, .35);
            color: #ffffff;
        }

        /* Dividir + copyright */
        .pgb-footer__divider {
            height: 1px;
            background: rgba(255, 255, 255, .28);
            margin: 18px 0 14px;
        }

        .pgb-footer__copyright {
            font-size: 13px;
            opacity: .95;
        }

        /* Responsive tweaks */
        @media (max-width: 768px) {
            .pgb-footer { padding: 38px 0 20px; }
            .pgb-footer__brand { flex-direction: column }
            .pgb-footer__brandname { letter-spacing: .12em; font-size: 14px }
        }
    </style>
</head>

<body>

    <header>
        <div class="container">
            <div class="header-content">
                <img src="{{ url('img/logo3.png') }}" width="210px" alt="Gobernación de Cochabamba"
                    style="padding: 5px; border-radius: 5px;">

                <div class="logo">
                    <i class="fas fa-leaf"></i>
                    <span>Protejamos Nuestra Biodiversidad</span>
                </div>

                <nav>
                    <ul>
                        <li><a href="{{ url('concientizacion') }}"><i class="fas fa-home"></i> Inicio</a></li>
                        <li><a href="#videos"><i class="fas fa-video"></i> Videos</a></li>
                        <li><a href="#participa"><i class="fas fa-hands-helping"></i> Participa</a></li>
                        <li><a href="#contacto"><i class="fas fa-envelope"></i> Contacto</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <section class="hero"
        style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('img/condores.jpeg') }}'); background-size: cover; background-position: center;">
        <div class="container">
            <h1>Protejamos la biodiversidad de Cochabamba</h1>
            <p>Proteger la biodiversidad de Cochabamba significa defender la vida en todas sus formas. Cada especie
                cumple
                una función vital dentro del ecosistema: las plantas purifican el aire, los bosques regulan el clima y
                los animales mantienen el equilibrio natural. La pérdida de cualquiera de ellos afecta directamente la
                calidad de vida humana.</p>
            <div class="location-tag">
                <i class="fas fa-map-marker-alt"></i> Cochabamba - Bolivia
            </div>
        </div>
    </section>

    <div class="container">

        <section class="videos-section" id="videos">
            <h2 class="section-title"><i class="fas fa-video"></i> Videos de Concientización</h2>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                @forelse ($concientizaciones as $video)
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <video controls class="card-img-top" style="background: black; max-height: 300px;">
                                <source src="{{ asset('storage/' . $video->video_path) }}" type="video/mp4">
                                Tu navegador no soporta el formato de video.
                            </video>
                            <div class="card-body">
                                <h5 class="card-title">{{ $video->titulo }}</h5>
                                <p class="card-text">{{ $video->descripcion }}</p>
                            </div>
                            <div class="card-footer text-muted d-flex justify-content-between">
                                <span><i class="far fa-calendar"></i> Fecha: {{ $video->created_at->format('d/m/Y') }}</span>
                                <span><i class="fas fa-tag"></i> {{ ucfirst($video->categoria) }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>No hay videos oficiales publicados todavía.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="user-section" id="participa">
            <h2 class="section-title"><i class="fas fa-users"></i> Participación Ciudadana</h2>

            <div class="user-actions">
                <button class="action-btn" data-bs-toggle="modal" data-bs-target="#suggestionModal">
                    <i class="fas fa-lightbulb"></i>
                    <span>Sugerir tema</span>
                </button>

                <button class="action-btn" data-bs-toggle="modal" data-bs-target="#reportModal">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Reportar problema</span>
                </button>

                <button class="action-btn" data-bs-toggle="modal" data-bs-target="#mediaModal">
                    <i class="fas fa-camera"></i>
                    <span>Subir Fotos/Videos</span>
                </button>
            </div>

            <div class="card shadow-sm p-4 mb-5 bg-white rounded">
                <h4 class="mb-3"><i class="fas fa-comments text-primary"></i> Deja tu comentario</h4>
                <form id="commentForm">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Tu nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control"
                            placeholder="Ej. Juan Pérez" required>
                    </div>

                    <div class="mb-3">
                        <label for="comentario" class="form-label">Tu comentario</label>
                        <textarea id="comentario" name="comentario" class="form-control" rows="3" placeholder="Escribe aquí tu opinión..."
                            required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Publicar
                        comentario</button>
                </form>

                <hr class="my-4">

                <div id="commentsList" class="comments-list">
                </div>
            </div>
        </section>

        <section class="suggestions-view-section">
            <h2 class="section-title" style="border-bottom-color: #28a745;">
                <i class="fas fa-lightbulb"></i> Sugerencias Aprobadas
            </h2>

            <div id="approvedSuggestionsList" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4"></div>
            <div id="noApprovedSuggestionsAlert" class="alert alert-light text-center w-100" style="display:none;">
                No hay sugerencias aprobadas aún.
            </div>
        </section>

        <section class="reports-view-section">
            <h2 class="section-title" style="border-bottom-color: #dc3545;">
                <i class="fas fa-exclamation-circle"></i> Reportes Ambientales
            </h2>

            <div id="approvedReportsList" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <div id="reportsLoadingStatus" class="col-12 text-center text-danger">
                    <i class="fas fa-spinner fa-spin"></i> Cargando...
                </div>
            </div>

            <div id="noApprovedReportsAlert" class="alert alert-light text-center w-100" style="display:none;">No hay
                reportes aprobados aún.
            </div>
        </section>

        <section class="videos-view-section">
            <h2 class="section-title" style="border-bottom-color: #0d6efd;">
                <i class="fas fa-video"></i> Videos de la Comunidad
            </h2>

            <div id="approvedVideosList" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <div id="videosLoadingStatus" class="col-12 text-center text-primary">
                    <i class="fas fa-spinner fa-spin"></i> Cargando...
                </div>
            </div>

            <div id="noApprovedVideosAlert" class="alert alert-light text-center w-100" style="display:none;">No hay
                videos de la comunidad aún.
            </div>
        </section>

        <section class="images-view-section">
            <h2 class="section-title" style="border-bottom-color: #ffc107;">
                <i class="fas fa-images"></i> Galería de Imágenes
            </h2>

            <div id="approvedImagesList" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <div id="imagesLoadingStatus" class="col-12 text-center text-warning">
                    <i class="fas fa-spinner fa-spin"></i> Cargando...
                </div>
            </div>

            <div id="noApprovedImagesAlert" class="alert alert-light text-center w-100" style="display:none;">No hay
                imágenes de la comunidad aún.
            </div>
        </section>

    </div>
    <div class="modal fade" id="suggestionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-lightbulb"></i> Nueva Sugerencia</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <br>

                <h3 style="text-align: center">Aquí puedes registrar o sugerir tus temas sobre la biodiversidad.</h3>

                <div class="modal-body">
                    <form id="suggestionForm">
                        <div class="mb-3">
                            <label class="form-label">Nombre Completo:</label>
                            <input type="text" id="sugerencia_nombre" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tema/Sugerencia:</label>
                            <input type="text" id="sugerencia_tema" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Detalle de la Sugerencia:</label>
                            <textarea id="sugerencia_contenido" class="form-control" rows="4" required></textarea>
                        </div>

                        <button type="submit"
                            class="btn btn-success w-100 d-flex justify-content-center align-items-center"><i
                                class="fas fa-paper-plane"></i> Enviar Sugerencia</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Reportar Problema</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <br>

                <h3 style="text-align: center;">Aquí pudes reportar los problemas ambientales.</h3>

                <div class="modal-body">
                    <form id="reportForm">
                        <div class="mb-3">
                            <label class="form-label">Nombre Completo:</label>
                            <input type="text" id="reporte_nombre" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ubicación/Título:</label>
                            <input type="text" id="reporte_tema" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción del Problema:</label>
                            <textarea id="reporte_contenido" class="form-control" rows="4" required></textarea>
                        </div>

                        <button type="submit"
                            class="btn btn-danger w-100 d-flex justify-content-center align-items-center gap-2"><i
                                class="fas fa-exclamation-triangle"></i> Enviar Reporte</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mediaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-upload"></i> Subir Contenido</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <br>

                <h3 style="text-align: center;">Aquí puedes subir tus videos o imagenes sobre la Biodiversidad.</h3>

                <div class="modal-body">
                    <form id="mediaForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Nombre Completo:</label>
                            <input type="text" id="media_nombre" name="nombre" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Título de la Biodiversidad:</label>
                            <input type="text" id="media_titulo" name="titulo" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción Detallada:</label>
                            <textarea id="media_descripcion" name="descripcion" class="form-control" rows="2" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Archivo (Foto o Video):</label>
                            <input type="file" id="media_file" name="archivo" class="form-control"
                                accept="image/*,video/mp4" required>
                        </div>

                        <button type="submit"
                            class="btn btn-primary w-100 d-flex justify-content-center align-items-center gap-2">
                            <i class="fas fa-upload"></i>
                            <div>Subir Archivo</div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL PARA VER MEDIA EN GRANDE --}}
    <div class="modal fade" id="viewMediaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-header border-0 p-0">
                    <button type="button" class="btn-close btn-close-white ms-auto mb-2" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img id="modalImageDisplay" src="" class="img-fluid rounded shadow-lg"
                        style="display: none; max-height: 85vh; margin: 0 auto;">

                    <h5 id="modalMediaTitle" class="text-white mt-3 text-shadow"
                        style="text-shadow: 0 2px 4px rgba(0,0,0,0.8);"></h5>
                </div>
            </div>
        </div>
    </div>

    <footer id="contacto" class="pgb-footer">
        <div class="container">
            <div class="row align-items-start text-center text-md-start">
                <div class="col-md-4 pgb-footer__col pgb-footer__contact">
                    <h6 class="pgb-footer__title">INFORMACIÓN DE LA GOBERNACIÓN DE COCHABAMBA</h6>
                    <div class="pgb-footer__item">
                        <span class="pgb-footer__icon"><i class="fas fa-envelope"></i></span>
                        <div class="pgb-footer__text">
                            gobernacióndecochabamba@ <br class="d-none d-md-block">
                            gobernacióndecochabamba.bo
                        </div>
                    </div>

                    <div class="pgb-footer__item">
                        <span class="pgb-footer__icon"><i class="fas fa-phone-alt"></i></span>
                        <div class="pgb-footer__text">+ (591) 71701056</div>
                    </div>

                    <div class="pgb-footer__item">
                        <span class="pgb-footer__icon"><i class="fas fa-map-marker-alt"></i></span>
                        <div class="pgb-footer__text">
                            Av. Aroma N°: 0-327 <br>
                            Plaza San Sebastian <br>
                            Edificio del Órgano Ejecutivo Departamental <br>
                        </div>
                    </div>
                </div>

                <!-- Centro del logo -->
                <div class="col-md-4 pgb-footer__col pgb-footer__center text-center">
                    <div class="pgb-footer__brand">
                        <img src="{{ asset('img/MARCA GESTION.png') }}" width="150px" alt="Cochabamba" class="pgb-footer__logo">
                    </div>

                    <div class="pgb-footer__program">
                        <div class="pgb-footer__programname">Programa Gestión de la Biodiversidad (PGB)</div>
                        <div class="pgb-footer__programdesc">
                            Plataforma dedicada a la conservación de la biodiversidad y la prevención de los riesgos ambientales en Cochabamba.
                        </div>
                    </div>
                </div>

                <!-- Redes Sociales -->
                <div class="col-md-4 pgb-footer__col pgb-footer__social text-center text-md-end">
                    <h6 class="pgb-footer__title">REDES SOCIALES</h6>

                    <div class="pgb-footer__socialwrap justify-content-center justify-content-md-end">
                        <a class="pgb-footer__socialbtn" target="_blank" 
                        href="https://www.facebook.com/GobernacionDeCochabamba" aria-label="Facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a class="pgb-footer__socialbtn" target="_blank"
                        href="https://www.youtube.com/@gobernaciondecochabamba8326" aria-label="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a class="pgb-footer__socialbtn" target="_blank"
                        href="https://www.tiktok.com/@gobernaciondecochabamba" aria-label="TikTok">
                        <i class="fab fa-tiktok"></i>
                    </a>
                    </div>
                </div>
            </div>

            <div class="pgb-footer__divider"></div>

            <div class="pgb-footer__copyright text-center">
                Programa Gestión de la Biodiversidad - Gobernación de Cochabamba &copy; {{ date('Y') }}. Todos los derechos reservados.
            </div>
        </div>
    </footer>

    {{-- SCRIPTS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.2/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            'use strict';

            // MAPA DE RUTAS PÚBLICAS (CORREGIDO)
            const ROUTE_MAP = {
                'comentarios.index': '{{ route('public.comentarios.index') }}',
                'comentarios.store': '{{ route('public.comentarios.store') }}',
                'sugerencias.index': '{{ route('public.sugerencias.index') }}',
                'sugerencias.store': '{{ route('public.sugerencias.store') }}',
                'reportes.index': '{{ route('public.reportes.index') }}',
                'reportes.store': '{{ route('public.reportes.store') }}',
                'archivos.index': '{{ route('public.archivos.index') }}',
                'archivos.store': '{{ route('public.archivos.store') }}',
            };

            // CONFIGURACIÓN AXIOS CSRF
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            if (tokenMeta) {
                axios.defaults.headers.common['X-CSRF-TOKEN'] = tokenMeta.getAttribute('content');
            }

            const apiCall = async (routeName, method = 'get', data = {}, config = {}) => {
                if (!ROUTE_MAP[routeName]) console.error("Ruta no definida:", routeName);
                return await axios({
                    method,
                    url: ROUTE_MAP[routeName],
                    data,
                    ...config
                });
            };

            const showSwal = (icon, title, text) => Swal.fire({
                icon,
                title,
                text,
                timer: 3000,
                timerProgressBar: true
            });

            // --- LÓGICA COMENTARIOS ---
            const commentsList = document.getElementById("commentsList");
            const commentForm = document.getElementById("commentForm");

            const renderComments = (comments) => {
                if (!comments.length) return '<p class="text-center text-muted">Sé el primero en comentar.</p>';

                return comments.map(c => `
        <div class="comment p-3 mb-2 border rounded bg-light">
            <div class="d-flex justify-content-between">
                <strong><i class="fas fa-user-circle"></i> ${c.nombre}</strong>
                
                <small class="text-muted">
                    <i class="far fa-calendar-alt"></i> Fecha: ${new Date(c.created_at).toLocaleDateString()}
                </small>
            </div>
            <p class="mb-0 mt-1">${c.comentario}</p>

            ${(c.respuestas_aprobadas || []).map(r => `
                                    <div class="replies mt-2 ms-4 border-start ps-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small><strong>Admin:</strong> ${r.comentario}</small>
                                            
                                            <small class="text-muted" style="font-size: 0.8em;">
                                                <i class="far fa-calendar-alt"></i> ${new Date(r.created_at).toLocaleDateString()}
                                            </small>
                                        </div>
                                    </div>
                                `).join('')}
        </div>
    `).join('');
            };

            const loadComments = async () => {
                try {
                    const res = await apiCall('comentarios.index');
                    commentsList.innerHTML = renderComments(res.data.data || []);
                } catch (e) {
                    console.error(e);
                }
            };

            if (commentForm) {
                commentForm.addEventListener("submit", async (e) => {
                    e.preventDefault();
                    const btn = e.target.querySelector('button');
                    btn.disabled = true;
                    try {
                        await apiCall('comentarios.store', 'post', {
                            nombre: document.getElementById("nombre").value,
                            comentario: document.getElementById("comentario").value
                        });
                        commentForm.reset();
                        showSwal('success', 'Enviado', 'Tu comentario espera aprobación.');
                        loadComments();
                    } catch (e) {
                        showSwal('error', 'Error', 'No se pudo enviar el comentario.');
                    }
                    btn.disabled = false;
                });
            }

            // --- LÓGICA SUGERENCIAS ---
            const loadSuggestions = async () => {
                const container = document.getElementById("approvedSuggestionsList");
                try {
                    const res = await apiCall('sugerencias.index');
                    const items = res.data.data || [];
                    if (items.length === 0) {
                        document.getElementById("noApprovedSuggestionsAlert").style.display = 'block';
                        return;
                    }
                    container.innerHTML = items.map(s => `
            <div class="col">
                <div class="card h-100 suggestion-approved">
                    <div class="card-header text-white"><i class="fas fa-check"></i> ${s.titulo}</div>
                    <div class="card-body"><p class="card-text">${s.contenido}</p></div>
                    
                    <div class="card-footer text-muted d-flex justify-content-between align-items-center">
                        <small>Por: ${s.nombre}</small>
                        <small><i class="far fa-calendar-alt"></i> Fecha: ${new Date(s.created_at).toLocaleDateString()}</small>
                    </div>

                </div>
            </div>
        `).join('');
                } catch (e) {
                    console.error(e);
                }
            };

            const suggestionForm = document.getElementById("suggestionForm");
            if (suggestionForm) {
                suggestionForm.addEventListener("submit", async (e) => {
                    e.preventDefault();
                    try {
                        await apiCall('sugerencias.store', 'post', {
                            nombre: document.getElementById("sugerencia_nombre").value,
                            titulo: document.getElementById("sugerencia_tema").value,
                            contenido: document.getElementById("sugerencia_contenido").value
                        });
                        bootstrap.Modal.getInstance(document.getElementById('suggestionModal')).hide();
                        suggestionForm.reset();
                        showSwal('success', 'Enviado', 'Sugerencia enviada con éxito.');
                    } catch (e) {
                        showSwal('error', 'Error', 'Revisa los campos.');
                    }
                });
            }

            // --- LÓGICA REPORTES ---
            const loadReports = async () => {
                const container = document.getElementById("approvedReportsList");
                document.getElementById("reportsLoadingStatus").style.display = 'none';
                try {
                    const res = await apiCall('reportes.index');
                    const items = res.data.data || [];
                    if (items.length === 0) {
                        document.getElementById("noApprovedReportsAlert").style.display = 'block';
                        return;
                    }
                    container.innerHTML = items.map(r => `
            <div class="col">
                <div class="card h-100 report-approved">
                    <div class="card-header text-white"><i class="fas fa-map-marker-alt"></i> ${r.titulo}</div>
                    <div class="card-body"><p class="card-text">${r.contenido}</p></div>
                    
                    <div class="card-footer text-muted d-flex justify-content-between align-items-center">
                        <small>Reporta: ${r.nombre}</small>
                        <small><i class="far fa-calendar-alt"></i> Fecha: ${new Date(r.created_at).toLocaleDateString()}</small>
                    </div>

                </div>
            </div>
        `).join('');
                } catch (e) {
                    console.error(e);
                }
            };

            const reportForm = document.getElementById("reportForm");
            if (reportForm) {
                reportForm.addEventListener("submit", async (e) => {
                    e.preventDefault();
                    try {
                        await apiCall('reportes.store', 'post', {
                            nombre: document.getElementById("reporte_nombre").value,
                            titulo: document.getElementById("reporte_tema").value,
                            contenido: document.getElementById("reporte_contenido").value
                        });
                        bootstrap.Modal.getInstance(document.getElementById('reportModal')).hide();
                        reportForm.reset();
                        showSwal('success', 'Enviado', 'Reporte registrado.');
                    } catch (e) {
                        showSwal('error', 'Error', 'Revisa los campos.');
                    }
                });
            }

            // --- NUEVA FUNCIÓN PARA ABRIR EL MODAL ---
            window.openImageModal = (url, title) => {
                const img = document.getElementById('modalImageDisplay');
                const titleEl = document.getElementById('modalMediaTitle');

                img.src = url;
                img.style.display = 'block';
                titleEl.innerText = title;

                // Usar Bootstrap 5 para abrir el modal
                new bootstrap.Modal(document.getElementById('viewMediaModal')).show();
            };

            // --- ACTUALIZACIÓN EN LA FUNCIÓN LOADMEDIA ---
            const loadMedia = async () => {
                const vidContainer = document.getElementById("approvedVideosList");
                const imgContainer = document.getElementById("approvedImagesList");

                document.getElementById("videosLoadingStatus").style.display = 'none';
                document.getElementById("imagesLoadingStatus").style.display = 'none';

                try {
                    const res = await apiCall('archivos.index');
                    const media = res.data.data || res.data;
                    const videos = media.filter(m => m.mime_type.startsWith('video'));
                    const images = media.filter(m => m.mime_type.startsWith('image'));

                    // RENDERIZADO DE VIDEOS
                    if (videos.length > 0) {
                        vidContainer.innerHTML = videos.map(v => `
                <div class="col">
                    <div class="card h-100 video-approved">
                        <div class="card-header text-white">Título: ${v.titulo}</div>
                        <video controls class="w-100">
                            <source src="/storage/${v.ruta_archivo}" type="${v.mime_type}">
                        </video>
                        
                        <div class="card-footer text-muted d-flex justify-content-between align-items-center">
                            <small class="text-truncate" style="max-width: 60%;" title="${v.descripcion}">
                                Descripción: ${v.descripcion}
                            </small>
                            <small>
                                <i class="far fa-calendar-alt"></i> Fecha: ${new Date(v.created_at).toLocaleDateString()}
                            </small>
                        </div>
                    </div>
                </div>`).join('');
                    } else {
                        document.getElementById("noApprovedVideosAlert").style.display = 'block';
                    }

                    // RENDERIZADO DE IMÁGENES
                    if (images.length > 0) {
                        imgContainer.innerHTML = images.map(i => `
                <div class="col">
                    <div class="card h-100 image-approved">
                        <div class="card-header">Título: ${i.titulo}</div>
                        <div style="overflow: hidden; cursor: pointer;" 
                            onclick="openImageModal('/storage/${i.ruta_archivo}', '${i.titulo}')">
                            <img src="/storage/${i.ruta_archivo}" 
                                class="card-img-top" 
                                style="height: 250px; object-fit: cover; transition: transform 0.3s;"
                                onmouseover="this.style.transform='scale(1.05)'"
                                onmouseout="this.style.transform='scale(1)'">
                        </div>
                        
                        <div class="card-footer text-muted d-flex justify-content-between align-items-center">
                            <small class="text-truncate" style="max-width: 60%;" title="${i.descripcion}">
                                Descripción: ${i.descripcion}
                            </small>
                            <small>
                                <i class="far fa-calendar-alt"></i> Fecha: ${new Date(i.created_at).toLocaleDateString()}
                            </small>
                        </div>
                    </div>
                </div>`).join('');
                    } else {
                        document.getElementById("noApprovedImagesAlert").style.display = 'block';
                    }

                } catch (e) {
                    console.error(e);
                }
            };

            const mediaForm = document.getElementById("mediaForm");
            if (mediaForm) {
                mediaForm.addEventListener("submit", async (e) => {
                    e.preventDefault();
                    const btn = e.target.querySelector('button');
                    btn.disabled = true;
                    btn.innerText = 'Subiendo...';
                    try {
                        await apiCall('archivos.store', 'post', new FormData(mediaForm), {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        });
                        bootstrap.Modal.getInstance(document.getElementById('mediaModal')).hide();
                        mediaForm.reset();
                        showSwal('success', 'Subido', 'Archivo enviado para revisión.');
                    } catch (e) {
                        showSwal('error', 'Error', 'Fallo al subir.');
                    }
                    btn.disabled = false;
                    btn.innerText = 'Subir Archivo';
                });
            }

            // Init
            loadComments();
            loadSuggestions();
            loadReports();
            loadMedia();
        });
    </script>
</body>

</html>
