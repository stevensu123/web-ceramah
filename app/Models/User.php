<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'no_hp',
        'username',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'username_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function ceritas()
    {
        return $this->hasMany(Cerita::class);
    }

    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    public function isAdmin()
    {
        return $this->role === 'admin' || $this->isSuperAdmin();
    }

    protected static function booted()
    {
        static::deleting(function ($user) {
            Log::info('User is being deleted: ' . $user->id);
    
            // Hapus notifikasi yang terkait dengan user ini
            $deletedUserNotifications = DB::table('notifications')
                ->where('notifiable_id', $user->id)
                ->where('notifiable_type', User::class)
                ->delete();
    
            Log::info('Jumlah notifikasi untuk user ' . $user->id . ' yang dihapus: ' . $deletedUserNotifications);
    
            // Hapus notifikasi terkait role jika diperlukan
            $roles = Role::whereIn('name', ['superadmin', 'admin'])->get();
            
            foreach ($roles as $role) {
                $deletedRoleNotifications = DB::table('notifications')
                    ->where('notifiable_id', $role->id)
                    ->where('notifiable_type', Role::class)
                    ->delete();
                
                Log::info('Jumlah notifikasi untuk role ' . $role->name . ' yang dihapus: ' . $deletedRoleNotifications);
            }
        });
    }


    public function notifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')->orderBy('created_at', 'desc');
    }

    public function unreadNotifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')->whereNull('read_at');
    }


}
