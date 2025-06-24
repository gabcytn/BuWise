<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LBJ Services') }}</title>

    @vite(['resources/css/services.css'])
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
            <li><a href="{{ route('services') }}">Services</a></li>
            <li><a href="/privacy">Privacy Policy</a></li>
            <li><a href="/contact">Contact Us</a></li>
        </ul>
    <a href="/register" class="btn-signin desktop-signin">Sign In</a>

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
        <li><a href="{{ route('services') }}">Services</a></li>
        <li><a href="/privacy">Privacy Policy</a></li>
        <li><a href="/contact">Contact Us</a></li>
    </ul>
        <a href="/register" class="btn-signin mobile-signin">Sign In</a>

</div>

<!-- Overlay -->
<div class="overlay" id="overlay"></div>

<!-- Hero Section -->
<section class="hero" style="background: url('/images/aboutbg.png') center center / cover no-repeat; height: 60vh; position: relative;">
    <div class="container hero-grid" style="position: relative; z-index: 1;">
        <div class="hero-text">
            <h1>Our <span class="blue">Services</span> </h1>
           
            <div class="hero-buttons">
                <a href="{{ route('about') }}" class="btn btn-primary">Learn More</a>
                <a href="/contact" class="btn btn-outline">Get in Touch</a>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="services">
    <div class="container">
        <h2 class="section-title">Our Services</h2>
        <p class="section-subtitle">Our innovative approach quickly accelerates your accounting workflows.</p>
        
        <div class="services-grid">
            <div class="service-box">
                <div class="service-icon">
                    <i class="mdi mdi-file-document-multiple"></i>
                </div>
                <h3>Automated Invoice Management</h3>
                <p>Lorem ipsum is simply dummy text of the printing and typesetting industry...</p>
            </div>
            <div class="service-box">
                <div class="service-icon">
                    <i class="mdi mdi-database-import"></i>
                </div>
                <h3>Seamless Data Migration</h3>
                <p>Lorem ipsum is simply dummy text of the printing and typesetting industry...</p>
            </div>
            <div class="service-box">
                <div class="service-icon">
                    <i class="mdi mdi-robot"></i>
                </div>
                <h3>RPA Integration</h3>
                <p>Lorem ipsum is simply dummy text of the printing and typesetting industry...</p>
            </div>
       </div>
       
<div class="services-grid">
  <div class="service-box">
    <div class="service-icon">
      <i class="mdi mdi-file-chart-outline"></i> <!-- 📄 Automated Journal Entries -->
    </div>
    <h3>Automated Journal Entries</h3>
    <p>Lorem ipsum is simply dummy text of the printing and typesetting industry...</p>
  </div>
  
  <div class="service-box">
    <div class="service-icon">
      <i class="mdi mdi-account-group-outline"></i> <!-- 👥 Efficient Staff Management -->
    </div>
    <h3>Efficient Staff Management</h3>
    <p>Lorem ipsum is simply dummy text of the printing and typesetting industry...</p>
  </div>
  
  <div class="service-box">
    <div class="service-icon">
      <i class="mdi mdi-file-chart"></i> <!-- 📊 Quick Report Generation -->
    </div>
    <h3>Quick Report Generation</h3>
    <p>Lorem ipsum is simply dummy text of the printing and typesetting industry...</p>
  </div>
</div>

<div class="services-grid">
  <div class="service-box">
    <div class="service-icon">
      <i class="mdi mdi-account-tie-outline"></i> <!-- 👨‍💼 Streamlined Client Management -->
    </div>
    <h3>Streamlined Client Management</h3>
    <p>Lorem ipsum is simply dummy text of the printing and typesetting industry...</p>
  </div>
  
  <div class="service-box">
    <div class="service-icon">
      <i class="mdi mdi-cellphone-cog"></i> <!-- 📱 BuWise Mobile -->
    </div>
    <h3>BuWise Mobile</h3>
    <p>Lorem ipsum is simply dummy text of the printing and typesetting industry...</p>
  </div>
  
  <div class="service-box">
    <div class="service-icon">
      <i class="mdi mdi-clipboard-check-outline"></i> <!-- ✅ Improved Task Management -->
    </div>
    <h3>Improved Task Management</h3>
    <p>Lorem ipsum is simply dummy text of the printing and typesetting industry...</p>
  </div>
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
            <li><a href="{{ route('services') }}">SERVICES</a></li>
            <li><a href="/privacy">Privacy Policy</a></li>
            <li><a href="/contact">Contact Us</a></li>
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

    window.addEventListener('scroll', function () {
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
