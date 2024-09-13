<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class UserCreatedStoryNotification extends Notification implements ShouldQueue
{
    use Queueable;
    private $story;
    /**
     * Create a new notification instance.
     */
    public function __construct($story)
    {
        $this->story = $story;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
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
            'message' => 'You created a new story: ' .  $waktuCerita,
            'story_id' => $this->story->id,
        ];
    }

    public function toBroadcast($notifiable)
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

        $waktuCerita = implode(', ', $waktu);
        return new BroadcastMessage([
            'message' => 'You created a new story: ' . $waktuCerita,
            'story_id' => $this->story->id,
        ]);
    }
}
