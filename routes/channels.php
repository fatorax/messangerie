<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use App\Models\Conversation;

Broadcast::channel('global', function ($user) {
    return true;
});

// Channel privé pour une conversation : accessible seulement aux membres
Broadcast::channel('private-conversation.{conversationId}', function ($user, $conversationId) {
    // Debug: log attempts to authorize a private conversation channel
    try {
        $userId = $user?->id ?? null;
        $isMember = false;
        if ($user) {
            $isMember = (bool) $user->conversations()->where('conversations.id', $conversationId)->exists();
        }
        Log::info('Broadcast auth attempt', ['user_id' => $userId, 'conversation_id' => $conversationId, 'is_member' => $isMember]);
    } catch (\Throwable $e) {
        Log::error('Broadcast auth check failed', ['error' => $e->getMessage(), 'conversation_id' => $conversationId]);
        return false;
    }

    if (! $user) return false;
    return $isMember;
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