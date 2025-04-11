<x-app-layout>
    @vite(['resources/css/user-management/index.css', 'resources/js/user-management/index.js'])
    <div class="container">
        <h1 id="page-title">{{ $title }}</h1>
        <p id="page-subtitle">{{ $subtitle }}</p>
        <div class="headers">
            <div style="display: flex; gap: 0.5rem;" class="headers-first-child">
                <div class="headers-child filter-box">
                    <div class="filter-group">
                        <button class="filter-button">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#6c7987"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg>
                        </button>
                        <div class="filter-divider"></div>
                        <select class="type-select">
                            <option value="" selected disabled>Type</option>
                            <option value="name">Name</option>
                            <option value="date">Date</option>
                        </select>
                        <div class="filter-divider"></div>
                        <button class="refresh-button">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#ff3366"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M23 4v6h-6"></path>
                                <path d="M1 20v-6h6"></path>
                                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10"></path>
                                <path d="M20.49 15a9 9 0 0 1-14.85 3.36L1 14"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="headers-child search-box">
                    <form action="#" method="GET" id="search-form">
                        <input id="search" name="search" type="text" placeholder="Search" required />
                    </form>
                </div>
            </div>
            <div class="headers-child button-box">
                <button id="open-dialog-btn">{{ $buttonText }}</button>
            </div>
        </div>
        <!-- TABLE -->
        {{ $slot }}
    </div>
</x-app-layout>
