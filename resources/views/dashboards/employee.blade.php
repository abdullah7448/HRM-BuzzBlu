<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Employee Workspace') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Web Punch Component -->
            <livewire:employee.web-punch />
            <livewire:employee.attendance-report />
            <!-- Leave Request Component -->
            <livewire:employee.leave-request-form />
        </div>
    </div>
</x-app-layout>