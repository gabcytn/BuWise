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
        <img src="{{ asset('images/navbuwise.png') }}" alt="BuWise Logo" class="logo-icon">
    </div>

    <ul class="nav">
        <li class="section-title">Home</li>

        <li><a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Dashboard</a></li>

        <li class="dropdown">
            <a href="#"><i class="fas fa-calendar-alt"></i> Calendar</a>
            <ul>
                <li><a href="{{ route('tasks.index') }}">Scheduling</a></li>
                <li><a href="{{ route('tasks.todo') }}">To Do</a></li>
            </ul>
        </li>

        <li class="section-title">Transactions</li>
        <li><a href="{{ route('invoices.index') }}"><i class="fas fa-file-invoice"></i> Invoices</a></li>

        <li class="dropdown">
            <a href="#"><i class="fas fa-book"></i> Journals</a>
            <ul>
                <li><a href="{{ route('journal-entries.index') }}">Current</a></li>
                <li><a href="{{ route('journal-entries.create') }}">Create</a></li>
                <li><a href="{{ route('journal-entries.archives') }}">Archives</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a href="#"><i class="fas fa-balance-scale"></i> Ledger</a>
            <ul>
                <li><a href="{{ route('ledger.coa') }}">Ledger Summary</a></li>
                <li><a href="{{ route('ledger.trial-balance') }}">Trial Balance</a></li>
            </ul>
        </li>

        <li class="section-title">Reports</li>

        <li class="dropdown">
            <a href="#"><i class="fas fa-chart-line"></i> Insights</a>
            <ul>
                <li><a href="#">Bookkeeper</a></li>
                <li><a href="{{ route('reports.insights') }}">Client</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a href="#"><i class="fas fa-file-alt"></i> Statements</a>
            <ul>
                <li><a href="{{ route('reports.balance-sheet') }}">Balance Sheet</a></li>
                <li><a href="{{ route('reports.income-statement') }}">P/L Statement</a></li>
            </ul>
        </li>

        <li><a href="{{ route('reports.working-paper') }}"><i class="fas fa-clipboard-check"></i> Audit</a></li>

        <li class="section-title">Users</li>
        <li><a href="{{ route('clients.index') }}"><i class="fas fa-user"></i> Clients</a></li>
        <li><a href="{{ route('staff.index') }}"><i class="fas fa-users-cog"></i> Staff</a></li>

        <li class="section-title">Policies</li>
        <li><a href="#"><i class="fas fa-shield-alt"></i> Privacy Policy</a></li>
        <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
    </ul>
</div>
