<?php

namespace App\Http\Controllers;

use App\AttendanceSheet;
use App\User;
use App\Group;
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

        // if (Auth::user()->hasRole('admin')) {
          $users = User::GroupUsers()->orderByRaw('created_at DESC')->paginate(10);
        // }else{
        //   //Access the pivot table
        //   $users = User::whereHas('group', function($query) {
        //     $userGroups = Auth::user()->group;
        //     foreach ($userGroups as $userGroup) {
        //     $userGroupIDs[] =  $userGroup->id;
        //   };
        //   $query->whereIn('group_id', $userGroupIDs); })->orderByRaw('created_at DESC')->paginate(10);
        //   };


        return  view('users.index', compact('users'));
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
      ->orWhere('name', 'LIKE', '%' . $request->searchKey . '%')->GroupUsers()->paginate(10);

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
         $user = \App\User::GroupUsers()->findOrfail($id);
         $userGroups = $user->group;
         $groups = \App\Group::all();
         if (Auth::user()->hasRole('admin')) {
        $roles = Role::all()->pluck('name');
      }else {
        $roles = Role::where('name','!=','admin')->pluck('name');
      }
         $userRoles = $user->roles;
         $permissions = Permission::all()->pluck('name');
         $userPermissions = $user->permissions;
         return view('users.edit', compact('user', 'roles', 'userRoles', 'permissions', 'userPermissions','groups','userGroups'));

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
       $user = \App\User::GroupUsers()->findOrfail($request->user_id);

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


    //add and remove group to user.

    public function addUserGroup(Request $request)
    {
      $user = User::GroupUsers()->findorfail($request->user_id);
      $user->group()->syncWithoutDetaching($request->group_id);
      return back();
    }

    public function removeUserGroup($group_id, $user_id)
    {
      $user = User::GroupUsers()->findorfail($user_id);

      $user->group()->detach($group_id);

      return back();

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
if (Auth::user()->hasRole('admin')) {
  $request->validate([
    'role_name' => 'required',
  ]);
} else {
  $request->validate([
    'role_name' => [
   'required',
   Rule::in(['manager']),
    ],
  ]);
}
        $users = User::GroupUsers()->findOrfail($request->user_id);
         if (Auth::user()->hasRole('admin')) {
        $roles = Role::all()->pluck('name');
      }else {
        $roles = Role::where('name','!=','admin')->pluck('name');
      }
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
    public function revokeRole(Request $request, $role, $user_id)
    {
        $users = \App\User::GroupUsers()->findorfail($user_id);

        if (Auth::user()->hasRole('admin')) {
        $users->removeRole($role);
      } elseif ($role == 'manager') {
        $users->removeRole($role);
      } else {
        return back()->with('danger', 'Invalid Request');
      }
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
   $users = User::GroupUsers()->findOrfail($request->user_id);
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
   $users = \App\User::GroupUsers()->findorfail($user_id);

   $users->revokePermissionTo($permission);

   return back();
}

/**
 * Display the specified resource.
 *
 * @param  \App\Users  $users
 * @return \Illuminate\Http\Response
 */
public function showUserProfile($id)
{
  $user =  User::findOrfail($id);
  // show all attendance records of auth user (regardless of roles and permissions)
  $attendancesheets = AttendanceSheet::where('user_id', $id)->latest()->simplePaginate(15);

  return view('profile.index', compact('user','attendancesheets'));

}

}
