<x-app-layout>
    @vite(['resources/css/user-management/index.css', 'resources/js/user-management/index.js'])
    <div class="container">
        <div class="container-header">
            <div class="container-header__texts">
                <h1 id="page-title">{{ $title }}</h1>
                <p id="page-subtitle">{{ $subtitle }}</p>
            </div>
            <div class="container-header__btn">
                <button class="main-blue" id="open-dialog-btn">+ {{ $buttonText }}</button>
            </div>
        </div>

        <div class="users-content">
            <form class="header-controls">
                <!-- Period Filter -->
                <div class="header-controls__left">
                    <select id="period" class="period-select" name="period">
                        <option value="all" selected>Period: All Time</option>
                        <option value="today">Period: Today</option>
                        <option value="week">Period: This Week</option>
                        <option value="month">Period: This Month</option>
                    </select>

                    <!-- Status Filter -->
                    <div>
                        <select id="status" class="status-select" name="filter">
                            <option {{ request()->query('filter') === null ? 'selected' : '' }} value="">Order By:
                                Id</option>
                            <option {{ request()->query('filter') === 'name' ? 'selected' : '' }} value="name">Order
                                By: Name</option>
                            <option {{ request()->query('filter') === 'date' ? 'selected' : '' }} value="date">Order
                                By: Date</option>
                        </select>
                    </div>
                </div>

                <!-- Search Form -->
                <div class="header-controls__right">
                    <input value="{{ request()->query('search') }}" id="search" name="search" type="search"
                        placeholder="Search Names" />
                    <button class="main-blue" type="submit">Run</button>
                </div>
            </form>
            <!-- TABLE -->
            {{ $slot }}
        </div>


    </div>
</x-app-layout>
