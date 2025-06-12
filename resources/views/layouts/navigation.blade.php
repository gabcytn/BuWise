
@php
    use App\Models\Role;

    $roleId = request()->user()->role_id;
    $routeName = request()->route()->getName();
@endphp
@vite('resources/js/components/nav.js')
@vite('resources/css/components/navigation.css')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700&display=swap" rel="stylesheet">

<div class="sidebar">
    <div class="logo">
        <img src="{{ asset('images/Buwiselogo.png') }}" alt="BuWise Logo" class="logo-icon">
    </div>

    <ul class="nav">
            <li class="section-title">Home</li>

        <li><a href="#"><i class="fas fa-home"></i> Dashboard</a></li>

        <li class="dropdown">
            <a href="#"><i class="fas fa-calendar-alt"></i> Calendar</a>
            <ul>
                <li><a href="#">Scheduling</a></li>
            </ul>
        </li>

        <li class="section-title">Transactions</li>
        <li><a href="#"><i class="fas fa-file-invoice"></i> Invoices</a></li>

        <li class="dropdown">
            <a href="#"><i class="fas fa-book"></i> Journals</a>
            <ul>
                <li><a href="#">Current</a></li>
                <li><a href="#">Create</a></li>
                <li><a href="#">Archives</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a href="#"><i class="fas fa-balance-scale"></i> Ledger</a>
            <ul>
                <li><a href="#">Ledger Summary</a></li>
                <li><a href="#">Trial Balance</a></li>
            </ul>
        </li>

        <li class="section-title">Reports</li>

        <li class="dropdown">
            <a href="#"><i class="fas fa-chart-line"></i> Insights</a>
            <ul>
                <li><a href="#">Bookkeeper</a></li>
                <li><a href="#">Client</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a href="#"><i class="fas fa-file-alt"></i> Statements</a>
            <ul>
                <li><a href="#">Balance Sheet</a></li>
                <li><a href="#">P/L Statement</a></li>
            </ul>
        </li>

        <li><a href="#"><i class="fas fa-clipboard-check"></i> Audit</a></li>

        <li class="section-title">Users</li>
        <li><a href="#"><i class="fas fa-user"></i> Clients</a></li>
        <li><a href="#"><i class="fas fa-users-cog"></i> Staff</a></li>

        <li class="section-title">Policies</li>
        <li><a href="#"><i class="fas fa-shield-alt"></i> Privacy Policy</a></li>
        <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
    </ul>
</div>
