<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\User;

class UsersSearch extends Component
{
    public $usersSearch;

    public function render()
    {
        $usersSearch = '%'.$this->usersSearch . '%';
       $users = User::where('id', 'LIKE', '%' . $usersSearch . '%')
      ->orWhere('name', 'LIKE', '%' . $usersSearch . '%')
      ->orWhere('email', 'LIKE', '%' . $usersSearch . '%')
      ->orWhere('name', 'LIKE', '%' . $usersSearch . '%')->GroupUsers()->paginate(7);

        return view('livewire.users-search',['users'=>$users]);
    }
}
