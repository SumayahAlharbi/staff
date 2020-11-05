<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\AttendanceSheet;
use Livewire\WithPagination;

class UpdateAttendance extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $attendancesheets = AttendanceSheet::latest()->paginate(15);
        return view('livewire.update-attendance',['attendancesheets' => $attendancesheets]);
    }
}
