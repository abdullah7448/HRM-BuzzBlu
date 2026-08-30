<div class="bg-white p-6 border border-gray-200 rounded-lg shadow-sm mt-6 mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4">Add New Candidate (ATS Pipeline)</h3>
    
    @if($successMessage)
        <div class="p-4 mb-6 bg-green-50 border border-green-200 rounded-md">
            <p class="text-green-800 font-medium">{{ $successMessage }}</p>
            @if($generatedPassword)
            <div class="mt-2 p-3 bg-white border border-gray-200 rounded text-sm text-gray-800 font-mono">
                <p><strong>Email:</strong> Hidden for security</p>
                <p><strong>Password:</strong> <span class="text-red-600 font-bold">{{ $generatedPassword }}</span></p>
            </div>
            @endif
        </div>
    @endif

    <form wire:submit.prevent="createCandidate" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <input type="text" wire:model="name" placeholder="Candidate Name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <input type="email" wire:model="email" placeholder="Email Address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <select wire:model="job_posting_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">Select a Job</option>
                    @foreach($openJobs as $job)
                        <option value="{{ $job->id }}">{{ $job->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow hover:bg-indigo-700">Create Candidate</button>
        </div>
    </form>

    <hr class="my-6">

    <h4 class="text-md font-bold text-gray-800 mb-4">Existing Candidates Directory</h4>
    <div class="overflow-x-auto max-h-64">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($candidates as $candidate)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $candidate->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $candidate->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="editCandidate({{ $candidate->id }})" class="text-indigo-600 hover:text-indigo-900 underline">Edit / Reset Pass</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No candidates found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- HR Edit Candidate Modal -->
    @if($isEditModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-gray-500 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Edit Candidate: {{ $edit_name }}</h3>
            <form wire:submit.prevent="updateCandidate" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" wire:model="edit_name" class="mt-1 block w-full border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" wire:model="edit_email" class="mt-1 block w-full border-gray-300 rounded-md">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Job Assignment</label>
                    <select wire:model="edit_job_posting_id" class="mt-1 block w-full border-gray-300 rounded-md">
                        @foreach($openJobs as $job)
                            <option value="{{ $job->id }}">{{ $job->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="bg-gray-50 p-4 border rounded-md mt-4">
                    <label class="block text-sm font-bold text-red-600">Force Password Reset</label>
                    <input type="text" wire:model="hr_new_password" placeholder="Leave blank to keep current" class="mt-1 block w-full border-gray-300 rounded-md">
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" wire:click="closeEditModal" class="px-4 py-2 border rounded-md">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>