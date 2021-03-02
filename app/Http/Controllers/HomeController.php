<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AttendanceSheet;
use Carbon\Carbon;
use App\Group;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $fromDate = Carbon::parse(Carbon::now()->toFormattedDateString())->startOfWeek(Carbon::SUNDAY);
        $toDate = Carbon::parse(Carbon::now()->toFormattedDateString())->endOfWeek(Carbon::THURSDAY);

        $groups = Auth::user()->group;
        $user = Auth::user();
        $UserAttendance = AttendanceSheet::where('user_id', '=', $user->id)
        ->whereBetween('created_at', [$fromDate, $toDate])
        ->latest()->take(10)->get();

        return view('home',compact('user', 'UserAttendance', 'groups'));
    }

    public function dashboardIndex()
    {
        if (Auth::user()->hasRole('admin')) {
            $groups = Group::all();
          }else {
            $groups = Auth::user()->group;
          }
         $user = Auth::user();

         foreach($groups as $group){
         foreach ($group->user as $user){
           $result = AttendanceSheet::select('action')
           ->where('user_id', '=', $user->id)
           ->where('group_id', '=', $group->id)
           ->whereDate('created_at', '=', Carbon::now()->toDateString())
           ->orderBy('created_at', 'desc')->first();
           if ($result)
           $user['lastAction'] = $result;
           else
           $user['lastAction'] = 'none';
         }
       }
       //return $groups;

       return view('dashboard',compact('user', 'groups'));
    }
}
