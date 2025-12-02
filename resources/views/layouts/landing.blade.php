<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Áreas Protegidas de Cochabamba</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
            padding: 0;
            margin-bottom: 50px;
            position: relative;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .hero-logo {
            position: absolute;
            max-width: 300px;
            width: 80%;
            z-index: 2;
            object-fit: contain;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.3);
            z-index: 1;
        }

        @media (max-width: 768px) {
            .hero-section {
                height: 300px;
                background-size: cover;
                background-position: top center;
            }

            .hero-logo {
                max-width: 200px;
            }
        }

        .welcome-section {
            padding: 80px 0;
            position: relative;
            overflow: hidden;
            background-color: #f8f9fa;
        }

        .welcome-content {
            position: relative;
            z-index: 2;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('{{ asset("img/bolivia.svg") }}') no-repeat center center;
            background-size: 40%;
            opacity: 0.1;
            z-index: 1;
        }

        .welcome-text {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #555;
            text-align: justify;
            max-width: 900px;
            margin: 0 auto;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            position: relative;
        }

        @media (max-width: 768px) {
            .welcome-section::before {
                background-size: 70%;
                opacity: 0.08;
            }

            .welcome-text {
                padding: 20px;
                background-color: rgba(255, 255, 255, 0.9);
            }
        }

        .welcome-title {
            font-size: 1.5rem;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            font-weight: 600;
            position: relative;
            display: inline-block;
        }

        .welcome-title strong {
            color: #3498db;
            font-weight: 700;
        }

        .welcome-text::first-letter {
            float: left;
            font-size: 4.5rem;
            line-height: 0.8;
            margin: 0.2em 0.15em 0 0;
            color: #2c3e50;
            font-weight: bold;
        }

        .zones-section {
            padding: 60px 0;
            background: #fff;
        }

        .section-title {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 2.5rem;
            text-align: center;
            position: relative;
        }

        .section-title:after {
            content: '';
            display: block;
            width: 80px;
            height: 3px;
            background: #3498db;
            margin: 15px auto 0;
        }

        .zone-card {
            transition: all 0.3s ease;
            margin: 0 15px;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .zone-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .zone-card .card-img-top {
            height: 200px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .zone-card:hover .card-img-top {
            transform: scale(1.05);
        }

        .zone-card .card-body {
            padding: 1.5rem;
            background: #fff;
        }

        .zone-card .card-title {
            font-size: 1.2rem;
            color: #2c3e50;
            margin-bottom: 0.75rem;
            font-weight: 600;
        }

        .zone-card .card-text {
            color: #6c757d;
            margin-bottom: 1.25rem;
            font-size: 0.95rem;
        }

        .zone-card .btn {
            background: #3498db;
            border: none;
            padding: 0.5rem 1.25rem;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .zone-card .btn-explore {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            border: none;
            border-radius: 30px;
            padding: 0.5rem 1.5rem;
            font-size: 0.9rem;
            font-weight: 500;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11);
        }

        .zone-card .btn-explore:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(50, 50, 93, 0.2);
            color: white;
        }

        .zone-card .btn-explore i {
            margin-right: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .zone-card .btn-explore:hover i {
            transform: translateX(3px);
        }

        .stats-section {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            position: relative;
            overflow: hidden;
        }

        .stats-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('{{ asset("img/pattern.png") }}') repeat;
            opacity: 0.05;
            z-index: 1;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            transition: all 0.4s ease;
            height: 100%;
            position: relative;
            z-index: 2;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .stat-icon {
            color: #3498db;
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1);
        }

        .stat-number {
            color: #2c3e50;
            font-weight: 700;
            background: linear-gradient(to right, #3498db, #2c3e50);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-label {
            color: #3498db;
            font-size: 0.9rem;
            letter-spacing: 1px;
        }

        .stat-divider {
            width: 50px;
            height: 3px;
            background: linear-gradient(to right, #3498db, #2c3e50);
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-divider {
            width: 70px;
        }

        .stat-description {
            color: #7f8c8d;
        }

        @media (max-width: 768px) {
            .stat-card {
                padding: 1.5rem !important;
            }

            .stat-number {
                font-size: 2.5rem;
            }
        }

        .lightbox .lb-image {
            border-radius: 8px;
        }

        .lightbox .lb-nav a.lb-next,
        .lightbox .lb-nav a.lb-prev {
            opacity: 0.9;
        }

        .lightbox .lb-data .lb-caption {
            font-size: 1rem;
            line-height: 1.5;
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 5%;
            background: rgba(0, 0, 0, 0.1);
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            width: 2rem;
            height: 2rem;
        }

        .site-footer {
        background-color: #007dc3;
        color: white;
        padding: 40px 0 0;
        margin-top: 60px;
    }

    .footer-container {
        max-width: 1300px;
        margin: 0 auto;
        padding: 0 20px;
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 40px;
    }

    .footer-contact-section {
        text-align: left;
    }

    .footer-contact-section .footer-text {
        display: flex;
        align-items: flex-start;
        text-align: left;
        justify-content: flex-start;
    }

    .footer-logo-section {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .footer-logo {
        max-width: 100%;
        height: auto;
        max-height: 120px;
        object-fit: contain;
    }

    .footer-title {
        font-size: 1.3rem;
        margin-bottom: 20px;
        color: white;
        text-align: center;
        font-weight: 600;
    }

    .footer-text {
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        color: white;
        text-align: left;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .footer-text i {
        margin-right: 10px;
        color: white;
        min-width: 20px;
        text-align: center;
    }

    .footer-social-section {
        text-align: center;
    }

    .social-icons {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .social-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: white;
        color: #007dc3;
        transition: all 0.3s ease;
        font-size: 1.1rem;
    }

    .social-icon:hover {
        background-color: #f0f0f0;
        transform: translateY(-3px);
    }

    .whatsapp-float {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 70px;
        height: 70px;
        z-index: 100;
        transition: all 0.3s ease;
    }

    .whatsapp-float:hover {
        transform: scale(1.1);
    }

    .whatsapp-float img {
        width: 100%;
        height: auto;
    }

    .footer-bottom {
        background-color: rgba(0, 0, 0, 0.1);
        padding: 20px 0;
        text-align: center;
        margin-top: 30px;
    }

    .copyright-text {
        margin: 0;
        font-size: 0.9rem;
        color: white;
    }

    /* Responsive Footer */
    @media (max-width: 992px) {
        .footer-container {
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .footer-logo-section {
            grid-column: span 2;
            order: -1;
            margin-bottom: 20px;
        }

        .footer-logo {
            max-height: 100px;
        }
    }

    @media (max-width: 768px) {
        .footer-container {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .footer-logo-section {
            grid-column: span 1;
        }

        .footer-contact-section,
        .footer-social-section {
            text-align: center;
        }

        .footer-contact-section .footer-text {
            justify-content: center;
            text-align: center;
        }

        .footer-text {
            justify-content: center;
        }

        .whatsapp-float {
            width: 60px;
            height: 60px;
            bottom: 20px;
            right: 20px;
        }
    }

    @media (max-width: 576px) {
        .footer-logo {
            max-height: 80px;
        }

        .footer-title {
            font-size: 1.2rem;
        }

        .footer-text {
            font-size: 0.9rem;
        }

        .whatsapp-float {
            width: 50px;
            height: 50px;
        }
    }
    </style>
</head>
<body>
    @yield('content')

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-contact-section">
                <h3 class="footer-title">INFORMACIÓN DE CONTACTO</h3>
                <p class="footer-text"><i class="fas fa-map-marker-alt"></i> Av. Aroma N°: O-327 - Plaza San Sebastián Edificio del Organo Ejecutivo</p>
                <p class="footer-text"><i class="fas fa-phone-alt"></i> Teléfonos: 591 4 4500530</p>
                <p class="footer-text"><i class="fas fa-envelope"></i>gobernaciondecochabamba@gobernaciondecochabamba.bo</p>
            </div>

            <!-- Logo (Centro) -->
            <div class="footer-logo-section">
                <img src="{{ asset('img/logo3.png') }}" alt="Logo" class="footer-logo">
            </div>

            <!-- Canales de Comunicación (Derecha) -->
            <div class="footer-social-section">
                <h3 class="footer-title">CANALES DE COMUNICACIÓN</h3>
                <div class="social-icons">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p class="copyright-text">© 2025, Gobierno Autónomo Departamental de Cochabamba.</p>
        </div>
        <a href="https://wa.me/59168774551" target="_blank" class="whatsapp-float">
            <img src="{{ asset('img/wsp.png') }}" alt="WhatsApp">
        </a>
    </footer>

    <!-- Scripts deben ir ANTES del cierre de body -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    <!-- Configuración de Lightbox -->
    <script>
        $(document).ready(function() {
            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true,
                'albumLabel': 'Imagen %1 de %2',
                'fadeDuration': 300,
                'imageFadeDuration': 300,
                'disableScrolling': true
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
