<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserRegistered implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        Log::info('UserRegistered event triggered for user ID: ' . $user->id);
    }

    public function broadcastOn()
    {
        return new Channel('admin-channel');
    }

    public function broadcastWith()
    {
        return [
            'message' => 'New user registered: ' . $this->user->name,
            'user_id' => $this->user->id,
        ];
    }
}


