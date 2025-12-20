<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ConversationDeleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $conversationId;

    public function __construct($conversationId)
    {
        $this->conversationId = $conversationId;
    }

    public function broadcastOn()
    {
        // Diffuse sur le channel privé de la conversation (chat.{conversationId})
        return new PrivateChannel('conversation.deleted.' . $this->conversationId);
    }

    public function broadcastAs()
    {
        return 'conversation.deleted';
    }
}
