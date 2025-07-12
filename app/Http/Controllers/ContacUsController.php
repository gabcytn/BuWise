<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\ContactUsEmail;
use Illuminate\Http\Request;

class ContacUsController extends Controller
{
    //
    public function index(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        $user = User::where('email', '=', 'cayetanogabriel03@outlook.com')->first();
        $user->notify(new ContactUsEmail($request->full_name, $request->email, $request->message));
        return redirect()->to('/contact');
    }
}
