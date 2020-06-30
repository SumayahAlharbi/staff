<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendanceSheet extends Model
{
  public $table = "attendance_sheet";

    //
    /**
     * Get the user that owns the Attendance.
     */
     public function user()
     {
     return $this->belongsTo('App\User');
     }
}
