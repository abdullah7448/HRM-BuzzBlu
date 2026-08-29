<?php

namespace App\Livewire\Hr;

use Livewire\Component;
use App\Models\LeaveRequest;

class LeaveApprovals extends Component
{
    public function approve($leaveId)
    {
        $leave = LeaveRequest::findOrFail($leaveId);
        // Final approval step
        $leave->update(['status' => 'approved']);
    }

    public function reject($leaveId)
    {
        $leave = LeaveRequest::findOrFail($leaveId);
        // Reject it at the final stage
        $leave->update(['status' => 'rejected']);
    }

    public function render()
    {
        // Fetch only requests that have been approved by Dept Heads
        $pendingRequests = LeaveRequest::where('status', 'pending_hr_approval')
            ->with(['user', 'user.department'])
            ->get();

        return view('livewire.hr.leave-approvals', [
            'pendingRequests' => $pendingRequests
        ]);
    }
}