<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Super Admin Dashboard - Company Overview') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Admin's Own Web Punch -->
            <livewire:employee.web-punch />

            <!-- System-Wide Master Leave Approvals -->
            <livewire:super-admin.master-leave-approvals />

            <!-- System-Wide Employee Directory (Reusing HR's Component for Full Access) -->
            <div class="bg-white p-6 border border-gray-200 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Master Employee Directory</h3>
                <livewire:hr.employee-list /> 
            </div>

            <!-- Admin's Own Leave Request -->
            <livewire:employee.leave-request-form />

        </div>
    </div>
</x-app-layout>