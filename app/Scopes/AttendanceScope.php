<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
//use App\Group;

class AttendanceScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
     public function apply(Builder $builder, Model $model)
     {
       if (Auth::hasUser()) {
         // for admin role, show all the attendance records
         if (Auth::user()->hasRole('admin')) {
          $builder;
         }
         // show the attendance of all group members (admin dose not have groups)
         elseif (Auth::user()->hasPermissionTo('view attendance sheet')) {
           $userGroups = Auth::user()->group;
             foreach ($userGroups as $userGroup) {
               $userGroupIDs[] =  $userGroup->id;
             };
           $builder->whereIn('group_id', $userGroupIDs);
         } else { // for users without the permission of (view attendance sheet), show their attendance only
           $userId = Auth::user()->id;
           $builder->where('user_id', '=', $userId);
         }
       }

     }
}
