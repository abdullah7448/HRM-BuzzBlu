<div class="space-y-6">
    <!-- Leave Request Form -->
    <div class="bg-white p-6 border border-gray-200 rounded-lg shadow-sm">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Apply for Leave</h3>

        @if($successMessage)
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-md">
                {{ $successMessage }}
            </div>
        @endif

        <form wire:submit.prevent="submitLeaveRequest" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Leave Type</label>
                    <select wire:model="leave_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Select Type</option>
                        <option value="Casual Leave">Casual Leave</option>
                        <option value="Sick Leave">Sick Leave</option>
                        <option value="Annual Leave">Annual Leave</option>
                    </select>
                    @error('leave_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" wire:model="start_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" wire:model="end_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Reason</label>
                <textarea wire:model="reason" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                @error('reason') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow hover:bg-blue-700 transition">
                    Submit Request
                </button>
            </div>
        </form>
    </div>

    <!-- Leave History Table -->
    <div class="bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-medium text-gray-900">My Leave History</h3>
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
                    @forelse($leaveHistory as $leave)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $leave->leave_type }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }} - 
                                {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($leave->reason, 30) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($leave->status === 'pending_head_approval')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending Head</span>
                                @elseif($leave->status === 'pending_hr_approval')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Pending HR</span>
                                @elseif($leave->status === 'approved')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No leave requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>