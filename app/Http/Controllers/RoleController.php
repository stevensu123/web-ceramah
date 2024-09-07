<?php

namespace App\Http\Controllers;

// use App\Models\Role;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

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
        return view('roles.add',[
            'authorities' => config('permission.authorities')    
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = Validator::make( 
            $request->all(),
            [
            'name' => 'required|string|max:50|unique:roles,name',
            'permissions' => 'required'
            
        ]);

        if($validated->fails()){
            return redirect()->back()->withInput($request->all())->withErrors($validated);
        }

       DB::beginTransaction();
       try {
        //code...
        $role = Role::create([
            'name' => $request->name
        ]);
        $role->givePermissionTo($request->permissions);
        Alert::html(
            'Data Berhasil Disimpan', 
            "Data dengan nama <span style='color: green;'>{$request->name}</span> berhasil disimpan ke dalam database.", 
            'success'
        );
        return redirect()->route('roles.index');
       } catch (\Throwable $th) {
        //throw $th;
        DB::rollBack();
        alert::error(
            'Data Gagal Disimpan',
            'Data gagal di simpan',['error'=>$th->getMessage()]
        );
    return redirect()->back()->withInput($request->all());
       }finally {
        DB::commit();
       }
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {

         // Ambil role dengan ID tertentu beserta permissions-nya
    $role = Role::with('permissions')->findOrFail($id);

    // Ambil konfigurasi authorities
    $authorities = Config::get('permission.authorities');

    // Ambil permissions dari role
    $permissions = $role->permissions->pluck('name')->toArray();

    // Hitung total permissions
    $totalPermissions = count($permissions);

    // Siapkan data untuk ditampilkan
    $data = [
        'role' => $role,
        'total_permissions' => $totalPermissions,
        'permissions' => $permissions,
        'authorities' => $authorities
    ];

    // Kembalikan data sebagai JSON
    return response()->json($data);
    
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
