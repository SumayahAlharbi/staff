<?php

namespace App\Http\Controllers;

use App\AttendanceSheet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\User;
use App\Group;
use Carbon\Carbon;
use Auth;

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
              //'check_in' => 'required_without_all:check_out',
              //'check_out' => 'required_without_all:check_in',
              'group_id' => 'required_unless:none,0',
              'from_date' => 'required',
              'to_date' => 'required',
        ]);

      $from_date = $request->input('from_date');
      $to_date = $request->input('to_date');
      //$check_in = $request->input('check_in');
      //$check_out = $request->input('check_out');
      $group_id = $request->input('group_id');

      /*$absentsheets = User::GroupUsers()->whereDoesntHave('attendance', function ($query) use ($from_date, $to_date, $group_id) {
      $query->selectRaw('day(created_at) day')
            ->whereBetween('created_at', [$from_date, $to_date])
            ->where('group_id', '=', $group_id)
            ->havingRaw('COUNT(*) = 2') // users must have two attendance records daily (check in, check out)
            ->groupBy('day');
          })->simplePaginate(15);
          */

      /*$absentsheets_missing = User::GroupUsers()->whereHas('attendance', function ($query) use ($from_date, $to_date, $group_id) {
      $query->selectRaw('action, user_id, created_at')
          ->whereBetween('created_at', [$from_date, $to_date])
                ->where('group_id', '=', $group_id)
                ->havingRaw('COUNT(*) = 1') // users must have two attendance records daily (check in, check out)
                ->groupBy('action', 'user_id', 'created_at');
              })->get();
              */
      $absentsheets = User::GroupUsers()
      ->with('attendance')
      ->whereHas('attendance', function ($query) use ($from_date, $to_date, $group_id) {
      $query->selectRaw('day(created_at) day')
            ->whereBetween('created_at', [$from_date, $to_date])
            ->where('group_id', '=', $group_id)
            ->havingRaw('COUNT(*) = 1') // users with one attendance records daily
            ->groupBy('day');
          })->simplePaginate(15)->appends([
              'group_id' => $group_id,
              'from' => $from_date,
              'to' => $to_date,
          ]);


          /*$array = array();
          foreach ($absentsheets_missing as $key => $value) {
            $search = AttendanceSheet::select('action','created_at')
            ->where('user_id','=',$key)
            ->where('group_id','=',$group)
            ->where('created_at','=','')
            ->get();
            $array = $search;
          }*/


      /*->simplePaginate(15)->appends([
          'group_id' => $group_id,
          'from' => $from_date,
          'to' => $to_date,
      ]);
      */

      //return $absentsheets;
      $userGroups = Auth::user()->group;
      return view('absence.index', compact('absentsheets','userGroups'));

    }
}
