<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\LeaveRequest;

class MasterLeaveApprovals extends Component
{
    public function masterApprove($leaveId)
    {
        $leave = LeaveRequest::findOrFail($leaveId);
        // Admin overrides all steps and directly approves
        $leave->update(['status' => 'approved']);
    }

    public function masterReject($leaveId)
    {
        $leave = LeaveRequest::findOrFail($leaveId);
        $leave->update(['status' => 'rejected']);
    }

    public function render()
    {
        // Admin sees ALL pending requests in the system, regardless of which stage they are in
        $pendingRequests = LeaveRequest::whereIn('status', ['pending_head_approval', 'pending_hr_approval'])
            ->with(['user', 'user.department'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.super-admin.master-leave-approvals', [
            'pendingRequests' => $pendingRequests
        ]);
    }
}