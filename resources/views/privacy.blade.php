<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LBJ Services') }}</title>


    @vite(['resources/css/privacy.css'])
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
<li><a href="/privacy" style="color: #279CE9;">LBJ Services</a></li>
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
        <li><a href="{{ route('services') }}">Features</a></li>
<li><a href="/privacy" style="color: #279CE9;">LBJ Services</a></li>
            <li><a href="/contact">Contact Us</a></li>
    </ul>
        <a href="/login" class="btn-signin mobile-signin">Sign In</a>


</div>


<!-- Overlay -->
<div class="overlay" id="overlay"></div>


<!-- Hero Section -->
<section class="hero lbj-hero"
 style="
  background: url('/images/LBJhero.png') center 70% no-repeat;
  background-size: 600px;
  height: 60vh;
  position: relative;
"> 
</section>

<!-- About us Section -->

<section class="about-section">
  <div class="about-content">
    <h5>ABOUT US</h5>
    <h2>Accounting by the Book,<br>Perform with Trust<span>.</span></h2>
    <p>
      Since our establishment in the early 2000s, LBJ Services has been a trusted provider of reliable external accounting and bookkeeping solutions for businesses of all sizes. With a strong commitment to accuracy, transparency, and client support, we help companies efficiently manage their finances, stay fully compliant with BIR regulations, and make well-informed financial decisions. Our goal has always been to simplify financial management so our clients can focus on growing their business with confidence.
    </p>
    <div class="about-buttons">
      <a href="/contact" class="btn btn-primary">Contact us and be a client today!</a>
      <a href="/about" class="btn btn-outline">Learn More →</a>
    </div>
  </div>
  <div class="about-image-wrapper">
    <img src="/images/aboutimg.png" alt="About Image" class="about-img">
    <div class="about-buttons">
    </div>
  </div>
</section>



<!-- Services Section -->
<section class="services">
    <div class="container">
        <h2 class="section-title">Our Goals</h2> 
<p class="section-subtitle">Exceptional Service. Nationwide.</p>
<p class="section-description">LBJ Services delivers high-quality bookkeeping and accounting services all throughout the country. </p>

<div class="services-grid">
    <div class="service-box">
        <div class="service-icon">
            <i class="mdi mdi-eye-check-outline"></i> <!-- Vision -->
        </div>
        <h3>Our Vision</h3>
        <p>To provide accurate, efficient, and technology-driven accounting and bookkeeping solutions that empower businesses to meet compliance requirements, make sound financial decisions, and achieve sustainable growth.</p>
    </div>
    <div class="service-box">
        <div class="service-icon">
            <i class="mdi mdi-target-account"></i> <!-- Mission -->
        </div>
        <h3>Our Mission</h3>
        <p>To be the leading partner for outsourced accounting and financial services in the Philippines, recognized for our reliability, innovation, and commitment to helping clients thrive in a complex regulatory environment.</p>
    </div>
    <div class="service-box">
        <div class="service-icon">
            <i class="mdi mdi-handshake-outline"></i> <!-- Culture -->
        </div>
        <h3>Our Culture</h3>
        <p>At LBJ Services, we foster a culture of integrity, transparency, and continuous improvement. We believe in building strong client relationships through proactive support, personalized service, and technology.</p>
    </div>
</div>

<a href="{{ route('services') }}" class="btn-secondary">
    See All Services <i class="mdi mdi-arrow-right"></i>
</a>

    </div>
</section>

