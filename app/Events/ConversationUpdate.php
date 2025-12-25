<?php

namespace App\Events;

use App\Models\Conversation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ConversationUpdate implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $conversation;
    public $users;

    /**
     * @param Conversation $conversation
     * @param User[] $users
     */
    public function __construct(Conversation $conversation, array $users = [])
    {
        $this->conversation = $conversation;
        $this->users = $users;
    }

    public function broadcastOn()
    {
        if (is_array($this->users) && count($this->users)) {
            return array_map(function($user) {
                return new PrivateChannel('user.' . (is_object($user) ? $user->id : $user));
            }, $this->users);
        }
        // Si aucun utilisateur n'est spécifié, diffuser sur le channel public
        return [new Channel('conversations')]; // fallback
    }

    public function broadcastAs()
    {
        return 'conversation.update';
    }
}
