<?php

namespace App\Livewire\DepartmentHead;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EmployeeList extends Component
{
    public function render()
    {
        $headDepartmentId = Auth::user()->department_id;

        // If the Department Head has no department assigned, return an empty list
        if (!$headDepartmentId) {
            $employees = collect();
        } else {
            // Fetch employees in the same department, excluding the Head themselves
            $employees = User::where('department_id', $headDepartmentId)
                ->where('id', '!=', Auth::id())
                ->with(['designation', 'roles'])
                ->get();
        }

        return view('livewire.department-head.employee-list', [
            'employees' => $employees,
            'hasDepartment' => (bool) $headDepartmentId
        ]);
    }
}