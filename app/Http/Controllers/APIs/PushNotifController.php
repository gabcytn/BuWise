<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Models\ExpoToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class PushNotifController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);
        $user = $request->user();
        ExpoToken::create([
            'owner_id' => $user->id,
            'owner_type' => $user->role->name,
            'value' => $request->token,
        ]);
        Log::info($user->expoTokens);

        return Response::json([
            'message' => 'Successfully saved token',
        ]);
    }
}
