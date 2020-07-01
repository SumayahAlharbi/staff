<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::get();

        return view('permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Permission::create(['name' => $request->name]);

        return redirect('permissions');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $permissions = Permission::find($id);
      return view('permissions.edit', compact('permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $permissions = Permission::find($id);
      $permissions->name = $request->name;
      $permissions->save();

      return redirect('/permissions')->with('success', 'permission has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permissions = Permission::findOrFail($id);

        $permissions->delete();

        return redirect('permissions');
    }

    /**
     * Assign Permission to a Permission.
     *
     * @param \Illuminate\Http\Request
     *
     * @return \Illuminate\Http\Response
     */
    public function addPermission(Request $request)
    {
        $permission = Permission::findOrFail($request->permission_id);
        $permission->givePermissionTo($request->permission_name);

        return redirect('permissions/edit/'.$request->permission_id);
    }

        /**
     * revoke Permission to a user.
     *
     * @param \Illuminate\Http\Request
     *
     * @return \Illuminate\Http\Response
     */
    public function revokePermission($permission, $permission_id)
    {
        $permission = Permission::findorfail($permission_id);

        $permission->revokePermissionTo(str_slug($permission, ' '));

        return redirect('permissions/edit/'.$permission_id);
    }

}
