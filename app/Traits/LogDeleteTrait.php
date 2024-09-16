<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait LogDeleteTrait
{
    /**
     * Log and delete the model.
     *
     * @return bool|null
     */
    public function logAndDelete()
    {
        $roles = $this->getRoleNames();
        $status = $this->status;
        // Simpan data yang dihapus ke dalam file log
        Storage::append('deleted_users.log', json_encode([
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'roles' => $roles,
            'status' => $status, // Mendapatkan role user
            'deleted_at' => now(),
        ]));
        
        // Hapus data dari database
        return $this->delete();
    }
}
