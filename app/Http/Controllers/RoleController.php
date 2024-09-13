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

            $description = $role->description ?? 'No description available';

            return [
                'role' => $role,
                'total_permissions' => $totalPermissions,
                'permissions' => $permissions,
                'authorities' => $authorities,
                'description' => $description,
            ];
        });

        return view('roles.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles.add', [
            'authorities' => config('permission.authorities')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:50|unique:roles,name',
                'permissions' => 'required|array'
            ]
        );

        if ($validated->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validated);
        }

        DB::beginTransaction();
        try {
            $permissions = $request->permissions;
            $authorities = Config::get('permission.authorities');

            // Buat array untuk mengelompokkan manage permission yang dipilih
            $managePermissions = [];

            // Iterasi melalui authorities untuk mencocokkan permissions yang dipilih
            foreach ($authorities as $manageName => $availablePermissions) {
                // Jika salah satu permission dalam authority dipilih, tambahkan ke $managePermissions
                if (array_intersect($permissions, $availablePermissions)) {
                    $managePermissions[] = $manageName; // Simpan manageName dengan prefix 'manage_'
                }
            }

            // Buat description berdasarkan manage permission yang dipilih
            if (count($managePermissions) > 0) {
                if (count($managePermissions) == 1) {
                    $description = 'Control ' . $managePermissions[0] . ' manage permission for ' . $request->name;
                } elseif (count($managePermissions) == 2) {
                    $description = 'Control ' . implode(' and ', $managePermissions) . ' manage permissions for ' . $request->name;
                } else {
                    $last = array_pop($managePermissions);
                    $description = 'Control ' . implode(', ', $managePermissions) . ' and ' . $last . ' manage permissions for ' . $request->name;
                }
            } else {
                // Jika tidak ada yang dipilih, default ke create cerita
                $description = 'Create story access only for ' . $request->name;
            }

            // Buat role baru dengan description
            $role = Role::create([
                'name' => $request->name,
                'description' => $description
            ]);

            // Berikan permissions pada role
            $role->givePermissionTo($permissions);

            Alert::html(
                'Data Berhasil Disimpan',
                "Data dengan nama <span style='color: green;'>{$request->name}</span> berhasil disimpan ke dalam database.",
                'success'
            );
            DB::commit();
            return redirect()->route('roles.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error(
                'Data Gagal Disimpan',
                'Data gagal disimpan',
                ['error' => $th->getMessage()]
            );
            return redirect()->back()->withInput($request->all());
        }
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Ambil role dengan ID tertentu beserta permissions-nya
        $role = Role::with('permissions')->findOrFail($id);

        // Ambil konfigurasi authorities dari config permission
        $authorities = Config::get('permission.authorities');

        // Ambil permissions dari role dalam bentuk array
        $permissions = $role->permissions->pluck('name')->toArray();

        // Hitung total permissions
        $totalPermissions = count($permissions);

        // Siapkan HTML untuk authorities dan permissions
        $permissionsHtml = '';

        foreach ($authorities as $category => $perms) {
            // Cek apakah role memiliki permission dalam kategori ini
            $hasPermissions = collect($perms)->intersect($permissions)->isNotEmpty();
            if ($hasPermissions) {
                $permissionsHtml .= '<div class="info-item">';
                $permissionsHtml .= '<span class="pcard-title-txt" style="background-color: black; color: white; padding: 5px; margin-left:5px; display: inline-block;">' . ucfirst(str_replace('_', ' ', $category)) . '</span>';
                $permissionsHtml .= '<ul class="list-group" style="padding-left:5px; padding-top:5px;">';
                foreach ($perms as $perm) {
                    if (in_array($perm, $permissions)) {
                        $permissionsHtml .= '<li class="list-group-item">' . ucfirst(str_replace('_', ' ', $perm)) . '</li>';
                    }
                }
                $permissionsHtml .= '</ul>';
                $permissionsHtml .= '</div>';
            }
        }

        // Kembalikan data sebagai JSON, termasuk permissionsHtml yang sudah di-generate
        return response()->json([
            'name' => $role->name,
            'total_permissions' => $totalPermissions,
            'permissionsHtml' => $permissionsHtml, // HTML untuk permissions
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('roles.edit', [
            'role' => $role,
            'authorities' => config('permission.authorities'),
            'permissionChecked' => $role->permissions->pluck('name')->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'name' => "required|string|max:50|unique:roles,name," . $role->id,
                'permissions' => "required"

            ]
        );

        if ($validated->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validated);
        }

        DB::beginTransaction();
        try {
            //code...
            $role->name = $request->name;
            $role->syncPermissions($request->permissions);
            $role->save();
            Alert::html(
                'Data Berhasil DiUpdate',
                "Data dengan nama <span style='color: green;'>{$request->name}</span> berhasil diUpdate.",
                'success'
            );
            return redirect()->route('roles.index');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            alert::error(
                'Data Gagal DiUpdate',
                'Data gagal di Update',
                ['error' => $th->getMessage()]
            );
            return redirect()->back()->withInput($request->all());
        } finally {
            DB::commit();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        DB::beginTransaction();
        try {
            //code...
            $role->revokePermissionTo($role->permissions->pluck('name')->toArray());
            $role->delete();
            Alert::html(
                'Delete',
                'Data Berhasil DIhapus',
                'success'
            );
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            alert::error(
                'Data Gagal DiUpdate',
                'Data gagal di Update',
                ['error' => $th->getMessage()]
            );
        } finally {
            DB::commit();
        }
        return redirect()->route('roles.index');
    }
}
