<div>
    <!-- Top Header & Button -->
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-medium text-gray-900">Employee Directory</h3>
        <button wire:click="openModal" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            + Add New Employee
        </button>
    </div>

    <!-- Employee Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dept & Desig</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($employees as $employee)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $employee->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $employee->getRoleNames()->first() ?? 'Unassigned' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $employee->department->name ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $employee->designation->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $employee->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($employee->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('employee.profile', $employee->id) }}" class="text-blue-600 hover:text-blue-900 underline">
                                View Profile
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add Employee Modal -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-gray-500 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Employee</h3>
            
            <form wire:submit.prevent="saveEmployee">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" wire:model="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" wire:model="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Temporary Password</label>
                    <input type="password" wire:model="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">System Role</label>
                    <select wire:model="role_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Department</label>
                    <!-- wire:model.live instantly updates the component so the Designations dropdown can react -->
                    <select wire:model.live="department_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Designation</label>
                    <select wire:model="designation_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Select Designation</option>
                        @foreach($designations as $desig)
                            <option value="{{ $desig->id }}">{{ $desig->name }}</option>
                        @endforeach
                    </select>
                    @error('designation_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save Employee</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>