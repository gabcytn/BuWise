<!DOCTYPE html>

<html lang="en">
<head>
@vite(['resources/css/welcome.css'])
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'LBJ Services') }}</title>
  <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
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
      <li><a href="/register">Register</a></li>
      <li><a class="login-btn" href="/login">Log In</a></li>
    @endauth
  </ul>
</nav>
  </header>

  <!-- Hero -->
  <section class="hero" style="background: url('{{ asset('images/hero.jpg') }}') center/cover no-repeat;">
    <div class="overlay">
      <h1>
        <span class="highlight"><strong>OUTSOURCED</strong></span> BOOKKEEPING AND 
        <span class="highlight"><strong>STREAMLINED</strong></span> ACCOUNTING SERVICES
      </h1>
      <p>Reliable & efficient <strong>outsourced</strong> bookkeeping and <strong>streamlined</strong> accounting services, 
      saving you time, reducing costs, and ensuring financial accuracy.</p>
      <div class="hero-buttons">
        <a href="#about" class="btn">Learn More</a>
        <a href="#services" class="btn btn-secondary">Get Started</a>
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

  <!-- Services Section -->
  <section class="our-services" id="services">
    <h2>OUR SERVICES</h2>
    <p class="services-intro">
      LBJ Bookkeeping and Accounting Services, led by Lallaine Bernardino Jayag, provides external bookkeeping and accounting services to over 60 clients across various industries, ensuring accuracy, efficiency, and financial peace of mind.
    </p>
    <div class="services">
      <div class="service-box green">
        <img src="{{ asset('images/invoice.png') }}" alt="icon" class="icon-side" />
        <h3>Invoice Management</h3>
        <p>Efficient processing of invoices, allowing clients to upload their invoices through the web app. Our automated system ensures accuracy and timely payments, reducing errors and delays.</p>
      </div>
      <div class="service-box gray">
        <img src="{{ asset('images/ledger.png') }}" alt="icon" class="icon-side" />
        <h3 class="blue-text">Ledger Management</h3>
        <p>We maintain and reconcile your financial records with precision for clear and organized bookkeeping. Using RPA technology, we ensure every transaction is accurately recorded and error-free.</p>
      </div>
      <div class="service-box gray">
        <img src="{{ asset('images/finance.png') }}" alt="icon" class="icon-side" />
        <h3 class="blue-text">Financial Reports</h3>
        <p>We create real-time and customized financial reports such as balance sheets and profit-loss statements. These documents help you make informed business decisions with confidence.</p>
      </div>
      <div class="service-box gray">
        <img src="{{ asset('images/bir.png') }}" alt="icon" class="icon-side" />
        <h3 class="blue-text">BIR Compliance</h3>
        <p>Stay compliant with BIR regulations and avoid tax issues. We assist with audit preparation, tax submissions, and filing to avoid penalties and maximize deductions.</p>
      </div>
      <div class="service-box gray">
        <img src="{{ asset('images/trans.png') }}" alt="icon" class="icon-side" />
        <h3 class="blue-text">Transparency</h3>
        <p>We believe in full transparency when it comes to managing your financials. Clients have real-time access to their bookkeeper's schedule, deadlines, and task progress.</p>
      </div>
      <div class="service-box gray">
        <img src="{{ asset('images/smart.png') }}" alt="icon" class="icon-side" />
        <h3 class="blue-text">Smart Notifications</h3>
        <p>Stay informed with real-time alerts on tax deadlines, invoice statuses, report availability, completed tasks, upcoming calendar events, and to-do list reminders.</p>
      </div>
      <div class="service-box gray">
        <img src="{{ asset('images/security.png') }}" alt="icon" class="icon-side" />
        <h3 class="blue-text">Data Security & Backup</h3>
        <p>Your financial data is protected with end-to-end encryption and automated backups. We prioritize confidentiality and disaster recovery to safeguard your business records.</p>
      </div>
      <div class="service-box green">
        <img src="{{ asset('images/vis.png') }}" alt="icon" class="icon-side" />
        <h3>Visualizations</h3>
        <p>Dynamic graphics and customizable financial projection models help businesses visualize data and plan for future expenses or comparisons.</p>
      </div>
    </div>
  </section>

  <!-- Trusted Section -->
  <section class="trusted">
    <h3>TRUSTED BY <span class="highlight">MICRO-SMALL-MEDIUM ENTREPRENEURS (MSMEs)</span> EVERYWHERE</h3>
    <div class="trusted-logos">
      <img src="{{ asset('images/pspi.png') }}" alt="PSPI Logo">
      <img src="{{ asset('images/hotel.png') }}" alt="A Hotel Logo">
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
