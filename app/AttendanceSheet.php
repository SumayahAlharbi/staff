<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

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

     public function group()
     {
     return $this->belongsTo('App\Group');
     }


     public static function boot()
     {
       parent::boot();
       static::addGlobalScope(new Scopes\AttendanceScope);
     }

     //Scopes
public function scopeLocalAttendanceSheet($query)
{
  if (Auth::hasUser()) {
    // for admin role, show all the attendance records
    if (Auth::user()->hasRole('admin')) {
     $query;
    }
    // show the attendance of all group members (admin dose not have groups)
    elseif (Auth::user()->hasPermissionTo('view attendance sheet')) {
      $userGroups = Auth::user()->group;
        foreach ($userGroups as $userGroup) {
          $userGroupIDs[] =  $userGroup->id;
        };
      $query->whereIn('group_id', $userGroupIDs);
    } else { // for users without the permission of (view attendance sheet), show their attendance only
      $userId = Auth::user()->id;
      $query->where('user_id', '=', $userId);
    }
  }
}

}
