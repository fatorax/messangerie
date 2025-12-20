<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Conversation;
use App\Models\User;
use App\Events\ConversationDeleted;

class ChannelController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $channelsPublic = Conversation::where('type', 'global')->get();
        $conversationView = Conversation::where('id', 1)->first();
        $messages = $conversationView->messages()
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->reverse();
        if ($conversationView->type == 'global') {
            $totalMembers = User::count();
        }else{
            $totalMembers = $conversationView->users->count();
        }
        $channelsPrivate = $user->conversations()
            ->where('type', 'private')
            ->whereHas('users', function ($query) {
                $query->selectRaw('count(users.id)')
                    ->havingRaw('count(users.id) = 2');
            })
            ->with(['users' => function ($query) use ($user) {
                $query->where('users.id', '!=', $user->id);
            }])
            ->get();
        return view('chat.dashboard', compact('user', 'channelsPublic', 'conversationView', 'messages', 'totalMembers', 'channelsPrivate'));
    }

    public function viewChannel(int $id)
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
            ->whereHas('users', function ($query) {
                $query->selectRaw('count(users.id)')
                    ->havingRaw('count(users.id) = 2');
            })
            ->with(['users' => function ($query) use ($user) {
                $query->where('users.id', '!=', $user->id);
            }])
            ->get();
        return view('chat.dashboard', compact('user', 'channelsPublic', 'conversationView', 'messages', 'totalMembers', 'channelsPrivate'));
    }

    public function addchannels(Request $request)
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

        return response()->json([
            'success' => true,
            'channel' => $channel,
        ]);
    }

    // public function editchannels(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //     ]);

    //     $channel = Conversation::create([
    //         'name' => $validated['name'],
    //         'type' => 'global',
    //         'created_by' => Auth::id(),
    //         'is_encrypted' => false,
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'channel' => $channel,
    //     ]);
    // }

    public function deletechannels(Request $request)
    {
        $id = $request->input('id');
        $conversation = Conversation::findOrFail($id);

        $conversation->delete();

        broadcast(new ConversationDeleted($id))->toOthers();

        return response()->json(['success' => true]);
    }

    public function searchUserAdd(Request $request)
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

        return response()->json([
            'success' => true,
            'channel' => $channel,
            'user' => $user,
        ]);
    }

    
}
