<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Tymon\JWTAuth\Claims\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotificationRecieved implements ShouldBroadcast 
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
     public $data;
    public $notification;
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('notification');
    }

    public function broadcastAs()
    {
        return 'notification-received.' . $this->user->id;
    }
     public function broadcastToEveryone()
    {
        return true;
    }
}

