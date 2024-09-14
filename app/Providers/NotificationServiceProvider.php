<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
                // Pastikan hanya mengambil 5 notifikasi terbaru
                $notifications = $user->unreadNotifications()
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
                
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
