<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ballet Folclórico de Cochabamba</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <!-- Google Fonts: Playfair for elegance, Inter for clarity -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="main-header glass">
        <div class="container">
            <h1 class="logo">BFC</h1>
            <button class="nav-toggle" id="navToggle" aria-label="Abrir menú">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <nav class="main-nav" id="mainNav">
                <ul>
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="#historia">Historia</a></li>
                    <li><a href="#contacto">Contacto</a></li>
                    <li><a href="login.php" class="btn-login">Acceso Bailarines</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <!-- Hero / Carousel Section -->
        <section id="inicio" class="hero-carousel">
            <div class="carousel-container">
                <div class="carousel-slide active" style="background-image: url('assets/images/hero1.jpg');">
                    <div class="carousel-content">
                        <h2>Pasión por nuestra cultura</h2>
                        <p>Llevando el folklore boliviano al mundo</p>
                    </div>
                </div>
                <div class="carousel-slide" style="background-image: url('assets/images/hero2.jpg');">
                    <div class="carousel-content">
                        <h2>Danza y Tradición</h2>
                        <p>Expresiones artísticas de Cochabamba</p>
                    </div>
                </div>
                <div class="carousel-slide" style="background-image: url('assets/images/hero3.jpg');">
                    <div class="carousel-content">
                        <h2>Únete al BFC</h2>
                        <p>Formando bailarines de excelencia</p>
                    </div>
                </div>
                <button class="carousel-btn prev">&#10094;</button>
                <button class="carousel-btn next">&#10095;</button>
            </div>
        </section>

        <!-- Historia Section -->
        <section id="historia" class="section-padding">
            <div class="container">
                <h2 class="section-title">Nuestra Historia</h2>
                <div class="content-grid">
                    <div class="text-block">
                        <p>El Ballet Folclórico de Cochabamba (BFC) nace con la misión de preservar y difundir las danzas tradicionales de nuestra región y de Bolivia entera. Con años de trayectoria, hemos pisado escenarios nacionales e internacionales, llevando en alto el nombre de nuestra cultura.</p>
                        <p>Desde la cueca valluna hasta los ritmos del oriente, nuestro repertorio es un viaje por la diversidad de nuestro país.</p>
                    </div>
                    <div class="image-block">
                        <!-- Placeholder for history image -->
                        <div class="placeholder-img">Historia BFC</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Misión Section -->
        <section class="mission-section">
            <div class="container">
                <div class="mission-card">
                    <h3>Misión</h3>
                    <p>Rescatar, revalorizar y promover el patrimonio cultural boliviano a través de la danza, formando artistas integrales comprometidos con su identidad.</p>
                </div>
                <div class="mission-card">
                    <h3>Visión</h3>
                    <p>Ser el referente artístico más importante de la danza folclórica en Bolivia, reconocidos por nuestra calidad técnica y compromiso cultural.</p>
                </div>
            </div>
        </section>

        <!-- Contacto Section -->
        <section id="contacto" class="section-padding bg-dark">
            <div class="container text-center">
                <h2 class="section-title text-white">Contáctanos</h2>
                <p class="text-white">¿Quieres formar parte del elenco o contratarnos?</p>
                <div class="contact-info">
                    <p>📍 Pasaje F. #123 entre Santa Cruz y Tomas Frias, Cochabamba</p>
                    <p>📞 +591 72706455</p>
                    <p>✉️ contacto@bfc.bo</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="main-footer">
        <p>&copy; <?php echo date('Y'); ?> Ballet Folclórico de Cochabamba. Todos los derechos reservados.</p>
    </footer>

    <script src="js/main.js?v=<?php echo time(); ?>"></script>
</body>
</html>
