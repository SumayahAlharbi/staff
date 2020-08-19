<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mail;
use Auth;
use App\Mail\InviteCreated;
use Illuminate\Validation\Rule;
use App\Invite;
use App\User;
use App\Group;


class InviteController extends Controller
{
  public function invite()
{
    // show the user a form with an email field to invite a new user
    $users = User::all();
    if (Auth::user()->hasRole('admin')) {
        $groups = Group::all();
      }else {
        $groups = Auth::user()->group;
      }

     return view('invite', compact('users', 'groups'));
}

public function process(Request $request)
{

  if (Auth::user()->hasRole('admin')) {
      $userGroups = Group::all();
    }else {
      $userGroups = Auth::user()->group;
    }

  foreach ($userGroups as $userGroup) {
    $userGroupIDs[] =  $userGroup->id;
  };

  $request->validate([
    'email' => 'required',
  'group_id' => [
    'required',
    Rule::in($userGroupIDs),
  ],
]);
    // process the form submission and send the invite by email

    // validate the incoming request data

    do {
        //generate a random string using Laravel's str_random helper
        $token = Str::random();
    } //check if the token already exists and if it does, try again
    while (Invite::where('token', $token)->first());

    //create a new invite record
    $invite = Invite::create([
        'email' => $request->get('email'),
        'group_id' => $request->get('group_id'),
        'token' => $token
    ]);

    // send the email
    Mail::to($request->get('email'))->send(new InviteCreated($invite));

    // redirect back where we came from
    return redirect('/invite')->with('success', 'User has been invited');
}

public function accept($token)
{
    // here we'll look up the user by the token sent provided in the URL

    // Look up the invite
    if (!$invite = Invite::where('token', $token)->first()) {
        //if the invite doesn't exist do something more graceful than this
        abort(404);
    }

    // find the user with the details from the invite
  $userFinder = User::where('email', $invite->email)->first();
  $userFinder->group()->syncWithoutDetaching($invite->group_id);


    // delete the invite so it can't be used again
    $invite->delete();

    // here you would probably log the user in and show them the dashboard, but we'll just prove it worked

    return redirect('/home')->with('success', 'You have been Joined');
}

}
