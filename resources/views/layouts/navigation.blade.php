@php
    use App\Models\Role;

    $roleId = request()->user()->role_id;
    $routeName = request()->route()->getName();
@endphp
@vite('resources/js/components/nav.js')

<nav class="nav-sm hidden">
    <div>
        <div class="nav-brand">
            <img src="{{ asset('images/nav-logo.png') }}" alt="Company Logo" id="nav-logo" loading="lazy" />
            <h3 id="app-name">{{ config('app.name') }}</h3>
        </div>
        <ul class="nav-list">
            <!-- Dashboard Section -->
            <div class="nav-section">
                <ul class="nav-section__item">
                    <li @class(['active-tab' => request()->routeIs('dashboard')])>
                        <a href="{{ route('dashboard') }}">
                            <i class="fa-solid fa-gauge"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="#"><i class="fa-solid fa-calendar-days"></i> Calendar</a>
                    </li>
                </ul>
            </div>

            <!-- Transactions Section -->
            <div class="nav-section">
                <p>Transactions</p>
                <ul class="nav-section__item">
                    <!-- Invoices Dropdown -->
                    <li x-data="{ open: false }">
                        <a href="#" @click.prevent="open = !open" class="nav-dropdown" :class="{ 'active-tab': open || ['invoices.index', 'invoices.files'].includes('{{ $routeName }}') }">
                            <i class="fa-solid fa-file-invoice"></i>
                            Invoices
                        </a>
                        <ul class="dropdown-list" x-show="open" x-transition>
                            <li>
                                <a href="{{ route('invoices.index') }}">
                                    <i class="fa-solid fa-file-invoice"></i>
                                    Invoice Management
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('invoices.files') }}">
                                    <i class="fa-solid fa-folder-open"></i>
                                    Invoice Files
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Journals -->
                    <li @class(['active-tab' => in_array($routeName, ['journal-entries.index', 'journal-entries.create', 'journal-entries.show', 'journal-entries.edit'])])>
                        <a href="{{ route('journal-entries.index') }}">
                            <i class="fa-solid fa-book"></i> Journals
                        </a>
                    </li>

                    <!-- Ledger -->
                    <li>
                        <a href="#" class="nav-dropdown {{ in_array($routeName, ['ledger.coa', 'ledger.trial-balance']) ? 'active-tab' : '' }}">
                            <i class="fa-solid fa-book-open"></i> Ledger
                        </a>
                        <ul class="dropdown-list">
                            <li class="d-none">
                                <a href="{{ route('ledger.coa') }}">
                                    <i class="fa-solid fa-receipt"></i> Ledger Summary
                                </a>
                            </li>
                            <li class="d-none">
                                <a href="{{ route('ledger.trial-balance') }}">
                                    <i class="fa-solid fa-receipt"></i> Trial Balance
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>

            <!-- Reports Section -->
            <div class="nav-section">
                <p>Reports</p>
                <ul class="nav-section__item">
                    <li>
                        <a href="#"><i class="fa-solid fa-chart-line"></i> Insights</a>
                    </li>

                    <!-- Statements Dropdown -->
                    <li x-data="{ open: false }">
                        <a href="#" @click.prevent="open = !open" class="nav-dropdown" :class="{ 'active-tab': open || ['statements.income', 'statements.balance'].includes('{{ $routeName }}') }">
                            <i class="fa-solid fa-newspaper"></i>
                            Statements
                        </a>
                        <ul class="dropdown-list" x-show="open" x-transition>
                            <li>
                                <a href="{{ route('statements.income') }}">
                                    <i class="fa-solid fa-chart-pie"></i> Income Statement
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('statements.balance') }}">
                                    <i class="fa-solid fa-scale-balanced"></i> Balance Sheet
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#"><i class="fa-solid fa-hospital-user"></i> Audit</a>
                    </li>
                </ul>
            </div>

            <!-- Manage Section -->
            @if ($roleId !== Role::CLERK)
                <div class="nav-section">
                    <p>Manage</p>
                    <ul class="nav-section__item">
                        <li @class(['active-tab' => in_array($routeName, ['clients.index', 'clients.edit'])])>
                            <a href="{{ route('clients.index') }}">
                                <i class="fa-solid fa-briefcase"></i> Clients
                            </a>
                        </li>
                        @if ($roleId === Role::ACCOUNTANT)
                            <li @class(['active-tab' => in_array($routeName, ['staff.index', 'staff.edit'])])>
                                <a href="{{ route('staff.index') }}">
                                    <i class="fa-solid fa-users"></i> Staff
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif
        </ul>
    </div>
</nav>
