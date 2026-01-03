<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Events\MessageDeleted;
use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Envoie un nouveau message
    public function store(Request $request)
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

    // Supprime un message
    public function destroy(Request $request)
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
