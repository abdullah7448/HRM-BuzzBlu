<?php
namespace App\Livewire\Hr;

use Livewire\Component;
use App\Models\LeaveRequest;
use App\Models\Department;

class LeaveApprovals extends Component
{
    public $departmentId = ''; // ফিল্টার করার জন্য

    public function approve($leaveId)
    {
        $leave = LeaveRequest::findOrFail($leaveId);
        $leave->update(['status' => 'approved']);
    }

    public function reject($leaveId)
    {
        $leave = LeaveRequest::findOrFail($leaveId);
        $leave->update(['status' => 'rejected']);
    }

    public function render()
    {
        $departments = Department::all();

        // Query Builder
        $query = LeaveRequest::where('status', 'pending_hr_approval')
            ->with(['user', 'user.department']);

        // যদি কোনো ডিপার্টমেন্ট সিলেক্ট করা হয়
        if ($this->departmentId) {
            $query->whereHas('user', function ($q) {
                $q->where('department_id', $this->departmentId);
            });
        }

        return view('livewire.hr.leave-approvals', [
            'pendingRequests' => $query->latest()->get(),
            'departments' => $departments
        ]);
    }
}