<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Models\TestAccount;
use App\Models\FriendRequest;
use App\Mail\DemoAccountDeleted;
use App\Mail\AccountDeleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    // Dashboard admin
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'channels' => Conversation::where('type', 'global')->count(),
            'conversations' => Conversation::where('type', 'private')->count(),
            'messages' => Message::count(),
        ];

        $recentUsers = User::orderBy('created_at', 'desc')->limit(5)->get();
        $recentMessages = Message::with('user', 'conversation')->orderBy('created_at', 'desc')->limit(5)->get();

        return view('admin.index', compact('stats', 'recentUsers', 'recentMessages'));
    }

    // Liste des utilisateurs
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users', compact('users'));
    }

    // Modifier un utilisateur
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:user,admin,demo',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users')->with('success', 'Utilisateur modifié avec succès.');
    }

    // Supprimer un utilisateur
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // Vérifier si c'est un compte démo
        $testAccount = TestAccount::where('user1_id', $user->id)
            ->orWhere('user2_id', $user->id)
            ->first();

        if ($testAccount) {
            // Supprimer l'autre utilisateur du compte démo
            $otherUser = $testAccount->user1_id == $user->id ? $testAccount->user2 : $testAccount->user1;
            
            if ($otherUser) {
                // Supprimer conversations et messages de l'autre utilisateur
                $otherUser->conversations()->delete();
                $otherUser->messages()->delete();
                
                if ($otherUser->avatar !== 'default.webp') {
                    Storage::disk('public')->delete('users/' . $otherUser->avatar);
                }
                $otherUser->delete();
            }

            // Envoyer l'email de notification
            if ($testAccount->requester_email) {
                Mail::to($testAccount->requester_email)->send(new DemoAccountDeleted(
                    $testAccount->username1,
                    $testAccount->username2
                ));
            }

            // Supprimer le TestAccount
            $testAccount->delete();
        } else {
            // Compte normal : supprimer les conversations privées et leurs messages
            $privateConversations = $user->conversations()->where('type', 'private')->get();
            foreach ($privateConversations as $conversation) {
                // Supprimer tous les messages de cette conversation
                $conversation->messages()->delete();
                // Détacher tous les utilisateurs de la conversation
                $conversation->users()->detach();
                // Supprimer la conversation
                $conversation->delete();
            }

            // Supprimer les demandes d'ami (envoyées et reçues)
            FriendRequest::where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id)
                ->delete();

            // Supprimer les messages dans les channels (global)
            $user->messages()->delete();

            // Détacher l'utilisateur des channels
            $user->conversations()->where('type', 'global')->detach();

            // Supprimer les lectures de messages
            $user->readMessages()->detach();

            // Envoyer l'email de confirmation de suppression
            Mail::to($user->email)->send(new AccountDeleted($user->username, true));
        }
        
        if ($user->avatar !== 'default.webp') {
            Storage::disk('public')->delete('users/' . $user->avatar);
        }
        
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé avec succès.');
    }

    // Liste des channels
    public function channels()
    {
        $channels = Conversation::where('type', 'global')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.channels', compact('channels'));
    }

    // Liste des conversations privées
    public function conversations()
    {
        $conversations = Conversation::where('type', 'private')->with('users')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.conversations', compact('conversations'));
    }

    // Modifier un channel
    public function updateChannel(Request $request, $id)
    {
        $channel = Conversation::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'delete_image' => 'nullable|string',
        ]);

        // Supprimer l'image si demandé
        if ($request->input('delete_image') === '1') {
            if ($channel->image) {
                Storage::disk('public')->delete('channels/' . $channel->image);
            }
            $validated['image'] = null;
        }
        // Upload de l'image si présente
        elseif ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($channel->image) {
                Storage::disk('public')->delete('channels/' . $channel->image);
            }
            
            $file = $request->file('image');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->put('channels/' . $filename, file_get_contents($file));
            $validated['image'] = $filename;
        } else {
            unset($validated['image']);
        }

        unset($validated['delete_image']);
        $channel->update($validated);

        return redirect()->route('admin.channels')->with('success', 'Channel modifié avec succès.');
    }

    // Supprimer un channel
    public function deleteChannel($id)
    {
        $channel = Conversation::findOrFail($id);
        
        if ($channel->image) {
            Storage::disk('public')->delete('channels/' . $channel->image);
        }
        
        $channel->delete();

        return redirect()->route('admin.channels')->with('success', 'Channel supprimé avec succès.');
    }

    // Liste des messages
    public function messages()
    {
        $messages = Message::with('user', 'conversation')->orderBy('created_at', 'desc')->paginate(50);
        return view('admin.messages', compact('messages'));
    }

    // Messages d'une conversation
    public function conversationMessages($id)
    {
        $conversation = Conversation::with(['messages.user', 'users'])->findOrFail($id);
        $messages = $conversation->messages()->with('user')->orderBy('created_at', 'desc')->paginate(50);
        return view('admin.conversation-messages', compact('conversation', 'messages'));
    }

    // Supprimer un message
    public function deleteMessage($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.messages')->with('success', 'Message supprimé avec succès.');
    }
}
