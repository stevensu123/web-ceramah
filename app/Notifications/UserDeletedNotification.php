<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class UserDeletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
  
     private $user;

     public function __construct(User $user)
     {
         $this->user = $user;
     }
 

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
      // Tentukan channel yang digunakan untuk notifikasi ini (database)
      public function via($notifiable)
      {
          return ['database', 'broadcast']; // Notifikasi akan dikirim ke database dan broadcast
      }

    /**
     * Get the mail representation of the notification.
     */
    // Format data notifikasi untuk database
    public function toArray($notifiable)
    {
        return [
            'message' => 'User with ID: ' . $this->user->name . ' has been deleted.',
           'user_id' => $this->user->id,
        ];
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'User with ID: ' . $this->user->name . ' has been deleted.',
          'user_id' => $this->user->id,
        ]);
    }
}
