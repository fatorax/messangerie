<?php

namespace App\Events;

use App\Models\FriendRequest;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;

class FriendRequestAdded implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $friendRequest;
    public $users;

    public function __construct(FriendRequest $friendRequest, array $users = [])
    {
        $this->friendRequest = $friendRequest;
        $this->users = $users;
    }

    public function broadcastOn()
    {
        // Diffuse sur le channel privé de chaque utilisateur membre
        if (is_array($this->users) && count($this->users)) {
            return collect($this->users)->map(function($id) {
                return new PrivateChannel('user.' . $id);
            })->all();
        }
        return [];
    }

    public function broadcastAs()
    {
        return 'friendRequest.added';
    }
}
