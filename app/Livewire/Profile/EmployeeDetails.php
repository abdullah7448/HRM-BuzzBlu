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
    
    // Admin/HR Edit Properties (All in one)
    public $isEditModalOpen = false;
    public $edit_employee_id, $edit_phone; // নতুন ফিল্ড যুক্ত করা হলো
    public $edit_name, $edit_email, $edit_department_id, $edit_designation_id, $edit_status, $hr_new_password;
    public $departments = [], $designations = [];

    // Personal Password Change Properties (For Employee themselves)
    public $isPasswordModalOpen = false;
    public $current_password, $new_password, $new_password_confirmation;

    public function mount($userId = null)
    {
        $targetId = $userId ?? Auth::id();
        
        $this->user = User::with(['department', 'designation', 'leaveRequests' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($targetId);

        $this->selectedMonth = Carbon::now()->format('Y-m');
        $this->departments = Department::all();
    }

    // Instantly loads designations when HR changes the department
    public function updatedEditDepartmentId($value)
    {
        $this->designations = Designation::where('department_id', $value)->get();
        $this->edit_designation_id = null;
    }

    // --- Admin/HR Edit Methods ---
    public function openEditModal() 
    { 
        // ইউজারের বর্তমান ডেটাগুলো প্রোপার্টিতে সেট করা হচ্ছে
        $this->edit_employee_id = $this->user->employee_id;
        $this->edit_phone = $this->user->phone;
        $this->edit_name = $this->user->name;
        $this->edit_email = $this->user->email;
        $this->edit_department_id = $this->user->department_id;
        $this->edit_designation_id = $this->user->designation_id;
        $this->edit_status = $this->user->status;
        $this->hr_new_password = ''; // Leave blank by default

        if ($this->edit_department_id) {
            $this->designations = Designation::where('department_id', $this->edit_department_id)->get();
        }

        $this->isEditModalOpen = true; 
    }

    public function closeEditModal() 
    { 
        $this->isEditModalOpen = false; 
    }

    public function updateProfile()
    {
        // নতুন ফিল্ডসহ ভ্যালিডেশন
        $this->validate([
            'edit_employee_id' => 'nullable|string|max:255|unique:users,employee_id,' . $this->user->id,
            'edit_phone'       => 'nullable|string|max:20',
            'edit_name'        => 'required|string|max:255',
            'edit_email'       => 'required|email|unique:users,email,' . $this->user->id,
            'hr_new_password'  => 'nullable|min:6', // Optional: Only validate if HR typed something
        ]);

        // নতুন ফিল্ডগুলো আপডেটের জন্য অ্যারেতে দেওয়া হলো
        $updateData = [
            'employee_id'    => $this->edit_employee_id,
            'phone'          => $this->edit_phone,
            'name'           => $this->edit_name,
            'email'          => $this->edit_email,
            'department_id'  => $this->edit_department_id,
            'designation_id' => $this->edit_designation_id,
            'status'         => $this->edit_status,
        ];

        // If HR typed a new password, hash it and add it to the update array
        if (!empty($this->hr_new_password)) {
            $updateData['password'] = Hash::make($this->hr_new_password);
        }

        $this->user->update($updateData);
        $this->user->refresh();
        $this->closeEditModal();
        session()->flash('success', 'Employee full profile updated successfully.');
    }

    // --- Personal Password Change Methods ---
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

        $this->user->update(['password' => Hash::make($this->new_password)]);
        $this->closePasswordModal();
        session()->flash('success', 'Your password has been changed successfully.');
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