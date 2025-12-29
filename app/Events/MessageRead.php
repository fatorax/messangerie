<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MessageRead implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $readerId;

    public function __construct(Message $message, int $readerId)
    {
        $this->message = $message;
        $this->readerId = $readerId;
    }

    public function broadcastOn()
    {
        // Notifie l'expéditeur du message que son message a été lu
        return new PrivateChannel('user.' . $this->message->user_id);
    }

    public function broadcastAs()
    {
        return 'MessageRead';
    }

    public function broadcastWith()
    {
        return [
            'message_id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'reader_id' => $this->readerId,
        ];
    }
}
