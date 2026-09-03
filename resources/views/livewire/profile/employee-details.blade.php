<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $user->hasRole('Candidate') ? __('Candidate Profile: ') : __('Employee Profile: ') }} {{ $user->name }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- Success Message -->
        @if (session()->has('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3 mb-4">
            <!-- Show 'Change Password' ONLY if the user is viewing their OWN profile -->
            @if(Auth::id() === $user->id)
                <button wire:click="openPasswordModal" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-md shadow-sm hover:bg-gray-50 transition">
                    Change Password
                </button>
            @endif

            <!-- Show 'Edit Employee Details' ONLY to Admin and HR -->
            @hasanyrole('Super Admin|HR')
            <button wire:click="openEditModal" class="px-4 py-2 bg-gray-800 text-white text-sm font-semibold rounded-md shadow hover:bg-gray-700 transition">
                Manage Details
            </button>
            @endhasanyrole
        </div>

        <!-- Employee/Candidate Basic Details Card -->
        <div class="bg-white p-6 border border-gray-200 rounded-lg shadow-sm grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-2 border-b pb-2">Personal Information</h3>
                <p class="text-sm text-gray-600 mt-2"><strong>Employee ID:</strong> {{ $user->employee_id ?? 'N/A' }}</p>
                <p class="text-sm text-gray-600 mt-1"><strong>Name:</strong> {{ $user->name }}</p>
                <p class="text-sm text-gray-600 mt-1"><strong>Email:</strong> {{ $user->email }}</p>
                <p class="text-sm text-gray-600 mt-1"><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}</p>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-2 border-b pb-2">Company Information</h3>
                <p class="text-sm text-gray-600 mt-2"><strong>System Role:</strong> {{ $user->getRoleNames()->first() ?? 'N/A' }}</p>
                <p class="text-sm text-gray-600 mt-1"><strong>Department:</strong> {{ $user->department->name ?? 'Unassigned' }}</p>
                <p class="text-sm text-gray-600 mt-1"><strong>Designation:</strong> {{ $user->designation->name ?? 'Unassigned' }}</p>
                <p class="text-sm text-gray-600 mt-1 flex items-center gap-2"><strong>Status:</strong> 
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : ($user->status === 'on_leave' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst(str_replace('_', ' ', $user->status)) }}
                    </span>
                </p>
            </div>
        </div>

        <!-- ONLY SHOW LEAVE AND ATTENDANCE IF NOT A CANDIDATE -->
        @if(!$user->hasRole('Candidate'))
            
            <!-- Leave History Table -->
            <div class="bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900">Complete Leave History</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dates</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reason</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($user->leaveRequests as $leave)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $leave->leave_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }} - 
                                        {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($leave->reason, 40) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($leave->status === 'approved')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                        @elseif($leave->status === 'rejected')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No leave records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Monthly Attendance History -->
            <div class="bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden mt-6">
                <div class="px-6 py-4 border-b border-gray-200 bg-indigo-50 flex flex-col sm:flex-row justify-between items-center">
                    <h3 class="text-lg font-medium text-indigo-900 mb-2 sm:mb-0">Monthly Attendance Report</h3>
                    
                    <!-- Month Filter -->
                    <div class="flex items-center space-x-2">
                        <label class="text-sm text-indigo-700 font-medium">Select Month:</label>
                        <input type="month" wire:model.live="selectedMonth" class="border-gray-300 rounded-md shadow-sm text-sm">
                    </div>
                </div>

                <!-- Attendance Summary Cards -->
                <div class="grid grid-cols-3 divide-x divide-gray-200 border-b border-gray-200 bg-white">
                    <div class="p-4 text-center">
                        <span class="block text-sm text-gray-500">Total Days in Month</span>
                        <span class="block text-2xl font-bold text-gray-900">{{ $daysInMonth }}</span>
                    </div>
                    <div class="p-4 text-center">
                        <span class="block text-sm text-gray-500">Days Present</span>
                        <span class="block text-2xl font-bold text-green-600">{{ $totalPresent }}</span>
                    </div>
                    <div class="p-4 text-center">
                        <span class="block text-sm text-gray-500">Days Absent / Off</span>
                        <span class="block text-2xl font-bold text-red-500">{{ $totalAbsent }}</span>
                    </div>
                </div>

                <!-- Attendance Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Punch In</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Punch Out</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($attendances as $record)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($record->date)->format('M d, Y (l)') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">
                                        {{ $record->punch_in ? \Carbon\Carbon::parse($record->punch_in)->format('h:i A') : '--' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-500 font-medium">
                                        {{ $record->punch_out ? \Carbon\Carbon::parse($record->punch_out)->format('h:i A') : 'Missing / Active' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($record->punch_in && $record->punch_out)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Incomplete</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        No attendance records found for this month.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        @else
            <!-- Placeholder for Candidate specific data -->
            <div class="bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden mt-6 p-8 text-center">
                <h3 class="text-lg font-medium text-gray-500">This user is currently in the Recruitment Pipeline (Candidate).</h3>
                <p class="text-sm text-gray-400 mt-2">Attendance and Leave tracking features will be activated automatically once they are hired and converted to an Employee.</p>
            </div>
        @endif

        <!-- ============================================== -->
        <!-- MODALS SECTION -->
        <!-- ============================================== -->

        <!-- Admin/HR Full Edit Profile Modal -->
        @if($isEditModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-gray-500 bg-opacity-75 flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl p-6 my-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Edit Details: {{ $user->name }}</h3>
                
                <form wire:submit.prevent="updateProfile" class="space-y-4">
                    
                    <!-- Emp ID and Phone -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Employee ID</label>
                            <input type="text" wire:model="edit_employee_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('edit_employee_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="text" wire:model="edit_phone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('edit_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Name and Email -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" wire:model="edit_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('edit_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" wire:model="edit_email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('edit_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Department and Designation -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Department</label>
                            <select wire:model.live="edit_department_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Select Department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Designation</label>
                            <select wire:model="edit_designation_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Select Designation</option>
                                @foreach($designations as $desig)
                                    <option value="{{ $desig->id }}">{{ $desig->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Status and Password Reset -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 border rounded-md">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Account Status</label>
                            <select wire:model="edit_status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="active">Active</option>
                                <option value="on_leave">On Leave</option>
                                <option value="terminated">Terminated</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-red-600">Force Password Reset</label>
                            <input type="text" wire:model="hr_new_password" placeholder="Leave blank to keep current" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <span class="text-xs text-gray-500">Type a new password here if they forgot theirs.</span>
                            @error('hr_new_password') <span class="text-red-500 text-xs block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" wire:click="closeEditModal" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save Complete Profile</button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Change Password Modal -->
        @if($isPasswordModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-gray-500 bg-opacity-75 flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>
                <form wire:submit.prevent="updatePassword">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Current Password</label>
                        <input type="password" wire:model="current_password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('current_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" wire:model="new_password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('new_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <input type="password" wire:model="new_password_confirmation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" wire:click="closePasswordModal" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
        @endif

    </div>
</div> 