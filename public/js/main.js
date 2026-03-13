document.addEventListener('DOMContentLoaded', function () {
    // --- Hero Carousel ---
    const slides = document.querySelectorAll('.carousel-slide');
    const nextBtn = document.querySelector('.next');
    const prevBtn = document.querySelector('.prev');
    let currentSlide = 0;
    const slideInterval = 6000; // Slightly longer for "expert" feel

    function showSlide(n) {
        if (!slides || slides.length === 0) return;
        slides[currentSlide].classList.remove('active');
        currentSlide = (n + slides.length) % slides.length;
        slides[currentSlide].classList.add('active');
    }

    function nextSlide() { if (slides.length > 0) showSlide(currentSlide + 1); }
    function prevSlide() { if (slides.length > 0) showSlide(currentSlide - 1); }

    if (nextBtn && prevBtn && slides.length > 0) {
        nextBtn.addEventListener('click', () => {
            nextSlide();
            resetInterval();
        });
        prevBtn.addEventListener('click', () => {
            prevSlide();
            resetInterval();
        });
    }

    let itv;
    if (slides.length > 0) {
        itv = setInterval(nextSlide, slideInterval);
    }

    function resetInterval() {
        if (itv) {
            clearInterval(itv);
            itv = setInterval(nextSlide, slideInterval);
        }
    }

    // --- Hamburger Menu ---
    const navToggle = document.getElementById('navToggle');
    const mainNav = document.getElementById('mainNav');

    if (navToggle && mainNav) {
        navToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            const isActive = mainNav.classList.toggle('active');
            navToggle.classList.toggle('active');
            
            if (isActive) {
                document.body.style.overflow = 'hidden';
                document.body.style.height = '100vh';
            } else {
                document.body.style.overflow = '';
                document.body.style.height = '';
            }
        });

        // Close menu when clicking links
        const navLinks = mainNav.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                navToggle.classList.remove('active');
                mainNav.classList.remove('active');
                document.body.style.overflow = '';
                document.body.style.height = '';
            });
        });

        // Close on background click
        document.addEventListener('click', (e) => {
            if (mainNav.classList.contains('active') && !mainNav.contains(e.target) && !navToggle.contains(e.target)) {
                navToggle.classList.remove('active');
                mainNav.classList.remove('active');
                document.body.style.overflow = '';
                document.body.style.height = '';
            }
        });
    }

    // --- Header Scroll Effect ---
    const header = document.querySelector('.header');
    function checkScroll() {
        if (header) {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }
    }
    window.addEventListener('scroll', checkScroll);
    checkScroll(); // Initial check
});
