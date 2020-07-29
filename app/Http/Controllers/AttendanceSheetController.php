<?php

namespace App\Http\Controllers;

use App\AttendanceSheet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\User;
use App\Group;
use Auth;

class AttendanceSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $attendancesheets = AttendanceSheet::latest()->simplePaginate(15);

      return view('attendance.index', compact('attendancesheets'));
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

        $request->validate([
          'coords' => 'required',
          'Action' => [
          'required',
          Rule::in(['Check In', 'Check Out']),
      ],
    ]);

        $AttendanceSheet = new \App\AttendanceSheet;

        $AttendanceSheet->user_id = Auth::user()->id;
        $AttendanceSheet->group_id = $request->group_id;
        $AttendanceSheet->action = $request->Action;
        $AttendanceSheet->coords = $request->coords;

        $AttendanceSheet->save();
        return redirect('home')->with('success', 'Attendance has been taken');
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
