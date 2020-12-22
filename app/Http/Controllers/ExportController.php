<?php

namespace App\Http\Controllers;

use App\AttendanceSheet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\User;
use Auth;
use CSVReport;
use Carbon\Carbon;
use DB;
use App\Group;

class ExportController extends Controller
{

  public function attendancesheet(Request $request)
  {
    $fromDate = Carbon::parse($request->input('from_date'))->startOfDay();
    $toDate = Carbon::parse($request->input('to_date'))->endOfDay();
    $sortBy = $request->input('sort_by');

    $filename = 'attendance_sheet_'.Carbon::now()->format('dmy_his');
    $title = 'Staff Attendance Sheet';

    $meta = [
        'Attendance Sheet from' => $fromDate . ' To ' . $toDate,
        'Sort By' => $sortBy
    ];

    $queryBuilder = AttendanceSheet::whereBetween('created_at', [$fromDate, $toDate])
    ->orderBy($sortBy);

    $columns = [
      'ID' => function($queryBuilder) {
        return $queryBuilder->id;
      },
      'Name' => function($queryBuilder) {
        return $queryBuilder->user->name;
      },
      'Email' => function($queryBuilder) {
        return $queryBuilder->user->email;
      },
      'Group' => function($queryBuilder) {
        return $queryBuilder->group['group_name'];
      },
      'Type' => function($queryBuilder) {
        return $queryBuilder->action;
      },
      'Date/Time' => function($queryBuilder) {
        return $queryBuilder->created_at;
      },
    ];

    CSVReport::of($title, $meta, $queryBuilder, $columns)
    ->showNumColumn(false)
    ->download($filename);
  }

  public function downloadAbsencesheet($group_id, $date)
  {
    $date = $date;
    $group_id = $group_id;

    $totallyAbsent = User::GroupUsers()
    ->whereDoesntHave('attendance', function ($query) use ($date, $group_id) {
      $query->select(DB::raw("COUNT(*) count, user_id"))
      ->whereDate('created_at', '=', $date)
      ->where('group_id', '=', $group_id)
      ->groupBy('user_id');
    })->get();

    $partiallyAbsent = User::GroupUsers()
    ->with('attendance')
    ->whereHas('attendance', function ($query) use ($date, $group_id) {
      $query->select(DB::raw("COUNT(*) count, user_id"))
      ->whereDate('created_at', '=', $date)
      ->where('group_id', '=', $group_id)
      ->havingRaw('COUNT(*) = 1') // users with one attendance records daily
      ->groupBy('user_id');
    })->get();

    $group = Group::select('group_name')->where('id','=',$group_id)->first();

    foreach ($totallyAbsent as $key => $value) {
      foreach ($partiallyAbsent as $key2 => $value2) {
          if ( $value->id == $value2->id)
           unset($totallyAbsent[$key]);
        }
      }

    foreach ($totallyAbsent as $key => $value) {
        unset($value->id);
        unset($value->email_verified_at);
        unset($value->created_at);
        unset($value->updated_at);
    }

    $totallyAbsentArray = array();
    foreach ($totallyAbsent as $key => $value) {
      $totallyAbsentArray[$key] = [$value->name, $value->email,'Absent'];
    }

    $partiallyAbsentArray = array();
    foreach ($partiallyAbsent as $key => $value) {

      if ($value->attendance[0]->action == 'Check In')
      $action = 'Missing Check Out';
      elseif ($value->attendance[0]->action == 'Check Out')
      $action = 'Missing Check In';

      $partiallyAbsentArray[$key] = [$value->name, $value->email, $action];
    }



    $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject);
    $csv->insertOne(['Date',\Carbon\Carbon::parse($date)->format('d-m-Y'),'Group',$group->group_name]);
    $csv->insertOne([]); // blank row
    $csv->insertOne(['Name','Email','Type']);

    foreach ($partiallyAbsentArray as $value) {
      $csv->insertOne($value);
    }

    foreach ($totallyAbsentArray as $value) {
       $csv->insertOne($value);
     }

    $csv->output('absence_sheet_'.Carbon::now()->format('dmy_his').'.csv');

  }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
