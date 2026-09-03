<?php

namespace App\Livewire\Hr;

use Livewire\Component;
use Livewire\WithPagination; // পেজিনেশনের জন্য এটি অবশ্যই লাগবে
use App\Models\User;
use App\Models\Department;
use App\Models\Designation;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class EmployeeList extends Component
{
    use WithPagination;

    // Form properties
    public $name, $email, $password, $department_id, $designation_id, $role_name;
    public $employee_id, $phone; // নতুন ফিল্ড
    public $isModalOpen = false;

    // Search and Filter properties
    public $search = '';
    public $filter_department = '';
    public $filter_designation = '';
    public $filter_status = '';

    // Validation rules
    protected function rules()
    {
        return [
            'employee_id' => 'nullable|string|max:255|unique:users,employee_id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|min:6',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'role_name' => 'required|exists:roles,name',
        ];
    }

    // ফিল্টার বা সার্চ করলে পেজ যেন ১-এ ফিরে আসে
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterDepartment() { $this->resetPage(); }
    public function updatingFilterDesignation() { $this->resetPage(); }
    public function updatingFilterStatus() { $this->resetPage(); }

    public function openModal()
    {
        $this->resetValidation();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->reset(['employee_id', 'name', 'email', 'phone', 'password', 'department_id', 'designation_id', 'role_name']);
    }

    public function saveEmployee()
    {
        $this->validate();

        // Create the user
        $user = User::create([
            'employee_id' => $this->employee_id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => Hash::make($this->password),
            'department_id' => $this->department_id,
            'designation_id' => $this->designation_id,
            'join_date' => now(),
            'status' => 'active',
        ]);

        // Assign the Spatie role
        $user->assignRole($this->role_name);

        session()->flash('success', 'Employee successfully added.');
        $this->closeModal();
    }

    public function render()
    {
        $query = User::with(['department', 'designation', 'roles'])
            ->whereDoesntHave('roles', function($q) {
                $q->where('name', 'Candidate'); // ক্যান্ডিডেট বাদ দেওয়া হলো
            });

        // Search logic
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('employee_id', 'like', '%' . $this->search . '%');
            });
        }

        // Filters
        if (!empty($this->filter_department)) {
            $query->where('department_id', $this->filter_department);
        }
        if (!empty($this->filter_designation)) {
            $query->where('designation_id', $this->filter_designation);
        }
        if (!empty($this->filter_status)) {
            $query->where('status', $this->filter_status);
        }

        return view('livewire.hr.employee-list', [
            'employees' => $query->latest()->paginate(10), // পেজিনেশন যোগ করা হলো
            'departments' => Department::all(),
            'designations' => Designation::where('department_id', $this->department_id)->get(),
            'roles' => Role::all(),
        ]);
    }
}