<!-- Team Section -->
<section class="team-section">
    <div class="team-header">
        <p class="team-subtitle">MEET OUR TEAM</p>
        <h2 class="team-title">Awesome people behind us.</h2>
    </div>
    <div class="team-grid">
        <div class="team-card">
            <div class="team-image">
                <img src="/images/profile1.png" alt="Lallaine Jayag">
            </div>
            <h3 class="team-name">Lallaine Jayag</h3>
            <p class="team-role">External Accountant</p>
            <div class="team-icons">
                <a href="#"><i class="mdi mdi-facebook"></i></a>
                <a href="#"><i class="mdi mdi-email-outline"></i></a>
                <a href="#"><i class="mdi mdi-phone-outline"></i></a>
            </div>
        </div>
        <div class="team-card">
            <div class="team-image">
                <img src="/images/profile2.png" alt="Liezel Bernadino">
            </div>
            <h3 class="team-name">Liezel Bernadino</h3>
            <p class="team-role">Liaison Officer</p>
            <div class="team-icons">
                <a href="#"><i class="mdi mdi-facebook"></i></a>
                <a href="#"><i class="mdi mdi-email-outline"></i></a>
                <a href="#"><i class="mdi mdi-phone-outline"></i></a>
            </div>
        </div>
        <div class="team-card">
            <div class="team-image">
                <img src="/images/profile3.png" alt="Camille Ann Gatre">
            </div>
            <h3 class="team-name">Camille Ann Gatre</h3>
            <p class="team-role">Accounting Clerk</p>
            <div class="team-icons">
                <a href="#"><i class="mdi mdi-facebook"></i></a>
                <a href="#"><i class="mdi mdi-email-outline"></i></a>
                <a href="#"><i class="mdi mdi-phone-outline"></i></a>
            </div>
        </div>
    </div>
</section>
<<!-- Services Section --> 
<section class="services">
    <div class="container">
        <h2 class="section-title">Our Services</h2>
        <p class="section-subtitle">List of what we can offer to you</p>
        
        <div class="services-grid">
            <div class="service-box">
                <div class="service-icon">
                    <i class="mdi mdi-book-open-page-variant"></i>
                </div>
                <h3>Accounting & Bookkeeping</h3>
                <p>We handle end-to-end bookkeeping, including real-time financial reporting, general ledger maintenance, bank reconciliation, and the preparation of income statements, balance sheets, and cash flow reports.</p>
            </div>
            <div class="service-box">
                <div class="service-icon">
                    <i class="mdi mdi-file-chart-outline"></i>
                </div>
                <h3>Tax Compliance & Reporting</h3>
                <p>We ensure full BIR compliance by managing registrations, preparing and filing tax returns (VAT, income, withholding), and providing deadline reminders and strategic tax planning.</p>
            </div>
            <div class="service-box">
                <div class="service-icon">
                    <i class="mdi mdi-cash-multiple"></i>
                </div>
                <h3>Payroll & Compensation Management</h3>
                <p>We process accurate payroll, generate payslips, manage government contributions (SSS, PhilHealth, Pag-IBIG), and handle year-end tax compliance like BIR Form 2316.</p>
            </div>
        </div>
        <div class="services-grid">
            <div class="service-box">
                <div class="service-icon">
                    <i class="mdi mdi-shield-search"></i>
                </div>
                <h3>Audit Assistance</h3>
                <p>We prepare audit-ready reports, support BIR and external audit processes, and review your financial records to ensure clean, compliant books.</p>
            </div>
            <div class="service-box">
                <div class="service-icon">
                    <i class="mdi mdi-cellphone-cog"></i>
                </div>
                <h3>Digital & Mobile Services</h3>
                <p>Our mobile app allows easy access to reports, invoice submissions, document uploads, and automated compliance notifications—all in real time.</p>
            </div>
            <div class="service-box">
                <div class="service-icon">
                    <i class="mdi mdi-briefcase-check-outline"></i>
                </div>
                <h3>Business Advisory</h3>
                <p>We offer expert guidance in financial analysis, budgeting, forecasting, business registration or closure, and internal control improvements.</p>
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
            <li><a href="/privacy"style="color: #279CE9;">LBJ SERVICES</a></li>
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
<script>
  let slides = document.querySelectorAll('.carousel-slide');
  let currentIndex = 0;
  let interval = 3000; // 5 seconds

  const showSlide = (index) => {
    slides.forEach((slide, i) => {
      slide.classList.toggle('active', i === index);
    });
  };

  const nextSlide = () => {
    currentIndex = (currentIndex + 1) % slides.length;
    showSlide(currentIndex);
  };

  document.querySelector('.carousel-next').addEventListener('click', () => {
    nextSlide();
    resetAutoSlide();
  });

  document.querySelector('.carousel-prev').addEventListener('click', () => {
    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
    showSlide(currentIndex);
    resetAutoSlide();
  });

  let autoSlide = setInterval(nextSlide, interval);

  const resetAutoSlide = () => {
    clearInterval(autoSlide);
    autoSlide = setInterval(nextSlide, interval);
  };
</script>

