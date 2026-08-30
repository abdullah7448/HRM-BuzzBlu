<x-app-layout>
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
                    <div class="tab-nav-container">
                        <button
                            @click="activeTab = 'attendance'"
                            :class="activeTab === 'attendance' ? 'tab-btn-active' : 'tab-btn-inactive'"
                            class="tab-btn"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span>Attendance</span>
                        </button>

                        <button
                            @click="activeTab = 'leave'"
                            :class="activeTab === 'leave' ? 'tab-btn-active' : 'tab-btn-inactive'"
                            class="tab-btn"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Leave</span>
                        </button>

                        <button
                            @click="activeTab = 'profile'"
                            :class="activeTab === 'profile' ? 'tab-btn-active' : 'tab-btn-inactive'"
                            class="tab-btn"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Profile</span>
                        </button>
                    </div>
                </div>

                <!-- ATTENDANCE TAB -->
                <div x-show="activeTab === 'attendance'" x-transition class="space-y-6">
                    <div class="bg-white rounded-xl shadow-card p-6 border border-gray-100">
                        <div class="sub-tab-nav">
                            <button
                                @click="subTab.attendance = 'overview'"
                                :class="subTab.attendance === 'overview' ? 'sub-tab-btn-active' : 'sub-tab-btn-inactive'"
                                class="sub-tab-btn"
                            >
                                📊 Overview
                            </button>
                            <button
                                @click="subTab.attendance = 'punch'"
                                :class="subTab.attendance === 'punch' ? 'sub-tab-btn-active' : 'sub-tab-btn-inactive'"
                                class="sub-tab-btn"
                            >
                                🕐 Punch
                            </button>
                            <button
                                @click="subTab.attendance = 'report'"
                                :class="subTab.attendance === 'report' ? 'sub-tab-btn-active' : 'sub-tab-btn-inactive'"
                                class="sub-tab-btn"
                            >
                                📈 Report
                            </button>
                        </div>

                        <div x-show="subTab.attendance === 'overview'" x-transition>
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <div class="stat-card stat-card-success">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="stat-label">Present This Month</p>
                                            <p class="stat-value mt-2">18</p>
                                        </div>
                                        <div class="stat-icon stat-icon-success">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="stat-card stat-card-warning">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="stat-label">Absent This Month</p>
                                            <p class="stat-value mt-2">2</p>
                                        </div>
                                        <div class="stat-icon stat-icon-warning">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="stat-card stat-card-primary">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="stat-label">Attendance %</p>
                                            <p class="stat-value mt-2">90%</p>
                                        </div>
                                        <div class="stat-icon stat-icon-primary">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h.01a1 1 0 110 2H12zm-2 4a2 2 0 104 0 2 2 0 00-4 0zm0 6a2 2 0 104 0 2 2 0 00-4 0z" clip-rule="evenodd" /></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div x-show="subTab.attendance === 'punch'" x-transition>
                            <h3 class="card-header mb-4">
                                <span class="card-accent"></span>
                                Clock In/Out
                            </h3>
                            <livewire:employee.web-punch />
                        </div>

                        <div x-show="subTab.attendance === 'report'" x-transition>
                            <h3 class="card-header mb-4">
                                <span class="card-accent"></span>
                                Attendance Record
                            </h3>
                            <livewire:employee.attendance-report />
                        </div>
                    </div>
                </div>

                <!-- LEAVE TAB -->
                <div x-show="activeTab === 'leave'" x-transition class="space-y-6">
                    <div class="bg-white rounded-xl shadow-card p-6 border border-gray-100">
                        <div class="sub-tab-nav">
                            <button
                                @click="subTab.leave = 'stats'"
                                :class="subTab.leave === 'stats' ? 'sub-tab-btn-active' : 'sub-tab-btn-inactive'"
                                class="sub-tab-btn"
                            >
                                📊 Statistics
                            </button>
                            <button
                                @click="subTab.leave = 'request'"
                                :class="subTab.leave === 'request' ? 'sub-tab-btn-active' : 'sub-tab-btn-inactive'"
                                class="sub-tab-btn"
                            >
                                📝 Request
                            </button>
                        </div>

                        <div x-show="subTab.leave === 'stats'" x-transition>
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <div class="stat-card stat-card-primary">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="stat-label">Remaining Days</p>
                                            <p class="stat-value mt-2">12</p>
                                        </div>
                                        <div class="stat-icon stat-icon-primary">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M6 5a2 2 0 012-2h6a2 2 0 012 2v9a2 2 0 01-2 2H8a2 2 0 01-2-2V5z" /></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="stat-card stat-card-warning">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="stat-label">Used Days</p>
                                            <p class="stat-value mt-2">8</p>
                                        </div>
                                        <div class="stat-icon stat-icon-warning">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="stat-card stat-card-success">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="stat-label">Total Allocation</p>
                                            <p class="stat-value mt-2">20</p>
                                        </div>
                                        <div class="stat-icon stat-icon-success">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div x-show="subTab.leave === 'request'" x-transition>
                            <h3 class="card-header mb-4">
                                <span class="card-accent"></span>
                                Submit Leave Request
                            </h3>
                            <livewire:employee.leave-request-form />
                        </div>
                    </div>
                </div>

                <!-- PROFILE TAB -->
                <div x-show="activeTab === 'profile'" x-transition class="space-y-6">
                    <div class="bg-white rounded-xl shadow-card p-6 border border-gray-100">
                        <h3 class="card-header mb-4">
                            <span class="card-accent"></span>
                            My Profile
                        </h3>
                        <livewire:profile.employee-details />
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endpush
</x-app-layout>