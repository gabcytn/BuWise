<x-app-layout title="Settings">
    @vite('resources/css/profile/index.css')
    <div class="container">
        @include('profile.partials.update-profile-information-form')
        @include('profile.partials.privacy-and-security')
    </div>
</x-app-layout>
