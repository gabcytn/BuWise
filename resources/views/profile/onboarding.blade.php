<x-root-layout>
    @vite(['resources/css/components/header.css', 'resources/css/profile/onboarding.css'])
    <div class="header-container">
        <header class="header-sm">
            <div class="nav-brand">
                <img src="{{ asset('images/nav-logo.png') }}" alt="Company Logo" id="nav-logo" />
                <h3 id="app-name">{{ config('app.name') }}</h3>
            </div>
            <div class="header-side">
                <div class="header-side__account" style="cursor: pointer;">
                    @php
                        $profileImg = request()->user()->profile_img;
                        if ($profileImg) {
                            $url = asset('storage/profiles/' . $profileImg);
                        } else {
                            $url = 'https://placehold.co/40';
                        }
                    @endphp
                    <img src="{{ $url }}" alt="Profile Image" width="40" height="40" />
                    <div class="header-side__account--details">
                        <p id="account-name">{{ request()->user()->name }}</p>
                        <p id="account-role">{{ ucfirst(request()->user()->role->name) }}</p>
                    </div>
                    <i class="fa-solid fa-circle-chevron-down"></i>
                </div>
                <i class="fa-solid fa-bell" id="notifToggle"></i>
            </div>
        </header>
    </div>
    <section class="main-body">
        <div class="form-container">
            <form id="organization-form" method="POST" action="{{ route('organizations.store') }}"
                enctype="multipart/form-data">
                @csrf
                <div class="greetings">
                    <h1>Welcome to BuWise!</h1>
                    <p>Tell us more about your bookkeeping firm.</p>
                </div>
                <div class="input-box">
                    <label for="organization-name">Organization Name<span>*</span></label>
                    <input required type="text" name="name" id="organization-name" />
                    <p><i class="fa-solid fa-circle-info"></i>Organization names help us to store your firm’s data and
                        manage your employees better. Your
                        clients are also automatically part of your organization if they download <a
                            href="#">BuWise Mobile.</a></p>
                </div>
                <div class="input-box">
                    <label for="organization-address">Organization Address<span>*</span></label>
                    <input required type="text" name="address" id="organization-address" />
                </div>
                <div class="input-box">
                    <label for="organization-logo">Organization Logo<span>*</span></label>
                    <input required type="file" name="logo" id="organization-logo" />
                    <p><i class="fa-solid fa-circle-info"></i>Upload your organization logo to identify your firm
                        better.</p>
                </div>
            </form>
            <hr />
            <div class="bottom-section">
                <button type="submit" form="organization-form">Let's Get Started!</button>
                <a href="#">Privacy Policy</a>
            </div>
        </div>
    </section>
</x-root-layout>
