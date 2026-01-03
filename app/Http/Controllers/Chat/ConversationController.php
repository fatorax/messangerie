<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Events\ConversationCreate;
use App\Events\ConversationDeleted;
use App\Events\ConversationUpdate;
use App\Models\Conversation;
use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    // Affiche le dashboard des conversations
    public function index()
    {
        $user = Auth::user();

        // Rediriger les comptes de test vers leur channel de test
        if ($user->role === 'demo') {
            $testConversation = $user->conversations()
                ->where('type', 'private')
                ->first();
            
            if ($testConversation) {
                return redirect()->route('channel.view', $testConversation->id);
            } else {
                auth()->logout();
                return redirect('/login')->with('error', 'Une erreur est survenue avec votre compte de test. Veuillez contacter l\'administrateur.');
            }
        }

        $channelsPublic = Conversation::where('type', 'global')->get();
        
        // Récupérer la première conversation globale ou rediriger si aucune n'existe
        $conversationView = $channelsPublic->first();
        
        if (!$conversationView) {
            // Créer une conversation globale par défaut si elle n'existe pas
            $conversationView = Conversation::create([
                'name' => 'Général',
                'type' => 'global',
                'created_by' => $user->id,
            ]);
            $channelsPublic = Conversation::where('type', 'global')->get();
        }
        
        $messages = $conversationView->messages()
            ->with('readers')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->reverse();

        // Marquer tous les messages de la conversation comme lus
        $this->markMessagesAsRead($conversationView, $user->id);
        if ($conversationView->type == 'global') {
            $totalMembers = User::count();
        } else {
            $totalMembers = $conversationView->users->count();
        }
        $channelsPrivate = $user->conversations()
            ->where('type', 'private')
            ->whereRaw('(SELECT COUNT(DISTINCT user_id) FROM conversation_user WHERE conversation_user.conversation_id = conversations.id) = 2')
            ->with(['users' => function ($query) use ($user) {
                $query->where('users.id', '!=', $user->id);
            }])
            ->get();

        // Compter les messages non lus par conversation
        $unreadCounts = $this->getUnreadCounts($user->id, $channelsPublic, $channelsPrivate);

        return view('chat.dashboard', compact('user', 'channelsPublic', 'conversationView', 'messages', 'totalMembers', 'channelsPrivate', 'unreadCounts'));
    }

    // Affiche une conversation spécifique
    public function show(int $id)
    {
        $user = Auth::user();
        $conversationView = Conversation::find($id);

        if (!$conversationView) {
            return redirect('/channels')->with('error', "Ce channel n'existe pas ou a été supprimé.");
        }

        if ($conversationView->type == 'private' && !$conversationView->users->contains($user->id)) {
            return redirect('/channels')->with('error', "Vous n'avez pas accès à ce channel.");
        }

        $channelsPublic = Conversation::where('type', 'global')->get();
        $messages = $conversationView->messages()
            ->with('readers')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->reverse();
        if ($conversationView->type == 'global') {
            $totalMembers = User::count();
        } else {
            $totalMembers = $conversationView->users->count();
        }
        $channelsPrivate = $user->conversations()
            ->where('type', 'private')
            ->whereRaw('(SELECT COUNT(DISTINCT user_id) FROM conversation_user WHERE conversation_user.conversation_id = conversations.id) = 2')
            ->with(['users' => function ($query) use ($user) {
                $query->where('users.id', '!=', $user->id);
            }])
            ->get();

        // Marquer tous les messages de la conversation comme lus
        $this->markMessagesAsRead($conversationView, $user->id);

        // Compter les messages non lus par conversation
        $unreadCounts = $this->getUnreadCounts($user->id, $channelsPublic, $channelsPrivate);

        return view('chat.dashboard', compact('user', 'channelsPublic', 'conversationView', 'messages', 'totalMembers', 'channelsPrivate', 'unreadCounts'));
    }

    // Crée une nouvelle conversation
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $channel = Conversation::create([
            'name' => $validated['name'],
            'type' => 'global',
            'created_by' => Auth::id(),
            'is_encrypted' => false,
        ]);

        event(new ConversationCreate($channel, []));

        return response()->json([
            'success' => true,
            'channel' => $channel,
        ]);
    }

    // Met à jour une conversation
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:conversations,id',
            'name' => 'required|string|max:255',
        ]);

        $channel = Conversation::findOrFail($validated['id']);
        $channel->update(['name' => $validated['name']]);

        event(new ConversationUpdate($channel));

        return response()->json([
            'success' => true,
            'channel' => $channel,
        ]);
    }

    // Supprime une conversation
    public function destroy(Request $request)
    {
        $id = $request->input('id');
        $conversation = Conversation::findOrFail($id);

        $friendRequest = FriendRequest::where('conversation_id', $id)->first();
        if ($friendRequest) {
            $friendRequest->delete();
        }

        // Récupère les IDs des membres de la conversation
        $userIds = $conversation->users()->pluck('users.id')->toArray();

        $conversation->delete();

        // Envoyer à tout le monde si channel global, sinon seulement aux membres
        if ($conversation->type === 'global') {
            broadcast(new ConversationDeleted($id, []));
        } else {
            broadcast(new ConversationDeleted($id, $userIds));
        }

        return response()->json(['success' => true]);
    }

    // Recherche un utilisateur pour l'ajouter
    public function searchUser(Request $request)
    {
        $validated = $request->validate([
            'search' => 'required|string|max:255',
        ]);

        if (Auth::user()->username == $validated['search'] || Auth::user()->email == $validated['search']) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de rechercher un utilisateur',
            ]);
        }

        $user = User::where('username', $validated['search'])
            ->orWhere('email', $validated['search'])
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur introuvable',
            ]);
        }

        $channel = Conversation::create([
            'name' => 'Channel ' . Auth::user()->username . ' avec ' . $user->username,
            'type' => 'private',
            'created_by' => Auth::id(),
            'is_encrypted' => false,
        ]);

        $channel->users()->attach([
            Auth::id() => ['role' => 'admin'],
            $user->id => ['role' => 'member'],
        ]);

        // Diffuse uniquement aux membres du channel privé
        $users = [$user, Auth::user()];
        event(new ConversationCreate($channel, $users));

        return response()->json([
            'success' => true,
            'channel' => $channel,
            'user' => $user,
        ]);
    }

    // Marque tous les messages non lus d'une conversation comme lus par l'utilisateur
    private function markMessagesAsRead(Conversation $conversation, int $userId): void
    {
        $unreadMessages = $conversation->messages()
            ->where('user_id', '!=', $userId)
            ->whereDoesntHave('readers', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();

        foreach ($unreadMessages as $message) {
            $message->markAsReadBy($userId);
        }
    }

    // Compte les messages non lus par conversation
    private function getUnreadCounts(int $userId, $channelsPublic, $channelsPrivate): array
    {
        $unreadCounts = [];

        foreach ($channelsPublic as $channel) {
            $unreadCounts[$channel->id] = $channel->messages()
                ->where('user_id', '!=', $userId)
                ->whereDoesntHave('readers', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->count();
        }

        foreach ($channelsPrivate as $channel) {
            $unreadCounts[$channel->id] = $channel->messages()
                ->where('user_id', '!=', $userId)
                ->whereDoesntHave('readers', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->count();
        }

        return $unreadCounts;
    }
}
