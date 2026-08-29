<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class EmployeeDetails extends Component
{
    public $user;
    public $selectedMonth;
    
    // Admin/HR Edit Properties
    public $isEditModalOpen = false;
    public $edit_department_id, $edit_designation_id, $edit_status;
    public $departments = [], $designations = [];

    // Personal Edit Properties (For the Employee)
    public $isPersonalModalOpen = false;
    public $edit_name, $edit_email;

    // Password Change Properties
    public $isPasswordModalOpen = false;
    public $current_password, $new_password, $new_password_confirmation;

    public function mount($userId = null)
    {
        $targetId = $userId ?? Auth::id();
        
        $this->user = User::with(['department', 'designation', 'leaveRequests' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($targetId);

        $this->selectedMonth = Carbon::now()->format('Y-m');

        // Load Admin/HR Edit Data
        $this->departments = Department::all();
        $this->edit_department_id = $this->user->department_id;
        $this->edit_designation_id = $this->user->designation_id;
        $this->edit_status = $this->user->status;
        if ($this->edit_department_id) {
            $this->designations = Designation::where('department_id', $this->edit_department_id)->get();
        }

        // Load Personal Edit Data
        $this->edit_name = $this->user->name;
        $this->edit_email = $this->user->email;
    }

    public function updatedEditDepartmentId($value)
    {
        $this->designations = Designation::where('department_id', $value)->get();
        $this->edit_designation_id = null;
    }

    // Admin/HR Edit Methods
    public function openEditModal() { $this->isEditModalOpen = true; }
    public function closeEditModal() { $this->isEditModalOpen = false; }
    public function updateProfile()
    {
        $this->user->update([
            'department_id' => $this->edit_department_id,
            'designation_id' => $this->edit_designation_id,
            'status' => $this->edit_status,
        ]);
        $this->user->refresh();
        $this->closeEditModal();
        session()->flash('success', 'Company details updated successfully.');
    }

    // Personal Info Edit Methods
    public function openPersonalModal() { $this->isPersonalModalOpen = true; }
    public function closePersonalModal() { $this->isPersonalModalOpen = false; }
    public function updatePersonalInfo()
    {
        $this->validate([
            'edit_name' => 'required|string|max:255',
            'edit_email' => 'required|email|unique:users,email,' . $this->user->id,
        ]);

        $this->user->update([
            'name' => $this->edit_name,
            'email' => $this->edit_email,
        ]);
        $this->user->refresh();
        $this->closePersonalModal();
        session()->flash('success', 'Personal information updated successfully.');
    }

    // Password Change Methods
    public function openPasswordModal() { $this->isPasswordModalOpen = true; }
    public function closePasswordModal() 
    { 
        $this->isPasswordModalOpen = false; 
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
    }
    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $this->user->update([
            'password' => Hash::make($this->new_password),
        ]);
        $this->closePasswordModal();
        session()->flash('success', 'Password changed successfully.');
    }

    public function render()
    {
        $year = Carbon::parse($this->selectedMonth)->year;
        $month = Carbon::parse($this->selectedMonth)->month;
        $daysInMonth = Carbon::parse($this->selectedMonth)->daysInMonth;

        $attendances = Attendance::where('user_id', $this->user->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'desc')
            ->get();

        return view('livewire.profile.employee-details', [
            'attendances' => $attendances,
            'totalPresent' => $attendances->count(),
            'totalAbsent' => $daysInMonth - $attendances->count(),
            'daysInMonth' => $daysInMonth
        ])->layout('layouts.app');
    }
}