<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:role-list|role-create|role-edit|role-delete', only: ['index']),
            new Middleware('permission:role-create', only: ['create', 'store']),
            new Middleware('permission:role-edit', only: ['edit', 'update']),
            new Middleware('permission:role-delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $auth_user = Auth::user();
            if ($auth_user->hasRole('superadmin')) {
                $roles = Role::get()->all();
            } else {
                $roles = Role::where('name','!=', 'superadmin')->get()->all();
            }
            return DataTables::of($roles)
                ->addIndexColumn()
                ->addColumn('action-btn', function($row) {
                    $auth_user = Auth::user();
                    if($auth_user->hasRole('superadmin')){
                        $roleMatch = [
                            'id' => $row->id,
                            'role' => $auth_user->roles->first()->name ?? null,
                        ];
                        return $roleMatch;
                    }else{
                        $roleMatch = [
                            'id' => $row->id
                        ];
                        return $roleMatch;
                    }
                })
                ->rawColumns(['action-btn'])
                ->make(true);
        }
        $permission = Permission::get();
        $modules = Permission::select('module')->distinct()->get();
        return view('admin.roles.index',compact('permission','modules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $auth_user = Auth::user();
        $permission = Permission::get();
        $modules = Permission::select('module')->distinct()->get();
        return view('admin.roles.create',compact('permission','modules'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
            'description' => 'nullable',
        ]);

        Role::create(['name' => $validated['name'], 'description' => $validated['description']]);

        $newRole = Role::findByName($validated['name']);
        foreach ($validated['permission'] as $key => $value) {
            $permissionName = Permission::findById($value);
            $newRole->givePermissionTo($permissionName->name);
        }

        return redirect()
            ->route('roles.index')
            ->with('success','Role created successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();
        return view('admin.roles.show',compact('role','rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $auth_user = Auth::user();
        $role = Role::find($id);
        if ($role->name === 'superadmin' && !$auth_user->hasRole('superadmin')) {
            return redirect()->route('role.index');
        }
        $permission = Permission::get();
        
        $modules = Permission::select('module')->distinct()->get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        return view('admin.roles.edit',compact('role','permission','rolePermissions','modules'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required',
            'permission' => 'required',
            'description' => 'nullable',
        ]);

        $role = Role::find($id);
        $role->name = $validated['name'];
        $role->description = $validated['description'];
        $role->save();

        DB::table("role_has_permissions")->where('role_id',$id)->delete();

        $newRole = Role::findByName($validated['name']);
        foreach ($validated['permission'] as $key => $value) {
            $permissionName = Permission::findById($value);
            $newRole->givePermissionTo($permissionName->name);
        }

        return redirect()->route('roles.index')
                        ->with('success','Role updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        $role = Role::find($id);
        if ($role->name === 'superadmin' && !Auth::user()->hasRole('superadmin')) {
            return redirect()->route('role.index');
        }
        $role->delete();
        return redirect()->route('roles.index')
                        ->with('success', 'Role deleted successfully');
    }

}
