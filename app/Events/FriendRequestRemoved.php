<?php

namespace App\Events;

use App\Models\FriendRequest;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;

class FriendRequestRemoved implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $friendRequest;
    public $userIds;

    public function __construct(FriendRequest $friendRequest, $userIds = [])
    {
        $this->friendRequest = $friendRequest;
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
    }

    public function broadcastAs()
    {
        return 'friendRequest.removed';
    }
}
