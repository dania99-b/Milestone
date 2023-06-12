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

    public function __construct()
    {
     
    }

    public function broadcastOn()
    {
        return new Channel('notification');
    }

    public function broadcastAs()
    {
        return 'notification-received' ;
    }
     public function broadcastToEveryone()
    {
        return true;
    }
}

