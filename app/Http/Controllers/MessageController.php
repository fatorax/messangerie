<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use App\Events\MessageDeleted;
use App\Events\MessageRead;

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

        // Marquer tous les messages de l'autre utilisateur comme lus
        $conversation = Conversation::find($request->conversation_id);
        $unreadMessages = $conversation->messages()
            ->where('user_id', '!=', Auth::id())
            ->whereDoesntHave('readers', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->get();

        foreach ($unreadMessages as $unreadMessage) {
            $unreadMessage->markAsReadBy(Auth::id());
        }

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
