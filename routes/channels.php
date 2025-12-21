<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use App\Models\Conversation;

Broadcast::channel('global', function ($user) {
    return true;
});

Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    try {
        $userId = $user?->id ?? null;
        $isMember = false;
        $conversation = Conversation::find($conversationId);
        if ($conversation->type == 'global') {
            $isMember = true;
        } else {
            if ($user) {
                $isMember = (bool) $user->conversations()->where('conversations.id', $conversationId)->exists();
                Log::info('Broadcast auth attempt', ['user_id' => $userId, 'conversation_id' => $conversationId, 'is_member' => $isMember]);
            }
        }
    } catch (\Throwable $e) {
        Log::error('Broadcast auth check failed', ['error' => $e->getMessage(), 'conversation_id' => $conversationId]);
        return false;
    }
    
    Log::info('Broadcast auth attempt', ['user_id' => $userId, 'conversation_id' => $conversationId, 'is_member' => $isMember]);
    if (! $user) return false;
    return $isMember;
});

Broadcast::channel('online', function ($user) {
    return [
        'id' => $user->id,
        'name' => $user->name,
    ];
});

// Autorisation pour le canal privé user.{id}
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});