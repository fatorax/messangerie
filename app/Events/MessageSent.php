<?php

namespace App\Events;

use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message->load('user', 'conversation.users');
    }

    public function broadcastOn()
    {
        $channels = [
            new PrivateChannel('chat.' . $this->message->conversation_id),
        ];

        // Récupère les utilisateurs à notifier selon le type de conversation
        if ($this->message->conversation->type === 'global') {
            // Pour les channels globaux, notifier tous les utilisateurs sauf l'expéditeur
            $users = User::where('id', '!=', $this->message->user_id)->get();
        } else {
            // Pour les conversations privées, utiliser la relation users et exclure l'expéditeur
            $users = $this->message->conversation->users->filter(function ($user) {
                return $user->id !== $this->message->user_id;
            });
        }

        // Notifie tous les utilisateurs sur leur canal privé
        foreach ($users as $user) {
            $channels[] = new PrivateChannel('user.' . $user->id);
        }

        return $channels;
    }

    public function broadcastAs()
    {
        return 'MessageSent';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'content' => $this->message->content,
            'conversation_id' => $this->message->conversation_id,
            'user_id' => $this->message->user_id,
            'user' => [
                'id' => $this->message->user->id,
                'username' => $this->message->user->username,
                'avatar' => $this->message->user->avatar,
            ],
            'created_at' => $this->message->created_at->toDateTimeString(),
        ];
    }
}
