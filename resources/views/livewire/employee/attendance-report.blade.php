<div class="bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden mt-6">
    <div class="px-6 py-4 border-b border-gray-200 bg-indigo-50 flex flex-col sm:flex-row justify-between items-center">
        <h3 class="text-lg font-medium text-indigo-900 mb-2 sm:mb-0">My Monthly Attendance Report</h3>
        
        <!-- Month Filter -->
        <div class="flex items-center space-x-2">
            <label class="text-sm text-indigo-700 font-medium">Select Month:</label>
            <input type="month" wire:model.live.debounce.150ms="selectedMonth" class="border-gray-300 rounded-md shadow-sm text-sm" aria-label="Select attendance month">
        </div>
    </div>

    <!-- Attendance Summary Cards -->
    <div class="grid grid-cols-3 divide-x divide-gray-200 border-b border-gray-200 bg-white">
        <div class="p-4 text-center">
            <span class="block text-sm text-gray-500">Total Days</span>
            <span class="block text-2xl font-bold text-gray-900">{{ $daysInMonth }}</span>
        </div>
        <div class="p-4 text-center">
            <span class="block text-sm text-gray-500">Present</span>
            <span class="block text-2xl font-bold text-green-600">{{ $totalPresent }}</span>
        </div>
        <div class="p-4 text-center">
            <span class="block text-sm text-gray-500">Absent / Off</span>
            <span class="block text-2xl font-bold text-red-500">{{ $totalAbsent }}</span>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="overflow-x-auto max-h-64">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0 z-10">
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
                            {{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">
                            {{ $record->punch_in ? \Carbon\Carbon::parse($record->punch_in)->format('h:i A') : '--' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-500 font-medium">
                            {{ $record->punch_out ? \Carbon\Carbon::parse($record->punch_out)->format('h:i A') : 'Missing' }}
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
                            No records found for this month.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>