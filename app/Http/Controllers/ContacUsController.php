<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContacUsController extends Controller
{
    /*
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        return redirect()->to('/contact');
    }
}
