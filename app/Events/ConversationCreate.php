<?php

namespace App\Events;

use App\Models\Conversation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ConversationCreate implements ShouldBroadcastNow
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
        return [];
    }

    public function broadcastAs()
    {
        return 'conversation.add';
    }
}
