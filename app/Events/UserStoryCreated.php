<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserStoryCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $story;
    /**
     * Create a new event instance.
     */
    public function __construct(User $user, $story)
    {
        $this->user = $user;
        $this->story = $story;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new Channel('admin-channel');
    }

    public function broadcastWith()
    {
        $waktu = [];

        if ($this->story->waktu_pagi) {
            $waktu[] = 'pagi';
        }
        if ($this->story->waktu_siang) {
            $waktu[] = 'siang';
        }
        if ($this->story->waktu_sore) {
            $waktu[] = 'sore';
        }

        // Gabungkan waktu yang ada menjadi string, misalnya "pagi, siang"
        $waktuCerita = implode(', ', $waktu);
        return [
            'message' => 'User ' . $this->user->name . ' created a new story: ' . $waktuCerita,
            'user_id' => $this->user->id,
            'story_id' => $this->story->id,
        ];
    }
}
