<div class="bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-800 flex justify-between items-center">
        <h3 class="text-lg font-medium text-white">Master Leave Approvals (System Wide)</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee & Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type & Dates</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current Stage</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Master Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pendingRequests as $leave)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $leave->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $leave->user->getRoleNames()->first() ?? 'Employee' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $leave->leave_type }}</div>
                            <div class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} - 
                                {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($leave->status === 'pending_head_approval')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Waiting for Dept Head</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Waiting for HR</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <button wire:click="masterApprove({{ $leave->id }})" class="inline-flex items-center px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition">
                                Force Approve
                            </button>
                            <button wire:click="masterReject({{ $leave->id }})" class="inline-flex items-center px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition">
                                Reject
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            No pending leave requests in the system.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>