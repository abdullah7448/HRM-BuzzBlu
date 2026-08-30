<x-app-layout>
    <!-- Premium Header -->
    <x-dashboard-header 
        title="HR Dashboard"
        description="Manage employees, attendance, leave requests, and recruitment"
        icon='<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M10.5 1.5H5.75A2.25 2.25 0 003.5 3.75v12.5A2.25 2.25 0 005.75 18.5h8.5a2.25 2.25 0 002.25-2.25V6.75m-9.5-3.5v3.5m4.25-3.5v3.5M3.5 8h13M5.375 13.75h1.5M5.375 15.875h1.5M9.875 13.75h1.5M9.875 15.875h1.5M14.375 13.75h1.5M14.375 15.875h1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"></path></svg>'
    />

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Tab Navigation Container -->
            <div x-data="{ activeTab: 'attendance', subTab: { attendance: 'overview', leave: 'approvals', employees: 'list', recruitment: 'postings' } }" class="space-y-6">
                
                <!-- Main Tab Navigation -->
                <div class="tab-nav">
                    <div class="tab-nav-container">
                        <button
                            @click="activeTab = 'attendance'; $dispatch('tab-changed')"
                            :class="activeTab === 'attendance' ? 'tab-btn-active' : 'tab-btn-inactive'"
                            class="tab-btn"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span>Attendance</span>
                        </button>

                        <button
                            @click="activeTab = 'leave'; $dispatch('tab-changed')"
                            :class="activeTab === 'leave' ? 'tab-btn-active' : 'tab-btn-inactive'"
                            class="tab-btn"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Leave Mgmt</span>
                        </button>

                        <button
                            @click="activeTab = 'employees'; $dispatch('tab-changed')"
                            :class="activeTab === 'employees' ? 'tab-btn-active' : 'tab-btn-inactive'"
                            class="tab-btn"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span>Employees</span>
                        </button>

                        <button
                            @click="activeTab = 'recruitment'; $dispatch('tab-changed')"
                            :class="activeTab === 'recruitment' ? 'tab-btn-active' : 'tab-btn-inactive'"
                            class="tab-btn"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            <span>Recruitment</span>
                        </button>
                    </div>
                </div>

                <!-- ==================== ATTENDANCE TAB ==================== -->
                <div x-show="activeTab === 'attendance'" x-transition class="space-y-6">
                    <!-- Sub-tabs for Attendance -->
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
                                @click="subTab.attendance = 'mypunch'"
                                :class="subTab.attendance === 'mypunch' ? 'sub-tab-btn-active' : 'sub-tab-btn-inactive'"
                                class="sub-tab-btn"
                            >
                                🕐 My Punch
                            </button>
                            <button
                                @click="subTab.attendance = 'report'"
                                :class="subTab.attendance === 'report' ? 'sub-tab-btn-active' : 'sub-tab-btn-inactive'"
                                class="sub-tab-btn"
                            >
                                📈 Reports
                            </button>
                        </div>

                        <!-- Overview Sub-tab -->
                        <div x-show="subTab.attendance === 'overview'" x-transition>
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <div class="stat-card stat-card-success">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="stat-label">Present Today</p>
                                            <p class="stat-value mt-2">156</p>
                                        </div>
                                        <div class="stat-icon stat-icon-success">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="stat-card stat-card-warning">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="stat-label">Late/Absent</p>
                                            <p class="stat-value mt-2">12</p>
                                        </div>
                                        <div class="stat-icon stat-icon-warning">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="stat-card stat-card-primary">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="stat-label">Total Employees</p>
                                            <p class="stat-value mt-2">168</p>
                                        </div>
                                        <div class="stat-icon stat-icon-primary">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v-1h8v1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" /></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- My Punch Sub-tab -->
                        <div x-show="subTab.attendance === 'mypunch'" x-transition>
                            <h3 class="card-header mb-4">
                                <span class="card-accent"></span>
                                Web Punch
                            </h3>
                            <livewire:employee.web-punch />
                        </div>

                        <!-- Reports Sub-tab -->
                        <div x-show="subTab.attendance === 'report'" x-transition>
                            <h3 class="card-header mb-4">
                                <span class="card-accent"></span>
                                Attendance Report
                            </h3>
                            <livewire:employee.attendance-report />
                        </div>
                    </div>
                </div>

                <!-- ==================== LEAVE MANAGEMENT TAB ==================== -->
                <div x-show="activeTab === 'leave'" x-transition class="space-y-6">
                    <!-- Sub-tabs for Leave -->
                    <div class="bg-white rounded-xl shadow-card p-6 border border-gray-100">
                        <div class="sub-tab-nav">
                            <button
                                @click="subTab.leave = 'approvals'"
                                :class="subTab.leave === 'approvals' ? 'sub-tab-btn-active' : 'sub-tab-btn-inactive'"
                                class="sub-tab-btn"
                            >
                                ✅ Approvals
                            </button>
                            <button
                                @click="subTab.leave = 'request'"
                                :class="subTab.leave === 'request' ? 'sub-tab-btn-active' : 'sub-tab-btn-inactive'"
                                class="sub-tab-btn"
                            >
                                📝 My Request
                            </button>
                        </div>

                        <!-- Approvals Sub-tab -->
                        <div x-show="subTab.leave === 'approvals'" x-transition>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                                <div class="stat-card stat-card-warning">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="stat-label">Pending Requests</p>
                                            <p class="stat-value mt-2">8</p>
                                        </div>
                                        <div class="stat-icon stat-icon-warning">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" /></svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="stat-card stat-card-success">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="stat-label">Approved This Month</p>
                                            <p class="stat-value mt-2">24</p>
                                        </div>
                                        <div class="stat-icon stat-icon-success">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h3 class="card-header mb-4">
                                <span class="card-accent"></span>
                                Leave Approvals
                            </h3>
                            <livewire:hr.leave-approvals />
                        </div>

                        <!-- Request Sub-tab -->
                        <div x-show="subTab.leave === 'request'" x-transition>
                            <h3 class="card-header mb-4">
                                <span class="card-accent"></span>
                                Submit Leave Request
                            </h3>
                            <livewire:employee.leave-request-form />
                        </div>
                    </div>
                </div>

                <!-- ==================== EMPLOYEES TAB ==================== -->
                <div x-show="activeTab === 'employees'" x-transition class="space-y-6">
                    <!-- Sub-tabs for Employees -->
                    <div class="bg-white rounded-xl shadow-card p-6 border border-gray-100">
                        <div class="sub-tab-nav">
                            <button
                                @click="subTab.employees = 'list'"
                                :class="subTab.employees === 'list' ? 'sub-tab-btn-active' : 'sub-tab-btn-inactive'"
                                class="sub-tab-btn"
                            >
                                👥 Directory
                            </button>
                            <button
                                @click="subTab.employees = 'stats'"
                                :class="subTab.employees === 'stats' ? 'sub-tab-btn-active' : 'sub-tab-btn-inactive'"
                                class="sub-tab-btn"
                            >
                                📊 Statistics
                            </button>
                        </div>

                        <!-- Directory Sub-tab -->
                        <div x-show="subTab.employees === 'list'" x-transition>
                            <h3 class="card-header mb-4">
                                <span class="card-accent"></span>
                                Employee Directory
                            </h3>
                            <livewire:hr.employee-list />
                        </div>

                        <!-- Statistics Sub-tab -->
                        <div x-show="subTab.employees === 'stats'" x-transition>
                            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                                <div class="stat-card stat-card-primary">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="stat-label">Total Staff</p>
                                            <p class="stat-value mt-2">168</p>
                                        </div>
                                        <div class="stat-icon stat-icon-primary">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v-1h8v1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" /></svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="stat-card stat-card-success">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="stat-label">Active</p>
                                            <p class="stat-value mt-2">165</p>
                                        </div>
                                        <div class="stat-icon stat-icon-success">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="stat-card stat-card-warning">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="stat-label">On Leave</p>
                                            <p class="stat-value mt-2">3</p>
                                        </div>
                                        <div class="stat-icon stat-icon-warning">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="stat-card stat-card-secondary">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="stat-label">Inactive</p>
                                            <p class="stat-value mt-2">0</p>
                                        </div>
                                        <div class="stat-icon stat-icon-secondary">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 2.523a6 6 0 008.367 8.367z" clip-rule="evenodd" /></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================== RECRUITMENT TAB ==================== -->
                <div x-show="activeTab === 'recruitment'" x-transition class="space-y-6">
                    <!-- Sub-tabs for Recruitment -->
                    <div class="bg-white rounded-xl shadow-card p-6 border border-gray-100">
                        <div class="sub-tab-nav">
                            <button
                                @click="subTab.recruitment = 'postings'"
                                :class="subTab.recruitment === 'postings' ? 'sub-tab-btn-active' : 'sub-tab-btn-inactive'"
                                class="sub-tab-btn"
                            >
                                💼 Job Postings
                            </button>
                            <button
                                @click="subTab.recruitment = 'evaluate'"
                                :class="subTab.recruitment === 'evaluate' ? 'sub-tab-btn-active' : 'sub-tab-btn-inactive'"
                                class="sub-tab-btn"
                            >
                                📋 Evaluations
                            </button>
                        </div>

                        <!-- Job Postings Sub-tab -->
                        <div x-show="subTab.recruitment === 'postings'" x-transition>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                                <div class="stat-card stat-card-secondary">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="stat-label">Total Applications</p>
                                            <p class="stat-value mt-2">42</p>
                                        </div>
                                        <div class="stat-icon stat-icon-secondary">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5z" /></svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="stat-card stat-card-success">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="stat-label">Open Positions</p>
                                            <p class="stat-value mt-2">6</p>
                                        </div>
                                        <div class="stat-icon stat-icon-success">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h3 class="card-header mb-4">
                                <span class="card-accent"></span>
                                Add Job Postings
                            </h3>
                            <livewire:ats.candidate-manager />
                        </div>

                        <!-- Evaluations Sub-tab -->
                        <div x-show="subTab.recruitment === 'evaluate'" x-transition>
                            <h3 class="card-header mb-4">
                                <span class="card-accent"></span>
                                Candidate Evaluations
                            </h3>
                            <livewire:ats.application-evaluator />
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endpush
</x-app-layout>