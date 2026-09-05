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
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" wire:model="name" placeholder="Candidate Name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @error('name') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" wire:model="email" placeholder="Email Address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @error('email') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" wire:model="phone" placeholder="Phone Number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @error('phone') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Position</label>
                <select wire:model="job_posting_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">Select a Job</option>
                    @foreach($openJobs as $job)
                        <option value="{{ $job->id }}">{{ $job->title }}</option>
                    @endforeach
                </select>
                @error('job_posting_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="flex justify-end">
            <button type="submit" wire:loading.attr="disabled" wire:target="createCandidate" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow hover:bg-indigo-700 disabled:opacity-60">
                <span wire:loading.remove wire:target="createCandidate">Create Candidate</span>
                <span wire:loading wire:target="createCandidate">Creating...</span>
            </button>
        </div>
    </form>

    <hr class="my-6">

    <h4 class="text-md font-bold text-gray-800 mb-4">Existing Candidates Directory</h4>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name, email, phone" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
        <input type="text" wire:model.live.debounce.300ms="phone_filter" placeholder="Phone" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
        <input type="date" wire:model.live="date_filter" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
        <input type="month" wire:model.live="month_filter" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
        <select wire:model.live="department_filter" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
            <option value="">All Departments</option>
            @foreach($departments as $department)
                <option value="{{ $department->id }}">{{ $department->name }}</option>
            @endforeach
        </select>
        <div class="md:col-span-5">
            <input type="text" wire:model.live.debounce.300ms="position_filter" placeholder="Filter by position/title" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
        </div>
    </div>

    <div class="overflow-x-auto max-h-80">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Application Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($candidates as $candidate)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $candidate->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $candidate->phone ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $candidate->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $candidate->department->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $candidate->jobApplications->first()?->jobPosting?->title ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @php($applicationStatus = $candidate->jobApplications->first()?->status ?? 'applied')
                            <select wire:change="updateApplicationStatus({{ $candidate->id }}, $event.target.value)" class="border-gray-300 rounded-md text-xs">
                                @foreach(['applied' => 'Documents Pending', 'screening' => 'Under Evaluation', 'interview' => 'Video Interview', 'selected' => 'Final Selected', 'joined' => 'Joined', 'rejected' => 'Rejected'] as $statusValue => $statusLabel)
                                    <option value="{{ $statusValue }}" @selected($applicationStatus === $statusValue)>{{ $statusLabel }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $candidate->created_at?->format('d M Y') ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="viewAnswers({{ $candidate->id }})" class="text-green-600 hover:text-green-900 underline mr-3">View Answers</button>
                            <button wire:click="editCandidate({{ $candidate->id }})" class="text-indigo-600 hover:text-indigo-900 underline">Edit / Reset Pass</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">No candidates found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

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
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" wire:model="edit_phone" class="mt-1 block w-full border-gray-300 rounded-md">
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

    @if($isAnswerModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-gray-500 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl p-6 max-h-[85vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-lg font-medium text-gray-900">Assessment Answers: {{ $answerCandidateName }}</h3>
                <button type="button" wire:click="closeAnswerModal" class="text-gray-500 text-2xl">&times;</button>
            </div>
            <div class="flex gap-2 border-b border-gray-200 mb-4 overflow-x-auto">
                @foreach(['documents' => 'Uploads', 'iq' => 'IQ', 'departmental' => 'Departmental', 'rules' => 'Office Rules'] as $tab => $label)
                    <button type="button" wire:click="$set('reviewTab', '{{ $tab }}')" class="px-3 py-2 text-sm font-semibold whitespace-nowrap {{ $reviewTab === $tab ? 'border-b-2 border-indigo-600 text-indigo-700' : 'text-gray-500' }}">{{ $label }}</button>
                @endforeach
            </div>

            @if($reviewTab === 'documents')
                <div class="space-y-3">
                    @forelse($candidateDocuments as $document)
                        <div class="flex items-center justify-between gap-3 p-3 border border-gray-200 rounded-md">
                            <div>
                                <p class="font-semibold text-gray-800">{{ ucwords(str_replace('_', ' ', $document['type'])) }}</p>
                                <p class="text-xs text-gray-500">{{ $document['name'] }}</p>
                            </div>
                            <a href="{{ $document['url'] }}" target="_blank" rel="noopener" class="px-3 py-2 text-sm font-semibold text-indigo-700 bg-indigo-50 rounded-md hover:bg-indigo-100">Open File</a>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 py-6 text-center">No documents uploaded yet.</p>
                    @endforelse
                </div>
            @else
                @php($tabAnswers = collect($candidateAnswers)->where('phase', $reviewTab))
                @forelse($tabAnswers as $answer)
                    <div class="border-b border-gray-200 py-4">
                        <p class="font-semibold text-gray-800">{{ $answer['question'] }}</p>
                        <p class="text-sm text-gray-600 mt-2">Candidate answer: {{ $answer['answer'] }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 py-6 text-center">No submitted answers for this stage yet.</p>
                @endforelse
            @endif
        </div>
    </div>
    @endif
</div>