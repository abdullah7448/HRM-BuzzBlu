<div>
    <!-- Premium Header -->
    <x-dashboard-header 
        title="Employee Workspace"
        description="Manage your attendance, leave requests, and personal profile"
        icon='<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M10 10a3 3 0 100-6 3 3 0 000 6zm0 2c-3.314 0-6 1.343-6 3v1h12v-1c0-1.657-2.686-3-6-3z" stroke="currentColor" stroke-width="1.5" fill="none"></path></svg>'
    />

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div x-data="{ activeTab: 'attendance', subTab: { attendance: 'overview', leave: 'request', profile: 'details' } }" class="space-y-6">
                
                <!-- Main Tab Navigation -->
                <div class="tab-nav">
                    <div class="tab-nav-container flex space-x-4 border-b border-gray-200">
                        <button
                            @click="activeTab = 'attendance'"
                            :class="activeTab === 'attendance' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center space-x-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span>Attendance</span>
                        </button>

                        <button
                            @click="activeTab = 'leave'"
                            :class="activeTab === 'leave' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center space-x-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Leave</span>
                        </button>

                        <button
                            @click="activeTab = 'profile'"
                            :class="activeTab === 'profile' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center space-x-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Profile</span>
                        </button>
                    </div>
                </div>

                <!-- ATTENDANCE TAB -->
                <div x-show="activeTab === 'attendance'" x-transition class="space-y-6">
                    <div class="bg-white rounded-xl shadow-card p-6 border border-gray-100">
                        <div class="sub-tab-nav flex space-x-4 mb-6">
                            <button
                                @click="subTab.attendance = 'overview'"
                                :class="subTab.attendance === 'overview' ? 'bg-blue-50 text-blue-600 rounded-md' : 'text-gray-500 hover:bg-gray-50 rounded-md'"
                                class="px-3 py-2 text-sm font-medium"
                            >
                                📊 Overview
                            </button>
                            <button
                                @click="subTab.attendance = 'punch'"
                                :class="subTab.attendance === 'punch' ? 'bg-blue-50 text-blue-600 rounded-md' : 'text-gray-500 hover:bg-gray-50 rounded-md'"
                                class="px-3 py-2 text-sm font-medium"
                            >
                                🕐 Punch
                            </button>
                            <button
                                @click="subTab.attendance = 'report'"
                                :class="subTab.attendance === 'report' ? 'bg-blue-50 text-blue-600 rounded-md' : 'text-gray-500 hover:bg-gray-50 rounded-md'"
                                class="px-3 py-2 text-sm font-medium"
                            >
                                📈 Report
                            </button>
                        </div>

                        <!-- Dynamic Attendance Overview -->
                        <div x-show="subTab.attendance === 'overview'" x-transition>
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <div class="stat-card stat-card-success bg-green-50 p-4 rounded-lg border border-green-100">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="stat-label text-sm text-green-600 font-semibold">Present This Month</p>
                                            <p class="stat-value mt-2 text-2xl font-bold text-green-800">{{ $presentDays }}</p>
                                        </div>
                                        <div class="stat-icon stat-icon-success text-green-500">
                                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="stat-card stat-card-warning bg-red-50 p-4 rounded-lg border border-red-100">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="stat-label text-sm text-red-600 font-semibold">Absent This Month</p>
                                            <p class="stat-value mt-2 text-2xl font-bold text-red-800">{{ $absentDays }}</p>
                                        </div>
                                        <div class="stat-icon stat-icon-warning text-red-500">
                                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="stat-card stat-card-primary bg-blue-50 p-4 rounded-lg border border-blue-100">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="stat-label text-sm text-blue-600 font-semibold">Attendance %</p>
                                            <p class="stat-value mt-2 text-2xl font-bold text-blue-800">{{ $attendancePercentage }}%</p>
                                        </div>
                                        <div class="stat-icon stat-icon-primary text-blue-500">
                                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h.01a1 1 0 110 2H12zm-2 4a2 2 0 104 0 2 2 0 00-4 0zm0 6a2 2 0 104 0 2 2 0 00-4 0z" clip-rule="evenodd" /></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div x-show="subTab.attendance === 'punch'" style="display: none;" x-transition>
                            <h3 class="card-header text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                                Clock In/Out
                            </h3>
                            <livewire:employee.web-punch />
                        </div>

                        <div x-show="subTab.attendance === 'report'" style="display: none;" x-transition>
                            <h3 class="card-header text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                                Attendance Record
                            </h3>
                            <livewire:employee.attendance-report />
                        </div>
                    </div>
                </div>

                <!-- LEAVE TAB -->
                <div x-show="activeTab === 'leave'" style="display: none;" x-transition class="space-y-6">
                    <div class="bg-white rounded-xl shadow-card p-6 border border-gray-100">
                        <div class="sub-tab-nav flex space-x-4 mb-6">
                            <button
                                @click="subTab.leave = 'stats'"
                                :class="subTab.leave === 'stats' ? 'bg-blue-50 text-blue-600 rounded-md' : 'text-gray-500 hover:bg-gray-50 rounded-md'"
                                class="px-3 py-2 text-sm font-medium"
                            >
                                📊 Statistics
                            </button>
                            <button
                                @click="subTab.leave = 'request'"
                                :class="subTab.leave === 'request' ? 'bg-blue-50 text-blue-600 rounded-md' : 'text-gray-500 hover:bg-gray-50 rounded-md'"
                                class="px-3 py-2 text-sm font-medium"
                            >
                                📝 Request
                            </button>
                        </div>

                        <!-- Dynamic Leave Statistics -->
                        <div x-show="subTab.leave === 'stats'" x-transition>
                            <div x-show="subTab.leave === 'stats'" x-transition class="space-y-6">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="stat-card stat-card-primary bg-indigo-50 p-4 rounded-lg border border-indigo-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label text-sm text-indigo-600 font-semibold">Remaining Days</p>
                    <p class="stat-value mt-2 text-2xl font-bold text-indigo-800">{{ $remainingLeaves }}</p>
                </div>
                <div class="stat-icon stat-icon-primary text-indigo-500">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M6 5a2 2 0 012-2h6a2 2 0 012 2v9a2 2 0 01-2 2H8a2 2 0 01-2-2V5z" /></svg>
                </div>
            </div>
        </div>
        <div class="stat-card stat-card-warning bg-orange-50 p-4 rounded-lg border border-orange-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label text-sm text-orange-600 font-semibold">Used Days</p>
                    <p class="stat-value mt-2 text-2xl font-bold text-orange-800">{{ $usedLeaves }}</p>
                </div>
                <div class="stat-icon stat-icon-warning text-orange-500">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                </div>
            </div>
        </div>
        <div class="stat-card stat-card-success bg-teal-50 p-4 rounded-lg border border-teal-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label text-sm text-teal-600 font-semibold">Total Allocation</p>
                    <p class="stat-value mt-2 text-2xl font-bold text-teal-800">{{ $totalLeaves }}</p>
                </div>
                <div class="stat-icon stat-icon-success text-teal-500">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave History Table -->
    <div class="bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden mt-6">
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

                            
                        </div>

                        <div x-show="subTab.leave === 'request'" style="display: none;" x-transition>
                            <h3 class="card-header text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                                Submit Leave Request
                            </h3>
                            <livewire:employee.leave-request-form />
                        </div>
                    </div>
                </div>

                <!-- PROFILE TAB -->
                <div x-show="activeTab === 'profile'" style="display: none;" x-transition class="space-y-6">
                    <div class="bg-white rounded-xl shadow-card p-6 border border-gray-100">
                        <h3 class="card-header text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                            My Profile
                        </h3>
                        <livewire:profile.employee-details />
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>