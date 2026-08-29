<?php

namespace App\Livewire\DepartmentHead;

use Livewire\Component;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;

class LeaveApprovals extends Component
{
    public function approve($leaveId)
    {
        $leave = LeaveRequest::findOrFail($leaveId);
        // Move to the next stage in the workflow
        $leave->update(['status' => 'pending_hr_approval']);
    }

    public function reject($leaveId)
    {
        $leave = LeaveRequest::findOrFail($leaveId);
        // Reject it outright
        $leave->update(['status' => 'rejected']);
    }

    public function render()
    {
        $headDepartmentId = Auth::user()->department_id;

        // Fetch requests only for employees in the Head's department
        $pendingRequests = LeaveRequest::where('status', 'pending_head_approval')
            ->whereHas('user', function ($query) use ($headDepartmentId) {
                $query->where('department_id', $headDepartmentId);
            })
            ->with('user')
            ->get();

        return view('livewire.department-head.leave-approvals', [
            'pendingRequests' => $pendingRequests
        ]);
    }
}