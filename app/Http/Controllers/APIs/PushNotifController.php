<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Models\ExpoToken;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PushNotifController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        return Response::json([
            'notifications' => $user->notifications,
        ]);
    }

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

        return Response::json([
            'message' => 'Successfully saved token',
        ]);
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return response(null, 200);
    }
}
