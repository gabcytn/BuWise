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
<li><a href="{{ route('services') }}" style="color: #279CE9;">Features</a></li>
            <li><a href="/privacy">LBJ Services</a></li>
            <li><a href="/contact">Contact Us</a></li>
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
<li><a href="{{ route('services') }}" style="color: #279CE9;">Features</a></li>
        <li><a href="/privacy">LBJ Services</a></li>
        <li><a href="/contact">Contact Us</a></li>
    </ul>
        <a href="/login" class="btn-signin mobile-signin">Sign In</a>

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
                <p>Enables users to upload and view invoice details in real time. This automation ensures 100% accuracy when mapping invoices to accounts and entering their details, significantly simplifying the process.</p>
            </div>
            <div class="service-box">
                <div class="service-icon">
                    <i class="mdi mdi-database-import"></i>
                </div>
                <h3>Seamless Data Migration</h3>
                <p>Lets you upload journal entries from Excel to the system using RPA, significantly reducing manual effort and potential errors. This automation ensures your financial records are updated quickly and accurately.</p>
            </div>
            <div class="service-box">
                <div class="service-icon">
                    <i class="mdi mdi-robot"></i>
                </div>
                <h3>RPA Integration</h3>
                <p>Enhances your firm's operational efficiency by automating repetitive, rule-based tasks. Reduces manual errors, processing times, and frees up your team to focus on higher-value and strategic activities.</p>
            </div>
       </div>
       
<div class="services-grid">
  <div class="service-box">
    <div class="service-icon">
      <i class="mdi mdi-file-chart-outline"></i> 
    </div>
    <h3>Automated Journal Entries</h3>
    <p>Leverages RPA to upload journal entries directly from Excel into the system. This process eliminates manual data entry, ensuring accuracy, consistency, and significantly reducing the time for bookkeeping.</p>
  </div>
  
  <div class="service-box">
    <div class="service-icon">
      <i class="mdi mdi-account-group-outline"></i> 
    </div>
    <h3>Efficient Staff Management</h3>
    <p>Efficiently manage your team by adding staff and assigning tasks, while precisely limiting their capabilities to only essential bookkeeping functions. This ensures streamlined operations and enhanced security.</p>
  </div>
  
  <div class="service-box">
    <div class="service-icon">
      <i class="mdi mdi-file-chart"></i>
    </div>
    <h3>Quick Report Generation</h3>
    <p>Generates comprehensive reports from financial data, providing  instant insights into your client’s performance. This includes essential statements like Income Statements (P&L), Balance Sheets, and Cash Flow Statements.</p>
  </div>
</div>

<div class="services-grid">
  <div class="service-box">
    <div class="service-icon">
      <i class="mdi mdi-account-tie-outline"></i> 
    </div>
    <h3>Streamlined Client Management</h3>
    <p>Allows accountants to manage their clients by adding new accounts, editing account details, and centralizing all client information in one secure location  for stronger client relationships.</p>
  </div>
  
  <div class="service-box">
    <div class="service-icon">
      <i class="mdi mdi-cellphone-cog"></i> 
    </div>
    <h3>BuWise Mobile</h3>
    <p>Empowers your clients with a convenient portal to their financial data, allowing them to effortlessly upload invoices, view personalized schedules, and generate essential reports. </p>
  </div>
  
  <div class="service-box">
    <div class="service-icon">
      <i class="mdi mdi-clipboard-check-outline"></i> 
    </div>
    <h3>Improved Task Management</h3>
    <p>Allows users to manage and track their tasks by providing a centralized platform where they can create, assign, prioritize, and monitor the progress of various activities. </p>
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
<li><a href="{{ route('services') }}" style="color: #279CE9;">FEATURES</a></li>
            <li><a href="/privacy">LBJ SERVICES</a></li>
            <li><a href="/contact">CONTACT US</a></li>
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
