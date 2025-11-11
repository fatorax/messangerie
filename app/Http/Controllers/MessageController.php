<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function messageSent(Message $message)
    {
        // $message = Message::create([
        //     'conversation_id' => $conversationId,
        //     'user_id' => auth()->id(),
        //     'content' => $request->content,
        // ]);

        $message = Message::create([
            'conversation_id' => 1,
            'user_id' => 1,
            'content' => "bonjour",
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return view('welcome');
    }

    public function dashboard()
    {
        $user = Auth::user();
        return view('chat.dashboard', compact('user'));
    }
}
