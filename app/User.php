<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Auth;

class User extends Authenticatable
{
    use HasRoles,
    Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function group()
    {
        return $this->belongsToMany('App\Group','group_to_user', 'user_id', 'group_id')->withTimestamps();
    }
    public function attendance()
    {
      return $this->hasMany('App\AttendanceSheet');
    }

//Scopes
public function scopeGroupUsers($query)
   {
     if (Auth::user()->hasRole('admin')) {
       return $query;
     }else {
       // Access Pivot Table
       return $query->whereHas('group', function($query) {
         $userGroups = Auth::user()->group;
         foreach ($userGroups as $userGroup) {
         $userGroupIDs[] =  $userGroup->id;
       };
       $query->whereIn('group_id', $userGroupIDs); });
     }
   }

}
