<div class="bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-200 bg-blue-50 flex justify-between items-center flex-wrap gap-4">
        <h3 class="text-lg font-medium text-blue-900 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Final Leave Approvals (HR)
        </h3>
        
        <!-- Department Filter -->
        <div class="flex items-center gap-2">
            <label class="text-sm font-medium text-gray-700">Filter:</label>
            <select wire:model.live="departmentId" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee & Dept</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type & Dates</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reason</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pendingRequests as $leave)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $leave->user->name }}</div>
                            <div class="text-xs text-gray-500 bg-gray-100 inline-block px-2 py-0.5 rounded mt-1">{{ $leave->user->department->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-blue-600">{{ $leave->leave_type }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ \Carbon\Carbon::parse($leave->start_date)->format('d M') }} 
                                <span class="mx-1">&rarr;</span> 
                                {{ \Carbon\Carbon::parse($leave->end_date)->format('d M, Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ Str::limit($leave->reason, 45) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <button wire:click="approve({{ $leave->id }})" class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white rounded hover:bg-green-700 transition shadow-sm">
                                Approve
                            </button>
                            <button wire:click="reject({{ $leave->id }})" class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white rounded hover:bg-red-700 transition shadow-sm">
                                Reject
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-sm text-gray-500 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                No pending leave requests.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>