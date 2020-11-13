<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
  protected $fillable = [
  'email', 'group_id', 'token',
];

public function group()
  {
  return $this->belongsTo('App\Group');
    }
}
