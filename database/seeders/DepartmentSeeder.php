<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Designation;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Tech Department & Designations
        $tech = Department::firstOrCreate(['name' => 'Technology']);
        Designation::firstOrCreate(['department_id' => $tech->id, 'name' => 'Software Engineer']);
        Designation::firstOrCreate(['department_id' => $tech->id, 'name' => 'System Administrator']);

        // 2. Create Marketing & Media Department & Designations
        $marketing = Department::firstOrCreate(['name' => 'Marketing & Media']);
        Designation::firstOrCreate(['department_id' => $marketing->id, 'name' => 'Marketing Manager']);
        Designation::firstOrCreate(['department_id' => $marketing->id, 'name' => 'Video Editor']);

        // 3. Create HR Department
        $hr = Department::firstOrCreate(['name' => 'Human Resources']);
        Designation::firstOrCreate(['department_id' => $hr->id, 'name' => 'HR Executive']);
    }
}