<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LBJ Services') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/nav-logo.png') }}" />
    @vite(['resources/css/contact.css'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>


    <!-- Navigation -->
    <nav class="navbar">
        <div class="container nav-wrapper">
            <img src="/images/buwiselogo.png" alt="BuWise Logo" class="logo">

            <!-- Hamburger Button (mobile only) -->
            <button class="hamburger" id="hamburger">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>

            <!-- Desktop Menu -->
            <ul class="nav-menu">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('about') }}">About Us</a></li>
                <li><a href="{{ route('services') }}">Features</a></li>
                <li><a href="/privacy">LBJ Services</a></li>
                <li><a href="/contact" style="color: #279CE9;">Contact Us</a></li>
            </ul>
            <a href="/login" class="btn-signin desktop-signin">Sign In</a>

        </div>
    </nav>

    <!-- Side Navigation (for mobile) -->
    <div class="side-nav" id="side-nav">
        <div class="side-nav-header">
            <img src="/images/buwiselogo.png" alt="BuWise Logo">
            <button class="close-btn" id="close-btn">&times;</button>
        </div>
        <ul class="side-nav-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('about') }}">About Us</a></li>
            <li><a href="{{ route('services') }}">Features</a></li>
            <li><a href="/privacy">LBJ Services</a></li>
            <li><a href="/contact" style="color: #279CE9;">Contact Us</a></li>
        </ul>
        <a href="/login" class="btn-signin mobile-signin">Sign In</a>

    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Hero Section -->
    <section class="hero"
        style="background: url('/images/aboutbg.png') center center / cover no-repeat; height: 60vh; position: relative;">
        <div class="container hero-grid" style="position: relative; z-index: 1;">
            <div class="hero-text">
                <h1>Contact Us </h1>

                <div class="hero-buttons">
                    <a href="{{ route('home') }}" class="btn btn-primary">Home</a>
                    <a href="{{ route('about') }}" class="btn btn-outline">Learn More</a>

                </div>
            </div>
        </div>
    </section>

    <section class="contact-section">
        <div class="container contact-grid">
            <!-- Left Contact Info -->
            <div class="contact-info">
                <h2>Get in touch and be our new <span style="color: #279CE9;">Clientele</span></h2>
                <p>Want to know more about BuWise? Enter your details in the message box or contact us through the
                    details below to learn more about our product and how it can transform your everyday bookkeeping
                    operations.</p>

                <div class="info-item">
                    <i class="mdi mdi-phone"></i>
                    <div>
                        <strong>Phone</strong>
                        <p>+63 920 637 810</p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="mdi mdi-email"></i>
                    <div>
                        <strong>Email</strong>
                        <p>buwise@gmail.com</p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="mdi mdi-map-marker"></i>
                    <div>
                        <strong>Address</strong>
                        <p>1072 Blumennrit St. Sampaloc Manila</p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="mdi mdi-facebook"></i>
                    <div>
                        <strong>Facebook</strong>
                        <p>facebook.com/buwise</p>
                    </div>
                </div>
            </div>

            <!-- Right Contact Form -->
            <div class="contact-form">
                <form action="/contact" method="POST">
                    @csrf
                    <label for="name">Full Name</label>
                    <input name="full_name" type="text" id="name" placeholder="Enter Full Name" required>

                    <label for="email">Email</label>
                    <div class="input-icon">
                        <i class="mdi mdi-email-outline"></i>
                        <input name="email" type="email" id="email" placeholder="name@domain.com" required>
                    </div>

                    <label for="message">Message</label>
                    <textarea id="message" name="message" placeholder="Message" required></textarea>

                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>
    </section>



    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-logo">
                <img src="/images/whitebuwise.png" alt="BuWise Logo" class="footer-img">
            </div>
            <ul class="footer-links">
                <li><a href="{{ route('home') }}">HOME</a></li>
                <li><a href="{{ route('about') }}">ABOUT US</a></li>
                <li><a href="{{ route('services') }}">FEATURES</a></li>
                <li><a href="/privacy">LBJ Services</a></li>
                <li><a href="/contact" style="color: #279CE9;">CONTACT US</a></li>
                <li><a href="{{ route('register') }}">SIGN UP</a></li>
                <li><a href="{{ route('login') }}">LOGIN</a></li>
            </ul>
            <div class="footer-socials">
                <a href="#" aria-label="Facebook"><i class="mdi mdi-facebook"></i></a>
                <a href="#" aria-label="Twitter"><i class="mdi mdi-twitter"></i></a>
                <a href="#" aria-label="Instagram"><i class="mdi mdi-instagram"></i></a>
                <a href="#" aria-label="GitHub"><i class="mdi mdi-github"></i></a>
            </div>
        </div>
    </footer>

</body>

</html>

<!-- Scripts -->
<script>
    const hamburger = document.getElementById('hamburger');
    const sideNav = document.getElementById('side-nav');
    const overlay = document.getElementById('overlay');
    const closeBtn = document.getElementById('close-btn');

    hamburger.addEventListener('click', () => {
        sideNav.classList.add('active');
        overlay.classList.add('active');
    });

    closeBtn.addEventListener('click', () => {
        sideNav.classList.remove('active');
        overlay.classList.remove('active');
    });

    overlay.addEventListener('click', () => {
        sideNav.classList.remove('active');
        overlay.classList.remove('active');
    });
</script>

<script>
    let lastScrollTop = 0;
    const topBar = document.querySelector('.top-bar');

    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > lastScrollTop) {
            topBar.classList.add('show');
        } else {
            topBar.classList.remove('show');
        }

        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    });
</script>

<script>
    let lastScrollTop = 0;
    const navbar = document.querySelector('.navbar');

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset || document.documentElement.scrollTop;

        if (currentScroll > lastScrollTop) {
            navbar.classList.add('navbar-hide');
        } else {
            navbar.classList.remove('navbar-hide');
        }

        lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
    });
</script>
