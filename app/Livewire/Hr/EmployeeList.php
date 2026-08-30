<?php

namespace App\Livewire\Hr;

use Livewire\Component;
use App\Models\User;
use App\Models\Department;
use App\Models\Designation;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class EmployeeList extends Component
{
    // Form properties
    public $name, $email, $password, $department_id, $designation_id, $role_name;
    public $isModalOpen = false;

    // Validation rules
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'department_id' => 'required|exists:departments,id',
        'designation_id' => 'required|exists:designations,id',
        'role_name' => 'required|exists:roles,name',
    ];

    public function openModal()
    {
        $this->resetValidation();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->reset(['name', 'email', 'password', 'department_id', 'designation_id', 'role_name']);
    }

    public function saveEmployee()
    {
        $this->validate();

        // Create the user
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'department_id' => $this->department_id,
            'designation_id' => $this->designation_id,
            'join_date' => now(),
            'status' => 'active',
        ]);

        // Assign the Spatie role
        $user->assignRole($this->role_name);

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.hr.employee-list', [
            // UPDATE: Filter out Candidates, show only actual employees/HR/Admins
            'employees' => User::with(['department', 'designation', 'roles'])
                ->whereDoesntHave('roles', function($query) {
                    $query->where('name', 'Candidate');
                })
                ->latest()
                ->get(),
            'departments' => Department::all(),
            'designations' => Designation::where('department_id', $this->department_id)->get(),
            'roles' => Role::all(),
        ]);
    }
}