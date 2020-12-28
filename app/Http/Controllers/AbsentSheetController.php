<?php

namespace App\Http\Controllers;

use App\AttendanceSheet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\User;
use App\Group;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Auth;
use DB;

class AbsentSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $userGroups = Auth::user()->group;
      return view('absence.index', compact('userGroups'));
    }

    public function absence(Request $request)
    {
      $request->validate([
              'group_id' => 'required_unless:none,0',
              'date' => 'required',
        ]);

      $date = $request->input('date');
      $group_id = $request->input('group_id');

      /*$totallyAbsent = User::GroupUsers()
      ->whereDoesntHave('attendance', function ($query) use ($date, $group_id) {
        $query->select(DB::raw("COUNT(*) count, user_id"))
        ->whereDate('created_at', '=', $date)
        ->where('group_id', '=', $group_id)
        ->groupBy('user_id');
      })->get();
      //  })->simplePaginate(15);

      $partiallyAbsent = User::GroupUsers()
      ->with('attendance')
      ->whereHas('attendance', function ($query) use ($date, $group_id) {
        $query->select(DB::raw("COUNT(*) count, user_id"))
        ->whereDate('created_at', '=', $date)
        ->where('group_id', '=', $group_id)
        ->havingRaw('COUNT(*) = 1') // users with one attendance records daily
        ->groupBy('user_id');
        //->havingRaw('COUNT(*) = 1');
        //->having(DB::raw('count(*)'), '=', 1);
      })->get();
      //})->simplePaginate(15);

      foreach ($totallyAbsent as $key => $value) {
        foreach ($partiallyAbsent as $key2 => $value2) {
          if ( $value->id == $value2->id)
          unset($totallyAbsent[$key]);
        }
      }*/

      $totallyAbsent = DB::table('users')
       ->select('users.name','users.email')
       ->join('group_to_user', 'group_to_user.user_id', '=', 'users.id')
       ->where('group_to_user.group_id', '=', $group_id)
       ->whereNotIn('users.id', function($query) use ($date, $group_id) {
        $query->select('attendance_sheet.user_id')->from('attendance_sheet')
        ->whereDate('attendance_sheet.created_at', '=', $date)
        ->where('attendance_sheet.group_id', '=', $group_id);
      })->get();

      $partiallyAbsent = DB::table('attendance_sheet')
        ->select('users.name','users.email','attendance_sheet.action')
        ->join('users', 'users.id', '=', 'attendance_sheet.user_id')
        ->whereDate('attendance_sheet.created_at', '=', $date)
        ->where('attendance_sheet.group_id', '=', $group_id)
        ->havingRaw('COUNT(*) = 1')
        ->groupBy('users.name','users.email','attendance_sheet.action')
        ->get();

      $userGroups = Auth::user()->group;
      $group_name = Group::where('id','=',$group_id)->value('group_name');
      return view('absence.index', compact('partiallyAbsent', 'userGroups', 'totallyAbsent','date','group_name'));
    }
}
