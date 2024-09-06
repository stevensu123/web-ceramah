<?php

namespace App\Http\Controllers;

// use App\Models\Role;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Config;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authorities = Config::get('permission.authorities');

        // Ambil semua role
        $roles = Role::with('permissions')->get();
    
        // Siapkan data untuk ditampilkan
        $data = $roles->map(function ($role) use ($authorities) {
            $permissions = $role->permissions->pluck('name')->toArray();
            $totalPermissions = count($permissions);
    
            return [
                'role' => $role,
                'total_permissions' => $totalPermissions,
                'permissions' => $permissions,
                'authorities' => $authorities
            ];
        });
    
        return view('roles.index', compact('data'));
    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //
    }
}
