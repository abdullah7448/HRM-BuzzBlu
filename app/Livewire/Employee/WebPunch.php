<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WebPunch extends Component
{
    public $todayAttendance;

    public function mount()
    {
        $this->loadAttendance();
    }

    public function loadAttendance()
    {
        // Check if the user has an attendance record for today
        $this->todayAttendance = Attendance::where('user_id', Auth::id())
            ->where('date', Carbon::today())
            ->first();
    }

    public function punchIn()
    {
        Attendance::create([
            'user_id' => Auth::id(),
            'date' => Carbon::today(),
            'punch_in' => Carbon::now()->format('H:i:s'),
        ]);

        $this->loadAttendance();
    }

    public function punchOut()
    {
        if ($this->todayAttendance) {
            $this->todayAttendance->update([
                'punch_out' => Carbon::now()->format('H:i:s'),
            ]);
        }

        $this->loadAttendance();
    }

    public function render()
    {
        return view('livewire.employee.web-punch');
    }
}