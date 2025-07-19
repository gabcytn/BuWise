<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Conversation $conversation)
    {
        // TODO: add gate authorize
        $user = $request->user();
        $messages = Message::where('conversation_id', '=', $conversation->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        // determines if current user is sender or receiver of the message
        foreach ($messages as $message)
            $message->sent = $message->user_id === $user->id;

        return Response::json([
            'messages' => $messages,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Conversation $conversation)
    {
        // TODO: gate authorization
        $request->validate([
            'message' => 'required|string|max:255',
        ]);
        $user = $request->user();

        Message::create([
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
            'message' => $request->message,
        ]);

        $receivers = $conversation->members()->where('user_id', '!=', $user->id)->get();
        // TODO: notify member
        foreach ($receivers as $receiver) {
            // $receiver->notify();
        }

        return response(null, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }
}
