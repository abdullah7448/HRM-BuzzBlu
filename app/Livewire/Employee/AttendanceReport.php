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

    public function updatedSelectedMonth($value): void
    {
        if (! preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', (string) $value)) {
            $this->selectedMonth = Carbon::now()->format('Y-m');
        }
    }

    public function render()
    {
        $selectedDate = Carbon::createFromFormat('!Y-m', $this->selectedMonth);
        $year = $selectedDate->year;
        $month = $selectedDate->month;
        $daysInMonth = $selectedDate->daysInMonth;

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