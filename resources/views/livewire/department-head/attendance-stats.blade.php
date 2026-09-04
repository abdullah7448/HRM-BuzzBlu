<div>
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Present Card -->
        <div wire:click="viewList('present')" 
             class="bg-white rounded-xl shadow-card p-6 border-t-4 border-green-500 cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all duration-200 {{ $activeList === 'present' ? 'ring-2 ring-green-500 bg-green-50' : '' }}">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Present Today</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $presentCount }}</p>
                </div>
                <div class="p-3 bg-green-100 text-green-600 rounded-full flex-shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-green-600 mt-4 flex items-center gap-1 font-medium">
                View List 
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </p>
        </div>
        
        <!-- Absent Card -->
        <div wire:click="viewList('absent')" 
             class="bg-white rounded-xl shadow-card p-6 border-t-4 border-red-500 cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all duration-200 {{ $activeList === 'absent' ? 'ring-2 ring-red-500 bg-red-50' : '' }}">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Absent</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $absentCount }}</p>
                </div>
                <div class="p-3 bg-red-100 text-red-600 rounded-full flex-shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-red-600 mt-4 flex items-center gap-1 font-medium">
                View List 
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </p>
        </div>
        
        <!-- Total Team Card -->
        <div wire:click="viewList('total')" 
             class="bg-white rounded-xl shadow-card p-6 border-t-4 border-blue-500 cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all duration-200 {{ $activeList === 'total' ? 'ring-2 ring-blue-500 bg-blue-50' : '' }}">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Total Team</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalTeamCount }}</p>
                </div>
                <div class="p-3 bg-blue-100 text-blue-600 rounded-full flex-shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-blue-600 mt-4 flex items-center gap-1 font-medium">
                View List 
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </p>
        </div>
    </div>

    <!-- Dynamic List Section (ক্লিক করলে ওপেন হবে) -->
    @if($activeList)
        <div class="mt-6 bg-white rounded-xl shadow-card overflow-hidden border border-gray-200 animate-fade-in-up">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 capitalize">
                    {{ $activeList }} Employees
                </h3>
                <button wire:click="viewList(null)" class="text-gray-400 hover:text-red-500 transition-colors bg-white hover:bg-red-50 rounded-full p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="overflow-x-auto max-h-96 overflow-y-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-white sticky top-0 shadow-sm">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Designation</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @php
                            $displayUsers = $activeList === 'present' ? $presentUsers : ($activeList === 'absent' ? $absentUsers : $allUsers);
                        @endphp
                        
                        @forelse($displayUsers as $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-gray-200 flex-shrink-0 flex items-center justify-center text-gray-600 font-bold text-xs uppercase">
                                            {{ substr($user->name, 0, 2) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->designation->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    @if($activeList === 'total')
                                        @if($presentUsers->contains('id', $user->id))
                                            <span class="inline-flex px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Present</span>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">Absent</span>
                                        @endif
                                    @elseif($activeList === 'present')
                                        <span class="inline-flex px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Present</span>
                                    @else
                                        <span class="inline-flex px-2.5 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">Absent</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500">
                                    No employees found in this category.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>