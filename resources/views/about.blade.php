<!DOCTYPE html>

<html lang="en">
<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@vite(['resources/css/about.css'])
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'LBJ Services') }}</title>
  <link rel="stylesheet" href="{{ asset('css/about.css') }}">
</head>
<body>
  <!-- Top Bar -->
  <header>
    <div class="top-bar">
      📧 lbjservices@yahoo.com | 📞 +63 209 637 810
    </div>

<!-- Navigation -->
<nav>
  <div class="logo">
    <img src="{{ asset('images/logo.png') }}" alt="LBJ Services Logo" height="40">
  </div>

  <div class="burger" id="burger">
    &#9776;
  </div>

  <ul class="nav-links" id="navLinks">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="{{ route('about') }}">About us</a></li>
    <li><a href="{{ route('services') }}">Services</a></li>

    @auth
  <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
@else
  <li class="nav-auth-buttons">
    <a href="/register" class="register-btn">Register</a>
    <a href="/login" class="login-btn">Log In</a>
  </li>
@endauth
  </ul>
</nav>
  </header>

<!-- Hero -->
<section class="hero" style="background: url('{{ asset('images/hero33.jpg') }}') center/cover no-repeat;">
  <div class="overlay">
    <p>COMPANY PROFILE</p>
    <h1>
      <span class="highlight"><strong>ABOUT US</strong></span> 
    </h1>
    <p>
      At LBJ Bookkeeping and Accounting Services, we are committed to providing accurate, efficient, and transparent financial management solutions to businesses across the Philippines.
    </p>
    <div class="hero-buttons">
      <a href="#about" class="btn">Learn More</a>
    </div>
  </div>
</section>

  <section class="logo">
    <img src="{{ asset('images/logo2.png') }}" alt="LBJ Services Logo" height="40">
  </section>

  <!-- About Us -->
  <section class="who-we-are" id="about">
    <h2>WHO WE ARE</h2>
    <p>LBJ Bookkeeping and Accounting Services, led by Lallaine Bernardino Jayag, provides external bookkeeping and accounting services to over 60 clients across various industries, ensuring accuracy, efficiency, and financial peace of mind.</p>
    <div class="features">
      <div class="feature blue">
        <h3><img src="{{ asset('images/bulb.png') }}" alt="Icon" class="icon-above">Reliable and Efficient</h3>
        <p>Our expert team delivers streamlined bookkeeping and accounting solutions, ensuring your financial records are accurate, up to date, and organized.</p>
      </div>
      <div class="feature green">
        <h3><img src="{{ asset('images/cost.png') }}" alt="Icon" class="icon-above">Cost-Effective</h3>
        <p>Reduce overhead costs by outsourcing your financial management. We provide top-tier bookkeeping and accounting services at a fraction of the cost.</p>
      </div>
      <div class="feature blue">
        <h3><img src="{{ asset('images/time.png') }}" alt="Icon" class="icon-above">Accuracy & Compliance</h3>
        <p>We ensure all records meet industry standards, tax requirements, and financial reporting guidelines to keep your business audit-ready.</p>
      </div>
      <div class="feature green">
        <h3><img src="{{ asset('images/robot.png') }}" alt="Icon" class="icon-above">Time-Saving</h3>
        <p>Free yourself from time-consuming financial tasks with the power of RPA automation. We leverage technology to handle repetitive bookkeeping functions.</p>
      </div>
    </div>
  </section>

<!-- Statistics Section -->
<section class="hero2" style="background: url('{{ asset('images/hero3.jpg') }}') center/cover no-repeat;">
  <div class="overlay2 stats-overlay">
    <div class="stats-container">
      <div class="stat-box">
        <i class="fas fa-building icon"></i>
        <div class="stat-text">
          <h2>60</h2>
          <p>Companies and clients managed</p>
        </div>
      </div>
      <div class="stat-box">
        <i class="fas fa-file-alt icon"></i>
        <div class="stat-text">
          <h2>190</h2>
          <p>Records currently managing</p>
        </div>
      </div>
      <div class="stat-box">
        <i class="fas fa-users icon"></i>
        <div class="stat-text">
          <h2>3</h2>
          <p>Active Staff</p>
        </div>
      </div>
    </div>
  </div>
</section>



    <section class="team-section">
      <h2>MEET THE TEAM</h2>
      <p class="team-intro">
        The dedicated professionals behind LBJ Bookkeeping and Accounting Services ensure seamless daily operations, 
        providing expert financial management and client support. Our team is committed to accuracy, efficiency, 
        and transparency, leveraging Robotic Process Automation (RPA) to deliver top-tier services.
      </p>
      <div class="team-members">
        <div class="team-card">
          <img src="{{ asset('images/lallaine.png') }}" alt="Lallaine Jayag" class="team-photo">
          <h3>LALLAINE JAYAG</h3>
          <p class="position">Founder/External Accountant</p>
        </div>
        <div class="team-card">
          <img src="{{ asset('images/liezel.png') }}" alt="Liezel Bernadino" class="team-photo">
          <h3>LIEZEL BERNADINO</h3>
          <p class="position">Liaison Officer</p>
        </div>
        <div class="team-card">
          <img src="{{ asset('images/camille.png') }}" alt="Camille Gatre" class="team-photo">
          <h3>CAMILLE GATRE</h3>
          <p class="position">Accounting Clerk</p>
        </div>
      </div>
    </section>
    
  <script>
    document.getElementById('burger').addEventListener('click', function () {
      document.getElementById('navLinks').classList.toggle('show');
    });
  </script>

  <!-- Footer -->
  <footer>
    <p>LBJ Bookkeeping and Accounting Services | No. 1062 Blumentritt St., Brgy 513, Sampaloc, Manila | Copyright 2020-2026 | All Rights Reserved</p>
  </footer>

  <!-- Script -->
  <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
