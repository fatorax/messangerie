<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('global', function ($user) {
    return true; // tout le monde peut écouter
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