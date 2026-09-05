<x-app-layout>
    <!-- Premium Header -->
    <x-dashboard-header 
        title="Department Head Dashboard"
        description="Manage your department's attendance, approvals, and team members"
        icon='<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v-1h8v1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" stroke="currentColor" stroke-width="1.5" fill="none"></path></svg>'
    />

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Tab Navigation Container -->
            <div x-data="{ activeTab: 'attendance' }" class="space-y-6">
                
                <!-- Tab Header with Navigation -->
                <div class="bg-white rounded-xl shadow-card border-b-4 border-primary-600">
                    <div class="flex flex-wrap gap-2 p-2">
                        <!-- Attendance Tab -->
                        <button
                            @click="activeTab = 'attendance'"
                            :class="{
                                'bg-primary-600 text-white shadow-lg': activeTab === 'attendance',
                                'bg-gray-50 text-gray-700 hover:bg-gray-100': activeTab !== 'attendance'
                            }"
                            class="px-6 py-3 font-semibold text-sm rounded-lg transition-all duration-200 flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.5 1.5H5.75A2.25 2.25 0 003.5 3.75v12.5A2.25 2.25 0 005.75 18.5h8.5a2.25 2.25 0 002.25-2.25V6.75" stroke="currentColor" stroke-width="1.5" fill="none"></path></svg>
                            My Attendance
                        </button>

                        <!-- Leave Tab -->
                        <button
                            @click="activeTab = 'leave'"
                            :class="{
                                'bg-primary-600 text-white shadow-lg': activeTab === 'leave',
                                'bg-gray-50 text-gray-700 hover:bg-gray-100': activeTab !== 'leave'
                            }"
                            class="px-6 py-3 font-semibold text-sm rounded-lg transition-all duration-200 flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V4z" stroke="currentColor" stroke-width="1.5" fill="none"></path></svg>
                            Leave Approvals
                        </button>

                        <!-- Team Tab -->
                        <button
                            @click="activeTab = 'team'"
                            :class="{
                                'bg-primary-600 text-white shadow-lg': activeTab === 'team',
                                'bg-gray-50 text-gray-700 hover:bg-gray-100': activeTab !== 'team'
                            }"
                            class="px-6 py-3 font-semibold text-sm rounded-lg transition-all duration-200 flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v-1h8v1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" stroke="currentColor" stroke-width="1.5" fill="none"></path></svg>
                            Team Members
                        </button>

                        <!-- Recruitment Tab -->
                        <button
                            @click="activeTab = 'recruitment'"
                            :class="{
                                'bg-primary-600 text-white shadow-lg': activeTab === 'recruitment',
                                'bg-gray-50 text-gray-700 hover:bg-gray-100': activeTab !== 'recruitment'
                            }"
                            class="px-6 py-3 font-semibold text-sm rounded-lg transition-all duration-200 flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92z" stroke="currentColor" stroke-width="1.5" fill="none"></path></svg>
                            Candidates
                        </button>
                    </div>
                </div>

                <!-- ATTENDANCE TAB -->
                <div x-show="activeTab === 'attendance'" x-transition class="space-y-6">
                   
                    <livewire:department-head.attendance-stats />

                    <div class="bg-white rounded-xl shadow-card p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <span class="w-1 h-6 bg-primary-600 rounded"></span>
                            My Attendance
                        </h3>
                        <livewire:employee.web-punch />
                    </div>

                    <div class="bg-white rounded-xl shadow-card p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <span class="w-1 h-6 bg-primary-600 rounded"></span>
                            Attendance Report
                        </h3>
                        <livewire:employee.attendance-report />
                    </div>
                </div>

                <!-- LEAVE APPROVALS TAB -->
                <div x-show="activeTab === 'leave'" x-transition class="space-y-6">
                  
                    <div class="bg-white rounded-xl shadow-card p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <span class="w-1 h-6 bg-primary-600 rounded"></span>
                            Department Leave Requests
                        </h3>
                        <livewire:department-head.leave-approvals />
                    </div>

                    <div class="bg-white rounded-xl shadow-card p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <span class="w-1 h-6 bg-primary-600 rounded"></span>
                            My Leave Request
                        </h3>
                        <livewire:employee.leave-request-form />
                    </div>
                </div>

                <!-- TEAM MEMBERS TAB -->
                <div x-show="activeTab === 'team'" x-transition class="space-y-6">
                    <div class="bg-white rounded-xl shadow-card p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <span class="w-1 h-6 bg-primary-600 rounded"></span>
                            Department Members
                        </h3>
                        <livewire:department-head.employee-list />
                    </div>
                </div>

                <!-- RECRUITMENT TAB -->
                <div x-show="activeTab === 'recruitment'" x-transition class="space-y-6">
                    <div class="bg-white rounded-xl shadow-card p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <span class="w-1 h-6 bg-primary-600 rounded"></span>
                            Candidate Evaluations
                        </h3>
                        <livewire:ats.application-evaluator />
                    </div>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>