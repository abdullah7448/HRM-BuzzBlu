<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceReport extends Component
{
    public $selectedMonth;

    public function mount()
    {
        $this->selectedMonth = Carbon::now()->format('Y-m');
    }

    public function render()
    {
        $year = Carbon::parse($this->selectedMonth)->year;
        $month = Carbon::parse($this->selectedMonth)->month;
        $daysInMonth = Carbon::parse($this->selectedMonth)->daysInMonth;

        $attendances = Attendance::where('user_id', Auth::id())
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'desc')
            ->get();

        $totalPresent = $attendances->count();
        $totalAbsent = $daysInMonth - $totalPresent;

        return view('livewire.employee.attendance-report', [
            'attendances' => $attendances,
            'totalPresent' => $totalPresent,
            'totalAbsent' => $totalAbsent,
            'daysInMonth' => $daysInMonth
        ]);
    }
}