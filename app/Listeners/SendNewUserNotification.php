<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\UserRegistered;
use Illuminate\Support\Facades\Log;
use App\Notifications\NewUserNotification;
use Illuminate\Support\Facades\Notification;

class SendNewUserNotification
{
    public function handle(UserRegistered $event)
    {
        $userId = $event->user->id;
        $admins = User::role(['superadmin', 'admin'])->get(); 

        foreach ($admins as $admin) {
            // Cek apakah notifikasi untuk user ini sudah ada
            $exists = $admin->notifications()
                ->where('data->user_id', $userId)
                ->exists();

            Log::info('Notification check for admin ID ' . $admin->id . ': ' . ($exists ? 'Exists' : 'Not Exists'));

            if (!$exists) {
                // Jika belum ada notifikasi, kirim notifikasi
                Notification::send($admin, new NewUserNotification($event->user));
            } else {
                // Jika sudah ada, batalkan pengiriman notifikasi
                Log::info('Duplicate notification detected for user ID ' . $userId);
            }
        }
    }
}
