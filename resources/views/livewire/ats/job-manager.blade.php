<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('ATS: Recruitment & Job Postings') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        @if($successMessage)
            <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-md">
                {{ $successMessage }}
            </div>
        @endif

        <!-- Create/Edit Job Form -->
        <div class="bg-white p-6 border border-gray-200 rounded-lg shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $isEditMode ? 'Edit Job Posting' : 'Create New Job Posting' }}</h3>
            
            <form wire:submit.prevent="saveJob">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Department</label>
                        <select wire:model.live="department_id" class="mt-1 block w-full border-gray-300 rounded-md">
                            <option value="">Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Designation (Role)</label>
                        <select wire:model="designation_id" class="mt-1 block w-full border-gray-300 rounded-md">
                            <option value="">Select Designation</option>
                            @foreach($designations as $desig)
                                <option value="{{ $desig->id }}">{{ $desig->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Custom Title</label>
                        <input type="text" wire:model="title" class="mt-1 block w-full border-gray-300 rounded-md" placeholder="e.g. Senior Editor (Night)">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Job Description</label>
                    <textarea wire:model="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Application Deadline</label>
                        <input type="date" wire:model="deadline" class="mt-1 block w-full border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select wire:model="status" class="mt-1 block w-full border-gray-300 rounded-md">
                            <option value="open">Open</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                </div>

                <hr class="mb-6">

                <!-- Dynamic Questions -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-md font-bold text-gray-800">Dynamic Screening Questions</h4>
                        <button type="button" wire:click="addQuestion" class="px-3 py-1 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                            + Add Question
                        </button>
                    </div>

                    @foreach($questions as $index => $question)
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-md mb-3 relative">
                            <button type="button" wire:click="removeQuestion({{ $index }})" class="absolute top-2 right-2 text-red-500 hover:text-red-700 font-bold">
                                &times;
                            </button>
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <div class="md:col-span-5">
                                    <label class="block text-xs font-medium text-gray-700">Question Text</label>
                                    <input type="text" wire:model="questions.{{ $index }}.question_text" class="mt-1 block w-full border-gray-300 rounded-md text-sm">
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-medium text-gray-700">Type</label>
                                    <select wire:model="questions.{{ $index }}.question_type" class="mt-1 block w-full border-gray-300 rounded-md text-sm">
                                        <option value="text">Short Text</option>
                                        <option value="yes_no">Yes / No</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-gray-700">Expected Answer</label>
                                    <input type="text" wire:model="questions.{{ $index }}.expected_answer" class="mt-1 block w-full border-gray-300 rounded-md text-sm">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-gray-700">Match Points</label>
                                    <input type="number" wire:model="questions.{{ $index }}.points" class="mt-1 block w-full border-gray-300 rounded-md text-sm" min="1">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end space-x-3">
                    @if($isEditMode)
                        <button type="button" wire:click="cancelEdit" class="px-6 py-2 border rounded shadow text-gray-700">Cancel Edit</button>
                    @endif
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-bold rounded shadow hover:bg-blue-700 transition">
                        {{ $isEditMode ? 'Update Job & Questions' : 'Publish Job & Questions' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Active Jobs List -->
        <div class="bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">Current Job Postings</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title & Designation</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($jobs as $job)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ $job->title }}</div>
                                    <div class="text-sm text-gray-500">{{ $job->designation->name ?? 'N/A' }} - {{ $job->department->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $job->status === 'open' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($job->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="editJob({{ $job->id }})" class="text-indigo-600 hover:text-indigo-900 underline">Edit Details & Qs</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>