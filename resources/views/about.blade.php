<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LBJ Services') }}</title>

    @vite(['resources/css/about.css'])
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
            <h1>About <span class="blue">BuWise</span> </h1>
           
            <div class="hero-buttons">
                <a href="{{ route('services') }}" class="btn btn-primary">Our Services</a>
                <a href="/contact" class="btn btn-outline">Get in Touch</a>
            </div>
        </div>
    </div>
</section>



<!-- Clients Submit Invoices -->
<section class="clients-submit">
    <div class="container dual-section">
        <div class="circle-wrap phone-wrap">
            <div class="circle-bg phone-circle"></div>
            <img src="/images/rpa 1.png" class="device phone" alt="Phone UI" />
        </div>
        <div class="section-text">
            <h2>What is <span class="highlight">Robotic Process Automation </span> ?</h2>
            <p>Robotic Process Automation (RPA) is an innovative technology that automates manual, recurring processes. Unlike a physical robot in industry, RPA does not use machines, but so-called software bots. They do not perform physical work but control digital processes by imitating work steps on a user interface. Like human employees, RPA bots log into various applications and systems and can then perform rule-based routine operations.
</p> <p>At <span class="highlight"> BuWise </span> we go beyond traditional methods, integrating RPA to fundamentally streamline and optimize your financial processes. This isn't just about speed; it's about ensuring unparalleled accuracy, boosting overall efficiency, and guaranteeing robust compliance with every transaction. By strategically leveraging automation, we significantly reduce manual tasks, virtually eliminating human error from repetitive workflows.</p>
        </div>
    </div>
</section>

<!-- Client Reports -->
<section class="client-reports">
    <div class="container dual-section reverse">
        <div class="circle-wrap monitor-wrap">
            <div class="circle-bg monitor-circle"></div>
            <img src="/images/monitor-ui.png" class="device monitor" alt="Monitor UI" />
        </div>
        <div class="section-text">
            <h2>How does <span class="highlight">BuWise</span>  improve Bookkeeping?<span class="highlight">data</span></h2>
            <p>BuWise is able to improve your Bookkeeping process by doing the following: First, we automate your invoice management. Simply upload an invoice, and our software bots will instantly scan its details and create a complete journal entry, including the correct account names and groups. Next, we meticulously check and verify these entries to ensure unparalleled accuracy before they're posted. After being processed as a Journal Entry, Ledger Entry, it will now generate a Report. And you didn’t even have to lift a finger! 

</p> <p> Our bookkeeping management system is completely free to try! Sign up as an Accountant now to manage your firm and optimize your bookkeeping process now. </p>
              <a href="/register" class="cta-button">Sign up as an Accountant <span class="arrow">→</span>
            
        </a>

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
