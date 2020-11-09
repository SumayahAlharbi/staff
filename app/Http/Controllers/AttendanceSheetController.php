<?php

namespace App\Http\Controllers;

use App\AttendanceSheet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\User;
use App\Group;
use Carbon\Carbon;
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

    public function absent(Request $request)
    {
    //         $request->validate([
    //         'type' => [
    //         'required',
    //         Rule::in(['Check In', 'Check Out']),
    //       ],
    //   ]);
        $date = Carbon::parse($request->input('date'))->startOfDay();
        $type = $request->input('type');

        $absentSheets = User::GroupUsers()->whereDoesntHave('attendance', function ($query) use ($date, $type) {
            $query->whereDate('created_at', $date)->where('action', $type);
        })->simplePaginate(5)->appends([
            'date' => request('date'),
            'type' => request('type'),
        ]);
        
        return view('attendance.absent', compact('absentSheets','date','type'));

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
        $userGroups = Auth::user()->group;
        foreach ($userGroups as $userGroup) {
          $userGroupIDs[] =  $userGroup->id;
        };

        $request->validate([
          'coords' => 'required',
          'Action' => [
          'required',
          Rule::in(['Check In', 'Check Out']),
        ],
        'group_id' => [
          'required',
          Rule::in($userGroupIDs),
        ],
    ]);

        // Check user latest attendance - 5 min role
        $user = Auth::user();
        $UserAttendance = AttendanceSheet::where('user_id', '=', $user->id)
        ->where('created_at', '>', Carbon::now()->subMinutes(5)->toDateTimeString())
        ->get();

        if ($UserAttendance->isEmpty()) {
            $AttendanceSheet = new \App\AttendanceSheet;
            $AttendanceSheet->user_id = Auth::user()->id;
            $AttendanceSheet->group_id = $request->group_id;
            $AttendanceSheet->action = $request->Action;
            $AttendanceSheet->coords = $request->coords;
            $AttendanceSheet->save();
            return redirect('home')->with('success', 'Attendance has been taken');
        }
        else {
            return redirect('home')->with('danger', 'Attendance has been taken already!');
        }
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
