@php
    use App\Models\Role;
    use Illuminate\Support\Facades\Cache;

    $user = request()->user();
    $roleId = $user->role_id;
    $routeName = request()->route()->getName();

    $organization = Cache::rememberForever($user->id . '-organization', function () use ($user) {
        return $user->organization;
    });
@endphp
@vite('resources/js/components/nav.js')
<nav class="nav-sm hidden">
    <div>
        <div class="nav-brand">
            <img src="{{ asset('images/nav-logo.png') }}" alt="Company Logo" id="nav-logo" />
            <a href="/" id="app-name">{{ config('app.name') }}</a>
        </div>
        <ul class="nav-list">
            <div class="nav-section">
                <p>Home</p>
                <div class="nav-section__item">
                    <li class="{{ request()->routeIs('dashboard') ? 'active-tab' : '' }}">
                        <a href="{{ route('dashboard') }}">
                            <i class="fa-solid fa-gauge"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="">
                        <a href="#"
                            class="nav-dropdown {{ in_array($routeName, ['tasks.index', 'tasks.todo']) ? 'active-tab' : '' }}">
                            <i class="fa-solid fa-calendar-days"></i>
                            Calendar
                        </a>
                        <ul class="dropdown-list">
                            <li class="d-none">
                                <a href="{{ route('tasks.index') }}" style="font-size: 0.75rem;">
                                    Calendar
                                </a>
                            </li>
                            <li class="d-none">
                                <a href="{{ route('tasks.todo') }}" style="font-size: 0.75rem;">
                                    To Do
                                </a>
                            </li>
                        </ul>
                    </li>
                </div>
            </div>
            <div class="nav-section">
                <p>Transactions</p>
                <div class="nav-section__item">
                    <li class="">
                        <a href="#"
                            class="nav-dropdown {{ in_array($routeName, ['invoices.index', 'invoices.create', 'invoices.show']) ? 'active-tab' : '' }}">
                            <i class="fa-solid fa-file-invoice"></i>
                            Invoice
                        </a>
                        <ul class="dropdown-list">
                            <li class="d-none">
                                <a href="{{ route('invoices.index') }}" style="font-size: 0.75rem;">
                                    Successful
                                </a>
                            </li>
                            <li class="d-none">
                                <a href="{{ route('failed.index') }}" style="font-size: 0.75rem;">
                                    Failed
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="">
                        <a href="#"
                            class="nav-dropdown {{ in_array($routeName, ['journal-entries.index', 'journal-entries.create', 'journal-entries.show', 'journal-entries.edit', 'journal-entries.archives']) ? 'active-tab' : '' }}">
                            <i class="fa-solid fa-book"></i>
                            Journals
                        </a>
                        <ul class="dropdown-list">
                            <li class="d-none">
                                <a href="{{ route('journal-entries.index') }}" style="font-size: 0.75rem;">
                                    Current
                                </a>
                            </li>
                            <li class="d-none">
                                <a href="{{ route('journal-entries.create') }}" style="font-size: 0.75rem;">
                                    Create
                                </a>
                            </li>
                            <li class="d-none">
                                <a href="{{ route('journal-entries.archives') }}" style="font-size: 0.75rem;">
                                    Archives
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="">
                        <a href="#"
                            class="nav-dropdown {{ in_array($routeName, ['ledger.coa', 'ledger.coa.show', 'ledger.trial-balance']) ? 'active-tab' : '' }}">
                            <i class="fa-solid fa-book-open"></i>
                            Ledger
                        </a>
                        <ul class="dropdown-list">
                            <li class="d-none">
                                <a href="{{ route('ledger.coa') }}" style="font-size: 0.75rem;">
                                    Ledger Summary
                                </a>
                            </li>
                            <li class="d-none">
                                <a href="{{ route('ledger.trial-balance') }}" style="font-size: 0.75rem;">
                                    Trial Balance
                                </a>
                            </li>
                        </ul>
                    </li>
                </div>
            </div>
            <div class="nav-section">
                <p>Reports</p>
                <div class="nav-section__item">
                    <li class="{{ request()->routeIs('reports.insights') ? 'active-tab' : '' }}">
                        <a href="{{ route('reports.insights') }}">
                            <i class="fa-solid fa-chart-line"></i>
                            Insights
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="nav-dropdown {{ in_array($routeName, ['reports.income-statement', 'reports.balance-sheet']) ? 'active-tab' : '' }}">
                            <i class="fa-solid fa-newspaper"></i>
                            Statements
                        </a>
                        <ul class="dropdown-list">
                            <li class="d-none">
                                <a href="{{ route('reports.balance-sheet') }}" style="font-size: 0.75rem;">
                                    Balance Sheet
                                </a>
                            </li>
                            <li class="d-none">
                                <a href="{{ route('reports.income-statement') }}" style="font-size: 0.75rem;">
                                    P/L Statement
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ request()->routeIs('reports.working-paper') ? 'active-tab' : '' }}">
                        <a href="{{ route('reports.working-paper') }}">
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
            <div class="nav-section">
                <p>Policies</p>
                <div class="nav-section__item">
                    <li class="{{ request()->routeIs('profile.edit') ? 'active-tab' : '' }}">
                        <a href="{{ route('profile.edit') }}">
                            <i class="fa-solid fa-cog"></i>
                            Settings
                        </a>
                    </li>
                </div>
            </div>
        </ul>
    </div>
    <div id="client-info">
        <img src="{{ asset('storage/organizations/' . $organization->logo) }}" alt="Client Image" />
        <div>
            <h4 title="{{ $organization->name }}">{{ truncate($organization->name, 16) }}</h4>
            <p title="{{ $organization->address }}">{{ truncate($organization->address, 16) }}</p>
        </div>
    </div>
</nav>
