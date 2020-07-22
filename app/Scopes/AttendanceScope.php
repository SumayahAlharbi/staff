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
       /*
       if (Auth::hasUser()) {
         $userId = Auth::user()->id;
         if (Auth::user()->hasRole('admin')) { // for manager role, show all the attendance records
           $builder;
         }
         elseif (Auth::user()->hasRole('manager')) { // for manager role, show the attendance of all his group members
           $builder;
           //$builder->whereIn('group_id', $userGroupIDs);
         } else { // for users without role, show their attendance only
         $builder->where('user_id', '=', $userId);
         }
       }
       */
     }
}
