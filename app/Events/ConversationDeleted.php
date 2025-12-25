<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ConversationDeleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $conversationId;
    public $userIds;

    public function __construct($conversationId, $userIds = [])
    {
        $this->conversationId = $conversationId;
        $this->userIds = $userIds;
    }

    public function broadcastOn()
    {
        // Diffuse sur le channel privé de chaque utilisateur membre
        if (is_array($this->userIds) && count($this->userIds)) {
            return collect($this->userIds)->map(function($id) {
                return new PrivateChannel('user.' . $id);
            })->all();
        }
        // Si aucun utilisateur n'est spécifié, diffuser sur le channel public
        return [new Channel('conversations')]; // fallback
    }

    public function broadcastAs()
    {
        return 'conversation.deleted';
    }
}
