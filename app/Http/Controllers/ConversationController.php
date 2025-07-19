<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $org = $user->organization;

        $conversations = Conversation::with(['messages', 'members.user'])->whereHas('members', function ($query) use ($user) {
            return $query->with('user')->where('user_id', '=', $user->id);
        })->get();

        foreach ($conversations as $convo) {
            if ($org->conversation_id === $convo->id) {
                $org = $user->organization;
                $convo->image = asset('storage/organizations/' . $org->logo);
                $convo->name = $org->name;
            } else {
                $members = $convo->members;
                $sender = $members->where('user.id', '!=', $user->id)->first()->user;
                $convo->image = asset('storage/profiles/' . $sender->profile_img);
                $convo->name = $sender->name;
            }
            $latest_message = $convo->messages()->orderByDesc('id')->first();
            if ($latest_message) {
                $convo->latest_message = $latest_message->message;
                $convo->latest_message_time = $latest_message->created_at;
            } else {
                $convo->latest_message = 'No messages yet.';
                $convo->latest_message_time = '';
            }
        }
        return view('conversations.index', [
            'conversations' => $conversations->sortByDesc('latest_message_time'),
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Conversation $conversation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Conversation $conversation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Conversation $conversation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conversation $conversation)
    {
        //
    }
}
