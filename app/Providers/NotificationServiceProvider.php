<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                
                // Ambil semua notifikasi pengguna dan urutkan berdasarkan waktu terbaru
                $notifications = $user->notifications->sortByDesc('created_at')->take(5);
                
                // Hitung jumlah notifikasi yang belum dibaca
                $notificationCount = $user->unreadNotifications->count();
        
                // Kirimkan data notifikasi ke view
                $view->with('notifications', $notifications);
                $view->with('notificationCount', $notificationCount);
            } else {
                // Jika tidak ada pengguna yang login, kirimkan variabel kosong
                $view->with('notifications', collect());
                $view->with('notificationCount', 0);
            }
        });
        
    }
    
    
}
