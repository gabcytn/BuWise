@php
    use App\Models\Role;

    $roleId = request()->user()->role_id;
    $routeName = request()->route()->getName();
@endphp
@vite('resources/js/components/nav.js')
<nav class="nav-sm hidden">
    <div>
        <div class="nav-brand">
            <img src="{{ asset('images/nav-logo.png') }}" alt="Company Logo" id="nav-logo" />
            <h3 id="app-name">{{ config('app.name') }}</h3>
        </div>
        <ul class="nav-list">
            <div class="nav-section">
                <div class="nav-section__item">
                    <li class="{{ request()->routeIs('dashboard') ? 'active-tab' : '' }}">
                        <a href="{{ route('dashboard') }}">
                            <i class="fa-solid fa-gauge"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa-solid fa-calendar-days"></i>
                            Calendar
                        </a>
                    </li>
                </div>
            </div>
            <div class="nav-section">
                <p>Transactions</p>
                <div class="nav-section__item">
                    <li>
                        <a href="#">
                            <i class="fa-solid fa-file-invoice"></i>
                            Invoice
                        </a>
                    </li>
                    <li
                        class="{{ in_array($routeName, ['journal-entries.index', 'journal-entries.create', 'journal-entries.show', 'journal-entries.edit']) ? 'active-tab' : '' }}">
                        <a href="{{ route('journal-entries.index') }}">
                            <i class="fa-solid fa-book"></i>
                            Journals
                        </a>
                    </li>
                    <li class="">
                        <a href="#"
                            class="nav-dropdown {{ in_array($routeName, ['ledger.coa', 'ledger.coa.show']) ? 'active-tab' : '' }}">
                            <i class="fa-solid fa-book-open"></i>
                            Ledger
                        </a>
                        <ul class="dropdown-list">
                            <li class="d-none">
                                <a href="{{ route('ledger.coa') }}" style="font-size: 0.75rem;">
                                    <i class="fa-solid fa-receipt" style="width: auto;"></i>
                                    Ledger Summary
                                </a>
                            </li>
                            <li class="d-none">
                                <a href="#" style="font-size: 0.75rem;">
                                    <i class="fa-solid fa-user-tie" style="width: auto;"></i>
                                    Vendors
                                </a>
                            </li>
                            <li class="d-none">
                                <a href="#" style="font-size: 0.75rem;">
                                    <i class="fa-solid fa-user-tie" style="width: auto;"></i>
                                    Customers
                                </a>
                            </li>
                        </ul>
                    </li>
                </div>
            </div>
            <div class="nav-section">
                <p>Reports</p>
                <div class="nav-section__item">
                    <li>
                        <a href="#">
                            <i class="fa-solid fa-chart-line"></i>
                            Insights
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa-solid fa-newspaper"></i>
                            Statements
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa-solid fa-hospital-user"></i>
                            Audit
                        </a>
                    </li>
                </div>
            </div>
            @if ($roleId !== Role::CLERK)
                <div class="nav-section">
                    <p>Manage</p>
                    <div class="nav-section__item">
                        <li class="{{ in_array($routeName, ['clients.index', 'clients.edit']) ? 'active-tab' : '' }}">
                            <a href="{{ route('clients.index') }}">
                                <i class="fa-solid fa-briefcase"></i>
                                Clients
                            </a>
                        </li>
                        @if ($roleId === Role::ACCOUNTANT)
                            <li class="{{ in_array($routeName, ['staff.index', 'staff.edit']) ? 'active-tab' : '' }}">
                                <a href="{{ route('staff.index') }}">
                                    <i class="fa-solid fa-users"></i>
                                    Staff
                                </a>
                            </li>
                        @endif
                    </div>
                </div>
            @endif
        </ul>
    </div>
</nav>
