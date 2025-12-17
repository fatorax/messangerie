<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class MessageController extends Controller
{
    public function messageSent(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|integer',
            'content' => 'required|string|max:500',
        ]);

        $message = Message::create([
            'conversation_id' => $request->conversation_id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['success' => true]);
    }
}
