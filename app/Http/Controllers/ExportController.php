<?php

namespace App\Http\Controllers;

use App\AttendanceSheet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
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

    /* Start of User Group Validation  */
    $userGroups = Auth::user()->group;
      foreach ($userGroups as $userGroup) {
        $userGroupIDs[] =  $userGroup->id;
      };
      $data = [
      'group_id' => $group_id,
      'date' => $date
    ];
    $validator = Validator::make($data, [
      'date' => 'required',
      'group_id' => ['required', Rule::in($userGroupIDs)]]);
    /* END of User Group Validation  */

    if ($validator->fails()) {
      return back();
    }
    else {
    $date = $date;
    $group_id = $group_id;

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

    $group = Group::select('group_name')->where('id','=',$group_id)->first();

    $totallyAbsentArray = array();
    foreach ($totallyAbsent as $key => $value) {
      $totallyAbsentArray[$key] = [$value->name, $value->email,'Absent'];
    }

    $partiallyAbsentArray = array();
    foreach ($partiallyAbsent as $key => $value) {

      if ($value->action == 'Check In')
      $action = 'Missing Check Out';
      elseif ($value->action == 'Check Out')
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
