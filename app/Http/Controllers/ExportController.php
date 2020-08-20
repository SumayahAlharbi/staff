<?php

namespace App\Http\Controllers;

use App\AttendanceSheet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\User;
use Auth;
use CSVReport;
use Carbon\Carbon;

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
