<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\AttendanceSheet;



class UpdateAttendance extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        return view('livewire.update-attendance',[
            'attendancesheets' => AttendanceSheet::LocalAttendanceSheet()->latest()->paginate(15),
            ]);
    }
}
