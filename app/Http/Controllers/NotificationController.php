<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class NotificationController extends Controller
{
    public function notifications(Request $request)
    {
        $notifications = Notification::where('user_id', '=', $request->user()->id)
            ->orderByDesc('created_at')
            ->get();
        Log::info($notifications);

        return Response::json([
            'notifications' => $notifications,
        ]);
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
    }
}
