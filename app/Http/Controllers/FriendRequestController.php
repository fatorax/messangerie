<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FriendRequest;
use App\Models\User;
use App\Models\Conversation;
use App\Events\ConversationCreate;
use App\Events\FriendRequestAdded;
use App\Events\FriendRequestRemoved;
use Illuminate\Support\Facades\Log;

class FriendRequestController extends Controller
{
    // Envoyer une demande d'ami
    public function send(Request $request)
    {
        $validated = $request->validate([
            'search' => 'required|string|max:255',
        ]);

        $senderId = Auth::id();
        
        // Chercher l'utilisateur par username ou email
        $receiver = User::where('username', $validated['search'])
            ->orWhere('email', $validated['search'])
            ->first();

        if (!$receiver) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur introuvable',
            ], 404);
        }

        // Vérifier que l'utilisateur ne s'ajoute pas lui-même
        if ($receiver->id === $senderId) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de vous ajouter vous-même',
            ], 422);
        }

        $receiverId = $receiver->id;

        // Vérifier qu'une demande n'existe pas déjà
        $existingRequest = FriendRequest::where(function($query) use ($senderId, $receiverId) {
            $query->where('sender_id', $senderId)
                  ->where('receiver_id', $receiverId);
        })->orWhere(function($query) use ($senderId, $receiverId) {
            $query->where('sender_id', $receiverId)
                  ->where('receiver_id', $senderId);
        })->first();

        if ($existingRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Une demande d\'ami existe déjà avec cet utilisateur',
            ], 409);
        }

        // Créer la demande
        $friendRequest = FriendRequest::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'status' => 'pending',
        ]);

        // Diffuser l'événement
        event(new FriendRequestAdded($friendRequest, [$receiverId]));

        return response()->json([
            'success' => true,
            'message' => 'Demande d\'ami envoyée',
            'friendRequest' => $friendRequest,
        ]);
    }

    // Accepter une demande d'ami
    public function accept(Request $request)
    {
        $validated = $request->validate([
            'request_id' => 'required|integer|exists:friend_requests,id',
        ]);

        $friendRequest = FriendRequest::findOrFail($validated['request_id']);
        $currentUserId = Auth::id();

        // Vérifier que l'utilisateur courant est le destinataire
        if ($friendRequest->receiver_id !== $currentUserId) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à accepter cette demande',
            ], 403);
        }

        // Créer le channel privé automatiquement
        $sender = User::find($friendRequest->sender_id);
        $receiver = User::find($friendRequest->receiver_id);

        $channel = Conversation::create([
            'name' => 'Channel ' . $sender->username . ' avec ' . $receiver->username,
            'type' => 'private',
            'created_by' => $currentUserId,
            'is_encrypted' => false,
        ]);

        $channel->users()->attach([
            $sender->id => ['role' => 'member'],
            $receiver->id => ['role' => 'member'],
        ]);

        // Accepter la demande
        $friendRequest->update([
            'status' => 'accepted',
            'conversation_id' => $channel->id,
        ]);

        // Diffuser l'événement aux deux utilisateurs
        $users = [$sender, $receiver];
        event(new ConversationCreate($channel, $users));
        event(new FriendRequestRemoved($friendRequest, [$receiver->id]));

        return response()->json([
            'success' => true,
            'message' => 'Demande d\'ami acceptée',
            'channel' => $channel,
        ]);
    }

    // Rejeter une demande d'ami
    public function reject(Request $request)
    {
        $validated = $request->validate([
            'request_id' => 'required|integer|exists:friend_requests,id',
        ]);

        $friendRequest = FriendRequest::findOrFail($validated['request_id']);
        $currentUserId = Auth::id();

        // Vérifier que l'utilisateur courant est le destinataire
        if ($friendRequest->receiver_id !== $currentUserId) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à rejeter cette demande',
            ], 403);
        }

        // Rejeter la demande
        $friendRequest->delete();

        event(new FriendRequestRemoved($friendRequest, [$friendRequest->receiver_id]));

        return response()->json([
            'success' => true,
            'message' => 'Demande d\'ami rejetée',
        ]);
    }

    // Récupérer les demandes d'ami en attente
    public function pendingRequests()
    {
        $currentUserId = Auth::id();

        $sent = FriendRequest::where('sender_id', $currentUserId)
            ->where('status', 'pending')
            ->with('receiver')
            ->get();

        $received = FriendRequest::where('receiver_id', $currentUserId)
            ->where('status', 'pending')
            ->with('sender')
            ->get();

        return response()->json([
            'success' => true,
            'sent' => $sent,
            'received' => $received,
        ]);
    }

    // Annuler une demande d'ami
    public function cancel(Request $request)
    {
        $validated = $request->validate([
            'request_id' => 'required|integer|exists:friend_requests,id',
        ]);

        $friendRequest = FriendRequest::findOrFail($validated['request_id']);
        $currentUserId = Auth::id();

        // Vérifier que l'utilisateur courant est l'expéditeur
        if ($friendRequest->sender_id !== $currentUserId) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à annuler cette demande',
            ], 403);
        }

        $friendRequest->delete();

        event(new FriendRequestRemoved($friendRequest, [$friendRequest->receiver_id]));

        return response()->json([
            'success' => true,
            'message' => 'Demande d\'ami annulée',
        ]);
    }
}
