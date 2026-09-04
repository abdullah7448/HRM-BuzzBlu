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
                    <div class="tab-nav-container flex flex-wrap gap-2 p-2 bg-white rounded-xl shadow-card border-b-4 border-primary-600">
                        <!-- Attendance Tab Button -->
                        <button
                            @click="activeTab = 'attendance'; $dispatch('tab-changed')"
                            :class="activeTab === 'attendance' ? 'bg-primary-600 text-white shadow-lg' : 'bg-gray-50 text-gray-700 hover:bg-gray-100'"
                            class="px-6 py-3 font-semibold text-sm rounded-lg transition-all duration-200 flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span>Attendance</span>
                        </button>

                        <!-- Leave Mgmt Tab Button -->
                        <button
                            @click="activeTab = 'leave'; $dispatch('tab-changed')"
                            :class="activeTab === 'leave' ? 'bg-primary-600 text-white shadow-lg' : 'bg-gray-50 text-gray-700 hover:bg-gray-100'"
                            class="px-6 py-3 font-semibold text-sm rounded-lg transition-all duration-200 flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Leave Mgmt</span>
                        </button>

                        <!-- Employees Tab Button -->
                        <button
                            @click="activeTab = 'employees'; $dispatch('tab-changed')"
                            :class="activeTab === 'employees' ? 'bg-primary-600 text-white shadow-lg' : 'bg-gray-50 text-gray-700 hover:bg-gray-100'"
                            class="px-6 py-3 font-semibold text-sm rounded-lg transition-all duration-200 flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span>Employees</span>
                        </button>

                        <!-- Recruitment Tab Button -->
                        <button
                            @click="activeTab = 'recruitment'; $dispatch('tab-changed')"
                            :class="activeTab === 'recruitment' ? 'bg-primary-600 text-white shadow-lg' : 'bg-gray-50 text-gray-700 hover:bg-gray-100'"
                            class="px-6 py-3 font-semibold text-sm rounded-lg transition-all duration-200 flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            <span>Recruitment</span>
                        </button>
                    </div>
                </div>

                <!-- ==================== ATTENDANCE TAB ==================== -->
                <div x-show="activeTab === 'attendance'" x-transition class="space-y-6">
                    <div class="bg-white rounded-xl shadow-card p-6 border border-gray-100">
                        <div class="sub-tab-nav flex gap-2 pb-4 mb-6 border-b border-gray-100">
                            <button
                                @click="subTab.attendance = 'overview'"
                                :class="subTab.attendance === 'overview' ? 'bg-primary-50 text-primary-700 font-bold border-b-2 border-primary-600' : 'text-gray-500 hover:text-gray-700'"
                                class="px-4 py-2 text-sm rounded-t-lg transition flex items-center gap-2"
                            >
                                📊 Overview
                            </button>
                            <button
                                @click="subTab.attendance = 'mypunch'"
                                :class="subTab.attendance === 'mypunch' ? 'bg-primary-50 text-primary-700 font-bold border-b-2 border-primary-600' : 'text-gray-500 hover:text-gray-700'"
                                class="px-4 py-2 text-sm rounded-t-lg transition flex items-center gap-2"
                            >
                                🕐 My Punch
                            </button>
                            <button
                                @click="subTab.attendance = 'report'"
                                :class="subTab.attendance === 'report' ? 'bg-primary-50 text-primary-700 font-bold border-b-2 border-primary-600' : 'text-gray-500 hover:text-gray-700'"
                                class="px-4 py-2 text-sm rounded-t-lg transition flex items-center gap-2"
                            >
                                📈 Reports
                            </button>
                        </div>

                        <!-- Overview Sub-tab: রিয়েল-টাইম ডায়নামিক স্ট্যাটস ও ক্লিক করলে লিস্ট -->
                        <div x-show="subTab.attendance === 'overview'" x-transition>
                            <livewire:hr.attendance-stats />
                        </div>

                        <!-- My Punch Sub-tab -->
                        <div x-show="subTab.attendance === 'mypunch'" x-transition>
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <span class="w-1 h-6 bg-primary-600 rounded"></span>
                                Web Punch
                            </h3>
                            <livewire:employee.web-punch />
                        </div>

                        <!-- Reports Sub-tab -->
                        <div x-show="subTab.attendance === 'report'" x-transition>
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <span class="w-1 h-6 bg-primary-600 rounded"></span>
                                Attendance Report
                            </h3>
                            <livewire:employee.attendance-report />
                        </div>
                    </div>
                </div>

                <!-- ==================== LEAVE MANAGEMENT TAB ==================== -->
                <div x-show="activeTab === 'leave'" x-transition class="space-y-6">
                    <div class="bg-white rounded-xl shadow-card p-6 border border-gray-100">
                        <div class="sub-tab-nav flex gap-2 pb-4 mb-6 border-b border-gray-100">
                            <button
                                @click="subTab.leave = 'approvals'"
                                :class="subTab.leave === 'approvals' ? 'bg-primary-50 text-primary-700 font-bold border-b-2 border-primary-600' : 'text-gray-500 hover:text-gray-700'"
                                class="px-4 py-2 text-sm rounded-t-lg transition flex items-center gap-2"
                            >
                                ✅ Approvals
                            </button>
                            <button
                                @click="subTab.leave = 'request'"
                                :class="subTab.leave === 'request' ? 'bg-primary-50 text-primary-700 font-bold border-b-2 border-primary-600' : 'text-gray-500 hover:text-gray-700'"
                                class="px-4 py-2 text-sm rounded-t-lg transition flex items-center gap-2"
                            >
                                📝 My Request
                            </button>
                        </div>

                        <!-- Approvals Sub-tab: কোনো স্ট্যাটিক কার্ড নেই, ডিপার্টমেন্ট ফিল্টারসহ টেবিল -->
                        <div x-show="subTab.leave === 'approvals'" x-transition>
                            <livewire:hr.leave-approvals />
                        </div>

                        <!-- Request Sub-tab -->
                        <div x-show="subTab.leave === 'request'" x-transition>
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <span class="w-1 h-6 bg-primary-600 rounded"></span>
                                Submit Leave Request
                            </h3>
                            <livewire:employee.leave-request-form />
                        </div>
                    </div>
                </div>

                <!-- ==================== EMPLOYEES TAB ==================== -->
                <div x-show="activeTab === 'employees'" x-transition class="space-y-6">
                    <div class="bg-white rounded-xl shadow-card p-6 border border-gray-100">
                        <div class="sub-tab-nav flex gap-2 pb-4 mb-6 border-b border-gray-100">
                            <button
                                @click="subTab.employees = 'list'"
                                :class="subTab.employees === 'list' ? 'bg-primary-50 text-primary-700 font-bold border-b-2 border-primary-600' : 'text-gray-500 hover:text-gray-700'"
                                class="px-4 py-2 text-sm rounded-t-lg transition flex items-center gap-2"
                            >
                                👥 Directory
                            </button>
                            <button
                                @click="subTab.employees = 'stats'"
                                :class="subTab.employees === 'stats' ? 'bg-primary-50 text-primary-700 font-bold border-b-2 border-primary-600' : 'text-gray-500 hover:text-gray-700'"
                                class="px-4 py-2 text-sm rounded-t-lg transition flex items-center gap-2"
                            >
                                📊 Statistics
                            </button>
                        </div>

                        <!-- Directory Sub-tab -->
                        <div x-show="subTab.employees === 'list'" x-transition>
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <span class="w-1 h-6 bg-primary-600 rounded"></span>
                                Employee Directory
                            </h3>
                            <livewire:hr.employee-list />
                        </div>

                        <!-- Statistics Sub-tab -->
                        <div x-show="subTab.employees === 'stats'" x-transition>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm border-t-4 border-blue-500">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-xs font-bold text-gray-500 uppercase">Total Staff</p>
                                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ \App\Models\User::count() }}</p>
                                        </div>
                                        <div class="p-2 bg-blue-50 text-blue-600 rounded-full">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v-1h8v1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" /></svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm border-t-4 border-green-500">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-xs font-bold text-gray-500 uppercase">Active</p>
                                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ \App\Models\User::where('status', 'active')->count() ?: \App\Models\User::count() }}</p>
                                        </div>
                                        <div class="p-2 bg-green-50 text-green-600 rounded-full">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm border-t-4 border-yellow-500">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-xs font-bold text-gray-500 uppercase">On Leave</p>
                                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ \App\Models\LeaveRequest::where('status', 'approved')->whereDate('start_date', '<=', now())->whereDate('end_date', '>=', now())->count() }}</p>
                                        </div>
                                        <div class="p-2 bg-yellow-50 text-yellow-600 rounded-full">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm border-t-4 border-gray-400">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-xs font-bold text-gray-500 uppercase">Departments</p>
                                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ \App\Models\Department::count() }}</p>
                                        </div>
                                        <div class="p-2 bg-gray-50 text-gray-600 rounded-full">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" /></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================== RECRUITMENT TAB ==================== -->
                <div x-show="activeTab === 'recruitment'" x-transition class="space-y-6">
                    <div class="bg-white rounded-xl shadow-card p-6 border border-gray-100">
                        <div class="sub-tab-nav flex gap-2 pb-4 mb-6 border-b border-gray-100">
                            <button
                                @click="subTab.recruitment = 'postings'"
                                :class="subTab.recruitment === 'postings' ? 'bg-primary-50 text-primary-700 font-bold border-b-2 border-primary-600' : 'text-gray-500 hover:text-gray-700'"
                                class="px-4 py-2 text-sm rounded-t-lg transition flex items-center gap-2"
                            >
                                💼 Job Postings
                            </button>
                            <button
                                @click="subTab.recruitment = 'evaluate'"
                                :class="subTab.recruitment === 'evaluate' ? 'bg-primary-50 text-primary-700 font-bold border-b-2 border-primary-600' : 'text-gray-500 hover:text-gray-700'"
                                class="px-4 py-2 text-sm rounded-t-lg transition flex items-center gap-2"
                            >
                                📋 Evaluations
                            </button>
                        </div>

                        <!-- Job Postings Sub-tab -->
                        <div x-show="subTab.recruitment === 'postings'" x-transition>
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <span class="w-1 h-6 bg-primary-600 rounded"></span>
                                Job Management
                            </h3>
                            <livewire:ats.candidate-manager />
                        </div>

                        <!-- Evaluations Sub-tab -->
                        <div x-show="subTab.recruitment === 'evaluate'" x-transition>
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <span class="w-1 h-6 bg-primary-600 rounded"></span>
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