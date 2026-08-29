<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('HR Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Personal Web Punch for HR -->
            <livewire:employee.web-punch />

            <!-- Administrative Controls: Company-wide Leave Approvals -->
            <livewire:hr.leave-approvals />

            <!-- Administrative Controls: Company-wide Employee Directory -->
            <livewire:hr.employee-list />

            <!-- Personal Leave Requests for HR -->
            <livewire:employee.leave-request-form />
            
        </div>
    </div>
</x-app-layout>