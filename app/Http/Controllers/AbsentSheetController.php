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

      $totallyAbsent = User::GroupUsers()
      ->whereDoesntHave('attendance', function ($query) use ($date, $group_id) {
      $query->select(DB::raw("COUNT(*) count, day(created_at) day"))
              ->where('created_at', '=', $date)
              ->where('group_id', '=', $group_id)
              ->havingRaw('COUNT(*) = 2') // users must have two attendance records daily (check in, check out)
              ->groupBy('day');
            })->simplePaginate(15)->appends([
          'group_id' => $group_id,
          'date' => $date,
      ]);

      $partiallyAbsent = User::GroupUsers()
      ->with('attendance')
      ->whereHas('attendance', function ($query) use ($date, $group_id) {
      $query->select(DB::raw("COUNT(*) count, day(created_at) day"))
            ->whereDate('created_at', '=', $date)
            ->where('group_id', '=', $group_id)
            ->havingRaw('COUNT(*) = 1') // users with one attendance records daily
            ->groupBy('day');
          })->simplePaginate(15);

      foreach ($totallyAbsent as $key => $value) {
        foreach ($partiallyAbsent as $key2 => $value2) {
        if ( $value->id == $value2->id)
         unset($totallyAbsent[$key]);
      }
      }

      $userGroups = Auth::user()->group;
      $group = Group::where('id','=',$group_id)->get();
      return view('absence.index', compact('partiallyAbsent', 'userGroups', 'totallyAbsent','date','group'));
    }
}
