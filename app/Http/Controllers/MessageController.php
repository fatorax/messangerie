<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use App\Events\MessageDeleted;

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

    public function delete(Request $request)
    {
        $request->validate([
            'message_id' => 'required|integer|exists:messages,id',
        ]);

        $message = Message::findOrFail($request->message_id);

        // Vérifier que l'utilisateur est l'auteur du message
        if ($message->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas supprimer ce message',
            ], 403);
        }

        $messageId = $message->id;
        $conversationId = $message->conversation_id;

        $message->delete();

        broadcast(new MessageDeleted($messageId, $conversationId))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'Message supprimé',
        ]);
    }
}
