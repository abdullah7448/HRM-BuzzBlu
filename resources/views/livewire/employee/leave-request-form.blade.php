<div class="space-y-6">
    <!-- Leave Request Form -->
    <div class="bg-white p-6 border border-gray-200 rounded-lg shadow-sm">
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
</div>