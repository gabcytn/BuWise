<x-app-layout>
    @vite(['resources/css/user-management/index.css', 'resources/js/user-management/index.js'])
    <div class="container">
        <h1 id="page-title">{{ $title }}</h1>
        <p id="page-subtitle">{{ $subtitle }}</p>

        <div class="headers">
            <div class="header-controls">
                <!-- Period Filter -->
                <label for="period" class="period-label"></label>
<select id="period" class="period-select">
    <option value="all" selected>Period: All Time</option>
    <option value="today">Period: Today</option>
    <option value="week">Period: This Week</option>
    <option value="month">This Month</option>
</select>

                

                <!-- Status Filter -->
                <div class="filter-dropdown">
                    <button class="filter-toggle">
                        <img src="/images/filterbyicon.png" alt="Filter Icon" style="width: 16px; height: 16px; margin-right: 0.5rem;">
                        <span>Filter by:</span>
                    </button>
                        <div class="filter-menu">
        <label>Choose filter:</label>
        <ul>
            <li><a href="#" data-filter="status">Status</a></li>
            <li><a href="#" data-filter="id">ID</a></li>
            <li><a href="#" data-filter="business_type">Business Type</a></li>
            <li><a href="#" data-filter="company_name">Company Name</a></li>
        </ul>
                        
                        </select>
                    </div>
                </div>

                <!-- Search Form -->
                <form action="#" method="GET" id="search-form">
                    <input value="{{ request()->query('search') }}" id="search" name="search" type="text"
                        placeholder="Search Clients" required />
                </form>
            </div>

            <!-- New Client Button -->
            <div class="new-client-button">
                <button id="open-dialog-btn">+ New Client</button>
            </div>
        </div>

        <!-- TABLE -->
        {{ $slot }}
    </div>
</x-app-layout>