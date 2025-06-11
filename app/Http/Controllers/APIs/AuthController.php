<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password) || $user->role_id === Role::BOT) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return Response::json([
            'role' => $user->role->name,
            'token' => $user->createToken($request->device_name)->plainTextToken
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'old_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->old_password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user->password = $request->new_password;
        $user->save();

        return Response::json([
            'message' => 'Successfully changed password'
        ]);
    }

    public function user(Request $request)
    {
        return DB::table('users')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->where('users.id', '=', $request->user()->id)
            ->select(
                'users.name',
                'roles.name AS userType',
                'users.email'
            )
            ->first();
    }
}
