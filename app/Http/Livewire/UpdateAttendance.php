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
        $attendancesheets = AttendanceSheet::latest()->get();
        return view('livewire.update-attendance',[
            'attendancesheets' => $attendancesheets,
            'attendancesheets' => AttendanceSheet::paginate(10),
            ]);
    }
}
