<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LBJ Services') }}</title>

    @vite(['resources/css/welcome.css'])
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
<li><a href="{{ route('home') }}" style="color: #279CE9;">Home</a></li>
            <li><a href="{{ route('about') }}">About Us</a></li>
            <li><a href="{{ route('services') }}">Features</a></li>
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
<li><a href="{{ route('home') }}" style="color: #279CE9;">Home</a></li>
        <li><a href="{{ route('about') }}">About Us</a></li>
        <li><a href="{{ route('services') }}">Features</a></li>
        <li><a href="/privacy">LBJ Services</a></li>
        <li><a href="/contact">Contact Us</a></li>
    </ul>
        <a href="/login" class="btn-signin mobile-signin">Sign In</a>

</div>

<!-- Overlay -->
<div class="overlay" id="overlay"></div>

<!-- Hero Section -->
<section class="hero-carousel">
  <!-- Slide 1 -->
  <div class="carousel-slide active" style="background-image: url('/images/1.png');">
    <div class="carousel-content">
      <h1>Having Problems with your <br>Bookkeeping?</h1>
      <p>We provide quality bookkeeping and accounting services for all through BuWise. Our services utilize automation technology such as Robotic Process Automation (RPA) to streamline our operations.</p>
      <div class="hero-buttons">
        <a href="/privacy" class="btn btn-primary">LBJ Services</a>
        <a href="/contact" class="btn btn-outline">Get in Touch →</a>
      </div>
    </div>
  </div>

  <!-- Slide 2 -->
  <div class="carousel-slide" style="background-image: url('/images/2.png');">
    <div class="carousel-content">
      <h1>Don’t know how to do your <br>Bookkeeping Reports?</h1>
      <p>LBJ Services delivers insightful reports that make it easier to understand your company’s performance and take smarter next steps. These reports are also crucial for your auditing through the Bureau of Internal Revenue.</p>
      <div class="hero-buttons">
        <a href="/privacy" class="btn btn-primary">LBJ Services</a>
        <a href="/contact" class="btn btn-outline">Get in Touch →</a>
      </div>
    </div>
  </div>

  <!-- Slide 3 -->
  <div class="carousel-slide" style="background-image: url('/images/3.png');">
    <div class="carousel-content">
      <h1>Having Trouble with your <br>Invoicing and Audit?</h1>
      <p>LBJ Services simplifies invoice submission through a user-friendly mobile app and streamlines the generation of audit reports. This helps you meet deadlines more efficiently and ensures better compliance with BIR regulations.</p>
      <div class="hero-buttons">
        <a href="/privacy" class="btn btn-primary">LBJ Services</a>
        <a href="/contact" class="btn btn-outline">Get in Touch →</a>
      </div>
    </div>
  </div>

  <!-- Slide 4 -->
  <div class="carousel-slide" style="background-image: url('/images/4.png');">
    <div class="carousel-content">
      <h1>Don’t know when your <br>BIR Forms are due?</h1>
      <p>We value transparency and proactive communication. That’s why we keep you informed of your monthly, quarterly, and annual BIR reporting schedules—so you’re always prepared, never miss a deadline, and can focus more on running your business .</p>
      <div class="hero-buttons">
        <a href="/privacy" class="btn btn-primary">LBJ Services</a>
        <a href="/contact" class="btn btn-outline">Get in Touch →</a>
      </div>
    </div>
  </div>

  <button class="carousel-prev">&#10094;</button>
  <button class="carousel-next">&#10095;</button>
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

        <a href="{{ route('services') }}" class="btn-secondary">
            See All Services <i class="mdi mdi-arrow-right"></i>
        </a>
    </div>
</section>

<!-- Clients Submit Invoices -->
<section class="clients-submit">
    <div class="container dual-section">
        <div class="circle-wrap phone-wrap">
            <div class="circle-bg phone-circle"></div>
            <img src="/images/mobile-ui.png" class="device phone" alt="Phone UI" />
        </div>
        <div class="section-text">
            <h2>Let your clients submit <span class="highlight">invoices</span> online</h2>
            <p>Buwise Mobile empowers your clients with a convenient portal to their financial data, allowing them to effortlessly upload invoices, view personalized schedules, and generate essential reports. This self-service functionality not only improves transparency and client satisfaction but also reduces administrative burden on your firm. Specifically, within the mobile app, clients can easily capture photos of invoices or upload existing files, track the real-time status of their submissions, and access their financial reports on demand, fostering seamless collaboration and faster turnaround times.</p>
        </div>
    </div>
</section>

<!-- Client Reports -->
<section class="client-reports">
  <div class="container dual-section reverse">
    <div class="monitor-wrap">
      <img src="/images/2mobile.png" class="device monitor" alt="Monitor UI" />
    </div>
        <div class="section-text">
            <h2>Generate realtime <span class="highlight">reports</span> in your mobile device <span class="highlight">data</span></h2>
            <p>Our mobile app enables clients to generate real-time bookkeeping reports—such as Income Statements, Balance Sheets, and other key financial documents—directly from our centralized system. By providing instant access to accurate and up-to-date financial data, the app empowers business owners and managers to monitor performance trends, assess profitability, and ensure financial stability at any time. This level of insight supports better decision-making, improves operational efficiency, and helps businesses stay compliant with reporting standards and regulatory requirements.</p>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="cta-container">
        <p class="cta-heading">GET STARTED</p>
        <h2 class="cta-title">Be an Accountant</h2>
        <p class="cta-description">
            Create your account and instantly step into your role as an accountant. Our platform empowers you to form your organization and manage your entire accounting firm with unparalleled ease. Beyond effortlessly adding clients and setting up staff accounts, you'll conquer all your key bookkeeping tasks in one centralized, intuitive platform. What's more, our system automates repetitive tasks like data entry and reconciliation, freeing up valuable time. We also provide seamless data migration for your Excel files, ensuring a smooth transition. And with robust security measures built-in, your sensitive financial data is always protected. </p>
              <a href="/register" class="cta-button">Sign up as an Accountant <span class="arrow">→</span>
        </a>
    </div>
</section>


<!-- Footer -->
<footer class="footer">
    <div class="footer-container">
        <div class="footer-logo">
            <img src="/images/whitebuwise.png" alt="BuWise Logo" class="footer-img">
        </div>
        <ul class="footer-links">
<li><a href="{{ route('home') }}" style="color: #279CE9;">HOME</a></li>
            <li><a href="{{ route('about') }}">ABOUT US</a></li>
            <li><a href="{{ route('services') }}">SERVICES</a></li>
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

