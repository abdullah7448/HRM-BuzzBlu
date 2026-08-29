<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::view('/', 'welcome');

// The main traffic controller route after login
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->roles->contains('name', 'Super Admin')) {
        return redirect()->route('super-admin.dashboard');
    } elseif ($user->roles->contains('name', 'HR')) {
        return redirect()->route('hr.dashboard');
    } elseif ($user->roles->contains('name', 'Department Head')) {
        return redirect()->route('department-head.dashboard');
    } elseif ($user->roles->contains('name', 'Employee')) {
        return redirect()->route('employee.dashboard');
    }

    abort(403, 'Unauthorized access.');
})->middleware(['auth', 'verified'])->name('dashboard');

//profile route 
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/employee/{userId?}', \App\Livewire\Profile\EmployeeDetails::class)->name('employee.profile');
});
// Protected Route Groups for each Role
Route::middleware(['auth', 'role:Super Admin'])->group(function () {
    Route::get('/super-admin/dashboard', function () {
        return view('dashboards.super-admin');
    })->name('super-admin.dashboard');
});

Route::middleware(['auth', 'role:HR'])->group(function () {
    Route::get('/hr/dashboard', function () {
        return view('dashboards.hr');
    })->name('hr.dashboard');
});

Route::middleware(['auth', 'role:Department Head'])->group(function () {
    Route::get('/department-head/dashboard', function () {
        return view('dashboards.department-head');
    })->name('department-head.dashboard');
});

Route::middleware(['auth', 'role:Employee'])->group(function () {
    Route::get('/employee/dashboard', function () {
        return view('dashboards.employee');
    })->name('employee.dashboard');
});

require __DIR__.'/auth.php';