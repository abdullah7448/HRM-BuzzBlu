<x-app-layout>
    <!-- Premium Header -->
    <x-dashboard-header 
        title="Super Admin Dashboard"
        description="Complete system overview, all employees, and master controls"
        icon='<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M10.5 1.5H5.75A2.25 2.25 0 003.5 3.75v12.5A2.25 2.25 0 005.75 18.5h8.5a2.25 2.25 0 002.25-2.25V6.75" stroke="currentColor" stroke-width="1.5" fill="none"></path></svg>'
    />

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Tab Navigation Container -->
            <div x-data="{ activeTab: 'dashboard' }" class="space-y-6">
                
                <!-- Tab Header with Navigation -->
                <div class="bg-white rounded-xl shadow-card border-b-4 border-primary-600">
                    <div class="flex flex-wrap gap-2 p-2 overflow-x-auto">
                        <!-- Overview Tab -->
                        <button
                            @click="activeTab = 'dashboard'"
                            :class="{
                                'bg-primary-600 text-white shadow-lg': activeTab === 'dashboard',
                                'bg-gray-50 text-gray-700 hover:bg-gray-100': activeTab !== 'dashboard'
                            }"
                            class="px-6 py-3 font-semibold text-sm rounded-lg transition-all duration-200 flex items-center gap-2 whitespace-nowrap"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" stroke="currentColor" stroke-width="1.5" fill="none"></path></svg>
                            Overview
                        </button>

                        <!-- Leave Tab -->
                        <button
                            @click="activeTab = 'leave'"
                            :class="{
                                'bg-primary-600 text-white shadow-lg': activeTab === 'leave',
                                'bg-gray-50 text-gray-700 hover:bg-gray-100': activeTab !== 'leave'
                            }"
                            class="px-6 py-3 font-semibold text-sm rounded-lg transition-all duration-200 flex items-center gap-2 whitespace-nowrap"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V4z" stroke="currentColor" stroke-width="1.5" fill="none"></path></svg>
                            Master Leave
                        </button>

                        <!-- Employees Tab -->
                        <button
                            @click="activeTab = 'employees'"
                            :class="{
                                'bg-primary-600 text-white shadow-lg': activeTab === 'employees',
                                'bg-gray-50 text-gray-700 hover:bg-gray-100': activeTab !== 'employees'
                            }"
                            class="px-6 py-3 font-semibold text-sm rounded-lg transition-all duration-200 flex items-center gap-2 whitespace-nowrap"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v-1h8v1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" stroke="currentColor" stroke-width="1.5" fill="none"></path></svg>
                            All Employees
                        </button>

                        <!-- My Account Tab -->
                        <button
                            @click="activeTab = 'account'"
                            :class="{
                                'bg-primary-600 text-white shadow-lg': activeTab === 'account',
                                'bg-gray-50 text-gray-700 hover:bg-gray-100': activeTab !== 'account'
                            }"
                            class="px-6 py-3 font-semibold text-sm rounded-lg transition-all duration-200 flex items-center gap-2 whitespace-nowrap"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 10a3 3 0 100-6 3 3 0 000 6zm0 2c-3.314 0-6 1.343-6 3v1h12v-1c0-1.657-2.686-3-6-3z" stroke="currentColor" stroke-width="1.5" fill="none"></path></svg>
                            My Account
                        </button>
                    </div>
                </div>

                <!-- DASHBOARD OVERVIEW TAB -->
                <div x-show="activeTab === 'dashboard'" x-transition class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-white rounded-xl shadow-card p-6 border-t-4 border-primary-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm font-semibold uppercase tracking-wide">Total Employees</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2">1,428</p>
                                </div>
                                <div class="p-3 bg-primary-100 rounded-lg">
                                    <svg class="w-6 h-6 text-primary-600" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v-1h8v1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" /></svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-card p-6 border-t-4 border-success-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm font-semibold uppercase tracking-wide">Present Today</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2">1,156</p>
                                </div>
                                <div class="p-3 bg-success-100 rounded-lg">
                                    <svg class="w-6 h-6 text-success-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-card p-6 border-t-4 border-warning-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm font-semibold uppercase tracking-wide">Absent/Late</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2">272</p>
                                </div>
                                <div class="p-3 bg-warning-100 rounded-lg">
                                    <svg class="w-6 h-6 text-warning-600" fill="currentColor" viewBox="0 0 20 20"><path d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-card p-6 border-t-4 border-secondary-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm font-semibold uppercase tracking-wide">Departments</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2">12</p>
                                </div>
                                <div class="p-3 bg-secondary-100 rounded-lg">
                                    <svg class="w-6 h-6 text-secondary-600" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM15 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2zM5 13a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM15 13a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2z" /></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-card p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <span class="w-1 h-6 bg-primary-600 rounded"></span>
                            My Attendance
                        </h3>
                        <livewire:employee.web-punch />
                    </div>
                </div>

                <!-- LEAVE TAB -->
                <div x-show="activeTab === 'leave'" x-transition class="space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-white rounded-xl shadow-card p-6 border-t-4 border-warning-500">
                            <p class="text-gray-600 text-sm font-semibold uppercase tracking-wide">Pending Approvals</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">23</p>
                        </div>
                        <div class="bg-white rounded-xl shadow-card p-6 border-t-4 border-success-500">
                            <p class="text-gray-600 text-sm font-semibold uppercase tracking-wide">Approved Today</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">18</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-card p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <span class="w-1 h-6 bg-primary-600 rounded"></span>
                            Master Leave Approvals
                        </h3>
                        <livewire:super-admin.master-leave-approvals />
                    </div>
                </div>

                <!-- EMPLOYEES TAB -->
                <div x-show="activeTab === 'employees'" x-transition class="space-y-6">
                    <div class="bg-white rounded-xl shadow-card p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <span class="w-1 h-6 bg-primary-600 rounded"></span>
                            Master Employee Directory
                        </h3>
                        <livewire:hr.employee-list />
                    </div>
                </div>

                <!-- MY ACCOUNT TAB -->
                <div x-show="activeTab === 'account'" x-transition class="space-y-6">
                    <div class="bg-white rounded-xl shadow-card p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <span class="w-1 h-6 bg-primary-600 rounded"></span>
                            My Leave Request
                        </h3>
                        <livewire:employee.leave-request-form />
                    </div>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>