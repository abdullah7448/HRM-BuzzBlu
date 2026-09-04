<div>
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Present (On Time) -->
        <div wire:click="viewList('present')" class="bg-white rounded-xl shadow-card p-6 border-t-4 border-green-500 cursor-pointer hover:shadow-lg transition-all {{ $activeList === 'present' ? 'ring-2 ring-green-500 bg-green-50' : '' }}">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Present (On Time)</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $presentCount }}</p>
                </div>
                <div class="p-2 bg-green-100 text-green-600 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-xs text-green-600 mt-4 flex items-center gap-1 font-medium">View List &rarr;</p>
        </div>

        <!-- Late -->
        <div wire:click="viewList('late')" class="bg-white rounded-xl shadow-card p-6 border-t-4 border-yellow-500 cursor-pointer hover:shadow-lg transition-all {{ $activeList === 'late' ? 'ring-2 ring-yellow-500 bg-yellow-50' : '' }}">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Late (After 9:10)</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $lateCount }}</p>
                </div>
                <div class="p-2 bg-yellow-100 text-yellow-600 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-xs text-yellow-600 mt-4 flex items-center gap-1 font-medium">View List &rarr;</p>
        </div>
        
        <!-- Absent -->
        <div wire:click="viewList('absent')" class="bg-white rounded-xl shadow-card p-6 border-t-4 border-red-500 cursor-pointer hover:shadow-lg transition-all {{ $activeList === 'absent' ? 'ring-2 ring-red-500 bg-red-50' : '' }}">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Absent</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $absentCount }}</p>
                </div>
                <div class="p-2 bg-red-100 text-red-600 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-xs text-red-600 mt-4 flex items-center gap-1 font-medium">View List &rarr;</p>
        </div>

        <!-- On Leave -->
        <div wire:click="viewList('onLeave')" class="bg-white rounded-xl shadow-card p-6 border-t-4 border-blue-500 cursor-pointer hover:shadow-lg transition-all {{ $activeList === 'onLeave' ? 'ring-2 ring-blue-500 bg-blue-50' : '' }}">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">On Leave</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $onLeaveCount }}</p>
                </div>
                <div class="p-2 bg-blue-100 text-blue-600 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            <p class="text-xs text-blue-600 mt-4 flex items-center gap-1 font-medium">View List &rarr;</p>
        </div>
    </div>

    <!-- Dynamic Employee List -->
    @if($activeList)
        <div class="mt-6 bg-white rounded-xl shadow-card overflow-hidden border border-gray-200 animate-fade-in-up">
            <div class="px-6 py-4 bg-gray-50 border-b flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 capitalize">{{ $activeList == 'onLeave' ? 'On Leave' : $activeList }} Employees</h3>
                <button wire:click="viewList(null)" class="text-gray-400 hover:text-red-500 bg-white rounded-full p-1"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            
            <div class="overflow-x-auto max-h-96 overflow-y-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-white sticky top-0 shadow-sm">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @php
                            $displayUsers = $activeList === 'present' ? $presentUsers : 
                                           ($activeList === 'late' ? $lateUsers : 
                                           ($activeList === 'absent' ? $absentUsers : $onLeaveUsers));
                        @endphp
                        
                        @forelse($displayUsers as $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->designation->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">{{ $user->department->name ?? 'N/A' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-6 py-8 text-center text-sm text-gray-500">No employees found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>