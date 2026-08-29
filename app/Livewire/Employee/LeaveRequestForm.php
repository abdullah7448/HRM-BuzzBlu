<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;

class LeaveRequestForm extends Component
{
    public $leave_type, $start_date, $end_date, $reason;
    public $successMessage = '';

    protected $rules = [
        'leave_type' => 'required|string',
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after_or_equal:start_date',
        'reason' => 'required|string|min:10',
    ];

    public function submitLeaveRequest()
    {
        $this->validate();

        LeaveRequest::create([
            'user_id' => Auth::id(),
            'leave_type' => $this->leave_type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'reason' => $this->reason,
            // Status defaults to 'pending_head_approval' from the migration
        ]);

        $this->reset(['leave_type', 'start_date', 'end_date', 'reason']);
        $this->successMessage = 'Leave request submitted successfully and is pending Department Head approval.';
    }

    public function render()
    {
        return view('livewire.employee.leave-request-form', [
            'leaveHistory' => LeaveRequest::where('user_id', Auth::id())->latest()->get()
        ]);
    }
}