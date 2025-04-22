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
                    <li>
                        <i class="fa-solid fa-book"></i>
                        <a href="#">Journals</a>
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
            <div class="nav-section">
                <p>Manage</p>
                <div class="nav-section__item">
                    <li class="{{ request()->routeIs('clients.index') ? 'active-tab' : '' }}">
                        <i class="fa-solid fa-briefcase"></i>
                        <a href="{{ route('clients.index') }}">Clients</a>
                    </li>
                    <li class="{{ request()->routeIs('staff.index') ? 'active-tab' : '' }}">
                        <i class="fa-solid fa-users"></i>
                        <a href="{{ route('staff.index') }}">Staff</a>
                    </li>
                </div>
            </div>
        </ul>
    </div>
</nav>
