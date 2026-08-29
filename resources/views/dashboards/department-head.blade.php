<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Department Head Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Personal Web Punch for the Head -->
            <livewire:employee.web-punch />
            <livewire:employee.attendance-report />

            <!-- Managerial Controls: Department Leave Approvals -->
            <livewire:department-head.leave-approvals />
            
            <!-- Managerial Controls: Department Employee Directory -->
            <livewire:department-head.employee-list />

            <!-- Personal Leave Requests for the Head -->
            <livewire:employee.leave-request-form />

        </div>
    </div>
</x-app-layout>