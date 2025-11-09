<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Ici tu définis les canaux privés et leur logique d'autorisation
|
*/

// Canal privé pour une conversation
Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    return Conversation::where('id', $conversationId)
        ->whereHas('users', fn($q) => $q->where('users.id', $user->id))
        ->exists();
});

// Chat global accessible à tous
Broadcast::channel('global', function ($user) {
    return true; // tout le monde peut écouter
});