@php
    use App\Models\Role;

    $roleId = request()->user()->role_id;
    $routeName = request()->route()->getName();
@endphp
<nav class="nav-sm hidden">
    <div>
        <div class="nav-brand">
            <img src="{{ asset('images/nav-logo.png') }}" alt="Company Logo" id="nav-logo" />
            <h3 id="app-name">{{ config('app.name') }}</h3>
        </div>
        <ul>
            <div class="nav-section">
                <div class="nav-section__item">
                    <li class="{{ request()->routeIs('dashboard') ? 'active-tab' : '' }}">
                        <i class="fa-solid fa-gauge"></i>
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li>
                        <i class="fa-solid fa-calendar-days"></i>
                        <a href="#">Calendar</a>
                    </li>
                </div>
            </div>
            <div class="nav-section">
                <p>Transactions</p>
                <div class="nav-section__item">
                    <li>
                        <i class="fa-solid fa-file-invoice"></i>
                        <a href="#">Invoice</a>
                    </li>
                    <li
                        class="{{ in_array($routeName, ['journal-entries.index', 'journal-entries.create']) ? 'active-tab' : '' }}">
                        <i class="fa-solid fa-book"></i>
                        <a href="{{ route('journal-entries.index') }}">Journals</a>
                    </li>
                    <li>
                        <i class="fa-solid fa-book-open"></i>
                        <a href="#">Ledger</a>
                    </li>
                </div>
            </div>
            <div class="nav-section">
                <p>Reports</p>
                <div class="nav-section__item">
                    <li>
                        <i class="fa-solid fa-chart-line"></i>
                        <a href="#">Insights</a>
                    </li>
                    <li>
                        <i class="fa-solid fa-newspaper"></i>
                        <a href="#">Statements</a>
                    </li>
                    <li>
                        <i class="fa-solid fa-hospital-user"></i>
                        <a href="#">Audit</a>
                    </li>
                </div>
            </div>
            @if ($roleId !== Role::CLERK)
                <div class="nav-section">
                    <p>Manage</p>
                    <div class="nav-section__item">
                        <li class="{{ in_array($routeName, ['clients.index', 'clients.edit']) ? 'active-tab' : '' }}">
                            <i class="fa-solid fa-briefcase"></i>
                            <a href="{{ route('clients.index') }}">Clients</a>
                        </li>
                        @if ($roleId === Role::ACCOUNTANT)
                            <li class="{{ in_array($routeName, ['staff.index', 'staff.edit']) ? 'active-tab' : '' }}">
                                <i class="fa-solid fa-users"></i>
                                <a href="{{ route('staff.index') }}">Staff</a>
                            </li>
                        @endif
                    </div>
                </div>
            @endif
        </ul>
    </div>
</nav>
