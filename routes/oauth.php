<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/auth/redirect', function () {
    return Socialite::driver('google')
        ->with(['prompt' => 'select_account'])
        ->redirect();
})->name('google.auth.redirect');

Route::get('/auth/callback', function () {
    $google_user = Socialite::driver('google')->user();

    $user = User::where('email', '=', $google_user->getEmail())->first();
    if ($user && $user->google_id) {
        request()->session()->put([
            'login.id' => $user->id,
            'login.remember' => true,
        ]);

        return to_route('two-factor.login');
    } else if ($user && !$user->google_id) {
        return to_route('login')->withErrors(['This account was created via email & password. Please sign in using the same method.']);
    } else if (!$user) {
        $created_user = User::create([
            'google_id' => $google_user->getId(),
            'name' => $google_user->getName(),
            'email' => $google_user->getEmail(),
            'password' => '',
            'email_verified_at' => now(),
        ]);
        Auth::login($created_user, true);
        return to_route('dashboard');
    }
})->name('google.auth.callback');
