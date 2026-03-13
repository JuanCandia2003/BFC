<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ballet Folclórico de Cochabamba - BFC</title>
    <meta name="description" content="El Ballet Folclórico de Cochabamba (BFC) preserva y difunde las danzas tradicionales de Bolivia.">
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header with Overlay -->
    <header class="header" id="header">
        <div class="header-overlay"></div>
        <div class="header-inner">
            <a href="index.php" class="logo">Ballet Folklórico de Cochabamba</a>
            
            <button class="nav-toggle" id="navToggle" aria-label="Menú">
                <span></span>
                <span></span>
                <span></span>
            </button>
            
            <nav class="nav" id="mainNav">
                <a href="#inicio" class="nav-link">Inicio</a>
                <a href="#presentaciones" class="nav-link">Presentaciones</a>
                <a href="#viajes" class="nav-link">Viajes</a>
                <a href="#historia" class="nav-link">Historia</a>
                <a href="#contacto" class="nav-link">Contacto</a>
                <a href="login.php" class="nav-btn">Bailarines</a>
            </nav>
        </div>
    </header>

    <main>
        <!-- Hero / Main Carousel -->
        <section id="inicio" class="hero">
            <div class="carousel" id="carousel">
                <div class="slide active" style="background-image: url('assets/images/hero1.jpg');"></div>
                <div class="slide" style="background-image: url('assets/images/hero2.jpg');"></div>
                <div class="slide" style="background-image: url('assets/images/hero3.jpg');"></div>
            </div>
            
            <div class="hero-content">
                <h1 class="hero-title">Ballet Folclórico de Cochabamba</h1>
                <p class="hero-subtitle">Preservando y difundiendo las danzas tradicionales de Bolivia</p>
            </div>
            
            <div class="carousel-nav">
                <button class="carousel-dot active" data-index="0"></button>
                <button class="carousel-dot" data-index="1"></button>
                <button class="carousel-dot" data-index="2"></button>
            </div>
        </section>

        <!-- Presentaciones Carousel -->
        <section id="presentaciones" class="gallery-section">
            <div class="container">
                <div class="gallery-header">
                    <h2 class="gallery-title">Presentaciones</h2>
                    <p class="gallery-subtitle">Nuestros espectáculos y functiones</p>
                </div>
                
                <div class="gallery-carousel" id="presentacionesCarousel">
                    <div class="gallery-track">
                        <div class="gallery-item">
                            <div class="gallery-img" style="background-image: url('assets/images/presentacion1.jpg');"></div>
                        </div>
                        <div class="gallery-item">
                            <div class="gallery-img" style="background-image: url('assets/images/presentacion2.jpg');"></div>
                        </div>
                        <div class="gallery-item">
                            <div class="gallery-img" style="background-image: url('assets/images/presentacion3.jpg');"></div>
                        </div>
                        <div class="gallery-item">
                            <div class="gallery-img" style="background-image: url('assets/images/presentacion4.jpg');"></div>
                        </div>
                        <div class="gallery-item">
                            <div class="gallery-img" style="background-image: url('assets/images/presentacion5.jpg');"></div>
                        </div>
                    </div>
                    <button class="gallery-nav prev" id="presPrev">‹</button>
                    <button class="gallery-nav next" id="presNext">›</button>
                </div>
            </div>
        </section>

        <!-- Viajes/Tours Carousel -->
        <section id="viajes" class="gallery-section section-alt">
            <div class="container">
                <div class="gallery-header">
                    <h2 class="gallery-title">Viajes y Tours</h2>
                    <p class="gallery-subtitle">Presentaciones internacionales</p>
                </div>
                
                <div class="gallery-carousel" id="viajesCarousel">
                    <div class="gallery-track">
                        <div class="gallery-item">
                            <div class="gallery-img" style="background-image: url('assets/images/viaje1.jpg');"></div>
                        </div>
                        <div class="gallery-item">
                            <div class="gallery-img" style="background-image: url('assets/images/viaje2.jpg');"></div>
                        </div>
                        <div class="gallery-item">
                            <div class="gallery-img" style="background-image: url('assets/images/viaje3.jpg');"></div>
                        </div>
                        <div class="gallery-item">
                            <div class="gallery-img" style="background-image: url('assets/images/viaje4.jpg');"></div>
                        </div>
                        <div class="gallery-item">
                            <div class="gallery-img" style="background-image: url('assets/images/viaje5.jpg');"></div>
                        </div>
                    </div>
                    <button class="gallery-nav prev" id="viajesPrev">‹</button>
                    <button class="gallery-nav next" id="viajesNext">›</button>
                </div>
            </div>
        </section>

        <!-- Historia Carousel -->
        <section id="historia" class="gallery-section">
            <div class="container">
                <div class="gallery-header">
                    <h2 class="gallery-title">Historia</h2>
                    <p class="gallery-subtitle">Trayectoria y momentos memorables</p>
                </div>
                
                <div class="gallery-carousel" id="historiaCarousel">
                    <div class="gallery-track">
                        <div class="gallery-item">
                            <div class="gallery-img" style="background-image: url('assets/images/historia1.jpg');"></div>
                        </div>
                        <div class="gallery-item">
                            <div class="gallery-img" style="background-image: url('assets/images/historia2.jpg');"></div>
                        </div>
                        <div class="gallery-item">
                            <div class="gallery-img" style="background-image: url('assets/images/historia3.jpg');"></div>
                        </div>
                        <div class="gallery-item">
                            <div class="gallery-img" style="background-image: url('assets/images/historia4.jpg');"></div>
                        </div>
                        <div class="gallery-item">
                            <div class="gallery-img" style="background-image: url('assets/images/historia5.jpg');"></div>
                        </div>
                    </div>
                    <button class="gallery-nav prev" id="historiaPrev">‹</button>
                    <button class="gallery-nav next" id="historiaNext">›</button>
                </div>

                <!-- Texto de Historia -->
                <div class="history-content">
                    <div class="history-text">
                        <p>El Ballet Folclórico de Cochabamba (BFC) nace con la misión de preservar y difundir las danzas tradicionales de nuestra región y de Bolivia entera.</p>
                        <p>Con años de trayectoria, hemos pisado escenarios nacionales e internacionales, llevando en alto el nombre de nuestra cultura.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Misión y Visión -->
        <section id="mision" class="about-section section-alt">
            <div class="container">
                <div class="about-grid">
                    <div class="about-card">
                        <h3>Misión</h3>
                        <p>Rescatar, revalorizar y promover el patrimonio cultural boliviano a través de la danza, formando artistas integrales comprometidos con su identidad.</p>
                    </div>
                    <div class="about-card">
                        <h3>Visión</h3>
                        <p>Ser el referente artístico más importante de la danza folclórica en Bolivia, reconocidos por nuestra calidad técnica y compromiso cultural.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contacto (solo información) -->
        <section id="contacto" class="contact-section">
            <div class="container">
                <div class="contact-content">
                    <h2 class="contact-title">Contacto</h2>
                    <div class="contact-details">
                        <div class="contact-item">
                            <span class="contact-label">Dirección</span>
                            <span class="contact-value">Pasaje F. #123 entre Santa Cruz y Tomas Frias, Cochabamba</span>
                        </div>
                        <div class="contact-item">
                            <span class="contact-label">Teléfono</span>
                            <span class="contact-value">+591 72706455</span>
                        </div>
                        <div class="contact-item">
                            <span class="contact-label">Email</span>
                            <span class="contact-value">contacto@bfc.bo</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-brand">
                <span class="footer-logo">BFC</span>
                <p>Ballet Folclórico de Cochabamba</p>
            </div>
            <div class="footer-links">
                <a href="#inicio">Inicio</a>
                <a href="#presentaciones">Presentaciones</a>
                <a href="#viajes">Viajes</a>
                <a href="#historia">Historia</a>
                <a href="#contacto">Contacto</a>
            </div>
            <div class="footer-copy">
                <p>&copy; <?php echo date('Y'); ?> Ballet Folclórico de Cochabamba. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="js/main.js?v=<?php echo time(); ?>"></script>
    <script>
        // Main Carousel
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.slide');
            const dots = document.querySelectorAll('.carousel-dot');
            let current = 0;
            let timer;

            function show(index) {
                slides.forEach(s => s.classList.remove('active'));
                dots.forEach(d => d.classList.remove('active'));
                slides[index].classList.add('active');
                dots[index].classList.add('active');
                current = index;
            }

            function next() {
                show((current + 1) % slides.length);
            }

            dots.forEach((dot, i) => {
                dot.addEventListener('click', () => {
                    show(i);
                    clearInterval(timer);
                    timer = setInterval(next, 6000);
                });
            });

            timer = setInterval(next, 6000);

            // Gallery Carousels
            function setupGallery(carouselId, prevBtnId, nextBtnId) {
                const carousel = document.getElementById(carouselId);
                const track = carousel.querySelector('.gallery-track');
                const items = carousel.querySelectorAll('.gallery-item');
                const prevBtn = document.getElementById(prevBtnId);
                const nextBtn = document.getElementById(nextBtnId);
                let position = 0;
                const itemWidth = 350;
                const totalWidth = items.length * itemWidth;
                const visibleWidth = carousel.offsetWidth;

                function update() {
                    track.style.transform = `translateX(${position}px)`;
                }

                prevBtn.addEventListener('click', () => {
                    if (position < 0) {
                        position += itemWidth;
                        update();
                    }
                });

                nextBtn.addEventListener('click', () => {
                    if (position > -(totalWidth - visibleWidth)) {
                        position -= itemWidth;
                        update();
                    }
                });

                // Auto advance
                setInterval(() => {
                    if (position > -(totalWidth - visibleWidth)) {
                        position -= itemWidth;
                    } else {
                        position = 0;
                    }
                    update();
                }, 4000);
            }

            setupGallery('presentacionesCarousel', 'presPrev', 'presNext');
            setupGallery('viajesCarousel', 'viajesPrev', 'viajesNext');
            setupGallery('historiaCarousel', 'historiaPrev', 'historiaNext');

            // Header always visible (no scroll effect needed)
            document.getElementById('header').classList.add('always-visible');
        });
    </script>
</body>
</html>
