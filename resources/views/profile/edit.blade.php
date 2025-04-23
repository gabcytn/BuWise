<x-app-layout>
    @vite('resources/css/profile/index.css')
    <div class="container">
        @include('profile.partials.update-profile-information-form')

        @include('profile.partials.update-password-form')

        @include('profile.partials.disable-2fa')
    </div>
</x-app-layout>
