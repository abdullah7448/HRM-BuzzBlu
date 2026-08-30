<div class="bg-white p-6 border border-gray-200 rounded-lg shadow-sm mt-6">
    <div class="flex justify-between items-center mb-4 border-b pb-2">
        <h3 class="text-lg font-bold text-gray-900">Candidate Evaluation & Screening</h3>
    </div>

    @if (session()->has('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Applications Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Candidate Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Applied Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($applications as $app)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $app->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $app->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $app->jobPosting->title }}</div>
                            <div class="text-xs text-gray-500">{{ $app->jobPosting->department->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $app->status === 'screening' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $app->status === 'interview' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $app->status === 'selected' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $app->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($app->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end space-x-2">
                            
                            @if($app->user->hasRole('Candidate'))
                                
                                <button wire:click="viewAnswers({{ $app->id }})" class="px-4 py-2 bg-indigo-50 text-indigo-700 rounded border border-indigo-200 hover:bg-indigo-100 transition">
                                    Review Answers
                                </button>

                                <!-- Show Convert Button ONLY if candidate is Selected -->
                                @if($app->status === 'selected')
                                    <button wire:click="convertToEmployee({{ $app->user->id }})" onclick="confirm('Are you sure you want to hire this candidate and convert them to an Employee?') || event.stopImmediatePropagation()" class="px-4 py-2 bg-green-600 text-white font-bold rounded shadow hover:bg-green-700 transition">
                                        Convert to Employee
                                    </button>
                                @endif

                            @else
                                <!-- If already converted to employee, show a hired badge -->
                                <span class="px-4 py-2 text-sm text-green-700 font-bold bg-green-50 rounded border border-green-200">
                                    ✓ Hired (Employee)
                                </span>
                            @endif

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No submitted applications found for evaluation.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Evaluation Modal (Exam Paper) -->
    @if($isModalOpen && $selectedApplication)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-75 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col">
            
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50 rounded-t-lg">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Applicant: {{ $selectedApplication->user->name }}</h3>
                    <p class="text-sm text-gray-600">Role: {{ $selectedApplication->jobPosting->title }} | Current Status: <span class="font-bold text-indigo-600">{{ ucfirst($selectedApplication->status) }}</span></p>
                </div>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 font-bold text-2xl">&times;</button>
            </div>

            <div class="p-6 overflow-y-auto flex-1 space-y-6">
                @forelse($answers as $index => $answer)
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-md">
                        <p class="font-bold text-gray-800 text-md mb-2">Q{{ $index + 1 }}: {{ $answer->question->question_text }}</p>
                        @if($answer->question->expected_answer)
                            <div class="mb-2 text-xs text-gray-500 font-medium">
                                Expected Answer: <span class="text-green-600">{{ $answer->question->expected_answer }}</span>
                            </div>
                        @endif
                        <div class="p-3 bg-white border border-gray-300 rounded shadow-sm text-gray-800">
                            <strong>Candidate's Answer:</strong><br>
                            <span class="whitespace-pre-line">{{ $answer->answer_text }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-8">No answers submitted.</div>
                @endforelse
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-lg flex flex-wrap gap-2 justify-end items-center">
                <button wire:click="closeModal" class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-100 mr-auto">Cancel</button>
                
                <button wire:click="updateStatus('rejected')" class="px-4 py-2 bg-red-100 text-red-700 border border-red-200 rounded hover:bg-red-200 font-bold">Reject</button>

                @if($selectedApplication->status === 'screening')
                    <button wire:click="updateStatus('interview')" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 font-bold">Pass to Interview</button>
                @endif

                @if($selectedApplication->status === 'interview' || $selectedApplication->status === 'screening')
                    <button wire:click="updateStatus('selected')" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-bold shadow">Final Select (Issue Offer)</button>
                @endif
            </div>

        </div>
    </div>
    @endif
</div>