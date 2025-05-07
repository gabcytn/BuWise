<!DOCTYPE html>

<html lang="en">
<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@vite(['resources/css/services.css'])
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'LBJ Services') }}</title>
  <link rel="stylesheet" href="{{ asset('css/services.css') }}">
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
  <!-- Hero Section -->
  <section class="hero" style="background: url('{{ asset('images/hero.jpg') }}') center/cover no-repeat;">
    <div class="overlay">
      <p>COMPANY OFFERS</p>
      <h1>
        <span class="highlight"><strong>SERVICES</strong></span> 
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
  
  
  <!-- Services Section -->
  <section id="services" class="services-section">
      <h2 class="section-title">Start your business with us</h2>
      <p class="section-subtitle">Our automation-driven approach allows us to provide real-time financial insights, transparent reporting, and compliance-focused solutions.</p>
      <div class="services-grid">
          <div class="service-card">
              <img src="{{ asset('images/services1.png') }}" alt="Service 1">
              <h3>Business Registration & Compliance</h3>
              <p>From securing tax IDs to setting up mandatory government contributions, we handle the paperwork.</p>
          </div>
          <div class="service-card">
              <img src="{{ asset('images/services2.png') }}" alt="Service 2">
              <h3>Bookkeeping & Financial Management</h3>
              <p>We provide real-time reports to help you make informed decisions aligned with standards.</p>
          </div>
          <div class="service-card">
              <img src="{{ asset('images/services3.png') }}" alt="Service 3">
              <h3>Payroll & Tax Compliance</h3>
              <p>We manage payroll, taxes, and filings to ensure employees are paid correctly and on time.</p>
          </div>
      </div>
  </section>
  
  <section class="rpa-section centered-rpa">
    <div class="rpa-content">
      <div class="rpa-text">
        <h2><strong>Using the Power of RPA to </strong>
          <strong>Automate our Workflows</strong></h2>
  
        <p>
          At LBJ Bookkeeping and Accounting Services, we integrate Robotic Process Automation (RPA) to streamline and optimize our financial processes, ensuring accuracy, efficiency, and compliance. By leveraging automation, we reduce manual tasks, minimize human error, and provide real-time financial insights to our clients.
        </p>
  
        <div class="rpa-highlight">
          <img src="{{ asset('images/roboticon.png') }}" alt="Robot Icon" class="rpa-icon">
          <strong>Experience RPA in real-time and automate the bookkeeping experience!</strong>
        </div>
  
        <p>
          We help businesses focus on growth and success while we handle the complexities of financial management. Download the BuWise app now!
        </p>
      </div>
    </div>
  
    <div class="rpa-image">
      <img src="{{ asset('images/rpa.png') }}" alt="RPA Automation">
    </div>
  </section>
  
  
  <!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Contact Section -->
<section class="contact-section">
  <div class="container">
    <div class="contact-form">
      <form>
        <input type="text" placeholder="Your Company Name" required>
        <input type="email" placeholder="Your Email" required>
        <textarea placeholder="Your Message" required></textarea>
        <button type="submit" class="btn-submit">Submit</button>
      </form>
    </div>
    <div class="contact-info">
      <h3>Be A Client</h3>
      <p>
        Interested in our services? Contact us to become a client of LBJ Services today.
        Fill up the form or contact our respective company details.
      </p>
      <ul>
        <li><i class="fas fa-phone"></i> <strong>Call Us:</strong> +63 209 637 810</li>
        <li><i class="fas fa-envelope"></i> <strong>Email Us:</strong> lbjservices@gmail.com</li>
        <li><i class="fas fa-globe"></i> <strong>Website:</strong> lbjservices.com</li>
        <li><i class="fas fa-map-marker-alt"></i> <strong>Address:</strong> 1072 Blumemtritt St., Manila</li>
      </ul>
    </div>
  </div>
</section>

<script>
  document.getElementById('burger').addEventListener('click', function () {
    document.getElementById('navLinks').classList.toggle('show');
  });
</script>
<!-- Footer -->
<footer class="footer">
  <div class="container">
    <p>LBJ Bookkeeping and Accounting Services | No. 1062 Blumemtritt St., Brgy 513, Sampaloc, Manila | Copyright 2020–2026</p>
  </div>
</footer>
