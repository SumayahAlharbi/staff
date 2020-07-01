<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::orderByRaw('created_at DESC')->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
         return view('users.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      $this->validate($request, [
       'email' => 'unique:users,email|required|max:191|string|email',
       'name' => 'required|max:191|string',
       'password' => 'required|between:6,50|string',
       ]);

      $user = new \App\User();

      $user->email = $request->email;
      $user->name = $request->name;
      $user->password = Hash::make($request->password);

      $user->save();
         return redirect('/users')->with('success', 'User has been created');

    }






  public function userSearch(Request $request)
  {


    $matchingUsers = User::where('id', 'LIKE', '%' . $request->searchKey . '%')
      ->orWhere('name', 'LIKE', '%' . $request->searchKey . '%')
      ->orWhere('email', 'LIKE', '%' . $request->searchKey . '%')
      ->orWhere('name', 'LIKE', '%' . $request->searchKey . '%')->paginate(10);

    return view('users.search', compact('matchingUsers'));
  }

    /**
     * Display the specified resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
     public function edit($id)
     {
      // $user = Auth::user();
       // if ($user->hasRole('SuperAdmin')) {
         $user = \App\User::findOrfail($id);

         $roles = Role::all()->pluck('name');
         $userRoles = $user->roles;
         $permissions = Permission::all()->pluck('name');
         $userPermissions = $user->permissions;
         return view('users.edit', compact('user', 'roles', 'userRoles', 'permissions', 'userPermissions'));
       // }elseif ($user->hasRole('Admin')) {
         // $user = \App\User::whereNotIn('id', [1, 3])->findOrfail($id);
         // $roles = Role::all()->pluck('name');
         // $userRoles = $user->roles;
    //     return view('users.edit', compact('user', 'roles', 'userRoles'));
    // }else{
    //   return abort(401, 'Unauthorized action.');
    // }

     }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\users  $users
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      {

        $this->validate($request, [
            'email' => [
            'required',
            Rule::unique('users')->ignore($request->user_id),
             'email',
             'max:191',
             'string',
          ],
            'name' => 'required|max:191|string',
            'password' => 'nullable|between:6,50|string',
            ]);
       $user = \App\User::findOrfail($request->user_id);

       $user->email = $request->email;
       $user->name = $request->name;
       if(!empty($request->input('password')))
     {
         $user->password = Hash::make($request->password);
     }


       $user->save();

       //\Mail::to($user)->send(new agent);

       return redirect('/users')->with('success', 'user has been updated');
   }

    }



     /**
     * Assign Role to user.
     *
     * @param \Illuminate\Http\Request
     *
     * @return \Illuminate\Http\Response
     */
    public function addRole(Request $request)
    {
        $users = User::findOrfail($request->user_id);
        $roles = Role::all()->pluck('name');
        $userRoles = $users->roles;
        $users->assignRole($request->role_name);

        return back();
    }

    /**
     * revoke Role from a user.
     *
     * @param \Illuminate\Http\Request
     *
     * @return \Illuminate\Http\Response
     */
    public function revokeRole($role, $user_id)
    {
        $users = \App\User::findorfail($user_id);

        $users->removeRole(str_slug($role, ' '));

        return back();
    }


/**
* Assign Permission to user.
*
* @param \Illuminate\Http\Request
*
* @return \Illuminate\Http\Response
*/
public function addPermission(Request $request)
{
   $users = User::findOrfail($request->user_id);
   $permissions = Permission::all()->pluck('name');
   $userPermissions = $users->permissions;
   $users->givePermissionTo($request->permission_name);

   return back();
}

/**
* revoke Permission from a user.
*
* @param \Illuminate\Http\Request
*
* @return \Illuminate\Http\Response
*/
public function revokePermission($permission, $user_id)
{
   $users = \App\User::findorfail($user_id);

   $users->revokePermissionTo(str_slug($permission, ' '));

   return back();
}

}
