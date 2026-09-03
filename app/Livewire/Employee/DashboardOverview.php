<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use App\Models\Attendance;
use App\Models\LeaveRequest; // আপনার লিভ মডেলের নাম দিন
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardOverview extends Component
{
    // Attendance Stats
    public $presentDays = 0;
    public $absentDays = 0;
    public $attendancePercentage = 0;

    // Leave Stats (ধরে নিচ্ছি বছরে মোট ছুটি ২০ দিন)
    public $totalLeaves = 4; 
    public $usedLeaves = 0;
    public $remainingLeaves = 0;

    public function mount()
    {
        $userId = Auth::id();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // --- Attendance Calculation ---
        // এই মাসে কয়দিন প্রেজেন্ট ছিল
        $this->presentDays = Attendance::where('user_id', $userId)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->count();

        // এই মাসে এখন পর্যন্ত মোট কর্মদিবস (ছুটির দিন বাদে হিসাব করতে পারেন, আপাতত সাধারণ হিসাব দিচ্ছি)
        $totalDaysInMonthSoFar = Carbon::now()->day; 
        
        // অ্যাবসেন্ট ডেজ (মোট দিন থেকে প্রেজেন্ট দিন বাদ দিয়ে)
        $this->absentDays = $totalDaysInMonthSoFar - $this->presentDays;

        // পার্সেন্টেজ হিসাব (১০০% এর মধ্যে)
        if ($totalDaysInMonthSoFar > 0) {
            $this->attendancePercentage = round(($this->presentDays / $totalDaysInMonthSoFar) * 100);
        }

        // --- Leave Calculation ---
        // এই বছর কতগুলো ছুটি নিয়েছে (Approved স্ট্যাটাস ধরে)
        $this->usedLeaves = LeaveRequest::where('user_id', $userId)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'approved')
            ->count(); // এখানে আপনি start_date এবং end_date এর পার্থক্য (days) হিসাব করতে পারেন

        $this->remainingLeaves = $this->totalLeaves - $this->usedLeaves;
    }

   public function render()
    {
        return view('livewire.employee.dashboard-overview', [
            'leaveHistory' => \App\Models\LeaveRequest::where('user_id', \Illuminate\Support\Facades\Auth::id())->latest()->get()
        ]);
    }
}