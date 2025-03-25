<x-app-layout>
    @vite(['resources/css/user-management/index.css'])
    <div class="container">
        <h1 id="page-title">{{ $title }}</h1>
        <p id="page-subtitle">{{ $subtitle }}</p>
        <div class="headers">
            <div class="headers-child">
                <i class="fa-solid fa-filter"></i>
                <p>Filter By</p>
                <select>
                    <option selected disabled>Type</option>
                    <option>Name</option>
                    <option>Date Created</option>
                </select>
                <div role="button">
                    <i class="fa-solid fa-rotate-left"></i>
                    <p>Reset Filter<p>
                </div>

            </div>
            <div class="headers-child">
                <input id="search" name="search" type="text" placeholder="Search" />
            </div>
            <div class="headers-child">
                <button id="open-dialog-btn">{{ $buttonText }}</button>
            </div>
        </div>
        <!-- TABLE -->
        {{ $slot }}
    </div>
</x-app-layout>
