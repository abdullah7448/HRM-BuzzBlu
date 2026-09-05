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

                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-md font-bold text-gray-800">Screening Question Builder</h4>
                        <div class="flex items-center gap-2">
                            <button type="button" wire:click="toggleCandidatePreview" class="px-3 py-1 bg-slate-700 text-white text-sm rounded hover:bg-slate-800">
                                {{ $showCandidatePreview ? 'Hide Candidate Preview' : 'Preview Candidate Assessment' }}
                            </button>
                            <button type="button" wire:click="addQuestion" class="px-3 py-1 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                                + Add Question
                            </button>
                        </div>
                    </div>

                    @foreach($questions as $index => $question)
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-md mb-3 relative" draggable="true"
                             @dragstart="draggingIndex = {{ $index }}"
                             @dragover.prevent
                             @drop="if (typeof draggingIndex !== 'undefined' && draggingIndex !== {{ $index }}) { $wire.reorderQuestions(draggingIndex, {{ $index }}); draggingIndex = null; }">
                            <div class="absolute top-2 right-2 flex items-center gap-2">
                                <button type="button" wire:click="removeQuestion({{ $index }})" class="text-red-500 hover:text-red-700 font-bold" title="Remove">&times;</button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-gray-700">Phase</label>
                                    <select wire:model.live="questions.{{ $index }}.phase" class="mt-1 block w-full border-gray-300 rounded-md text-sm">
                                        <option value="iq">IQ Test</option>
                                        <option value="departmental">Departmental</option>
                                        <option value="rules" disabled>Office Rules (Automatic)</option>
                                    </select>
                                </div>
                                <div class="md:col-span-4">
                                    <label class="block text-xs font-medium text-gray-700">
                                        {{ ($question['phase'] ?? 'iq') === 'rules' ? 'Office Rules / Terms & Conditions' : 'Question Text' }}
                                    </label>
                                    @if(($question['phase'] ?? 'iq') === 'rules')
                                        <textarea rows="8" readonly class="mt-1 block w-full border-gray-300 rounded-md bg-slate-100 text-sm text-slate-700">{{ config('ats.office_rules') }}</textarea>
                                        <p class="mt-1 text-xs text-emerald-700">System-managed agreement. This text is automatically shown to every candidate and cannot be changed per job.</p>
                                    @else
                                        <input type="text" wire:model="questions.{{ $index }}.question_text" class="mt-1 block w-full border-gray-300 rounded-md text-sm" placeholder="Example: Which color is blue?">
                                    @endif
                                </div>
                                @if(($question['phase'] ?? 'iq') !== 'rules')
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-gray-700">Type</label>
                                        <select wire:model="questions.{{ $index }}.question_type" wire:change="updateQuestionType({{ $index }}, $event.target.value)" class="mt-1 block w-full border-gray-300 rounded-md text-sm">
                                            <option value="multiple_choice">Multiple Choice</option>
                                            <option value="yes_no">Yes / No</option>
                                            <option value="text">Short Text</option>
                                        </select>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-gray-700">Correct Answer</label>
                                        @if(($question['question_type'] ?? 'multiple_choice') === 'multiple_choice')
                                            <select wire:model="questions.{{ $index }}.expected_answer" class="mt-1 block w-full border-gray-300 rounded-md text-sm">
                                                <option value="">Select</option>
                                                @foreach(($question['options'] ?? []) as $option)
                                                    @if(trim((string) $option) !== '')
                                                        <option value="{{ $option }}">{{ $option }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @elseif(($question['question_type'] ?? 'multiple_choice') === 'yes_no')
                                            <select wire:model="questions.{{ $index }}.expected_answer" class="mt-1 block w-full border-gray-300 rounded-md text-sm">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        @else
                                            <input type="text" wire:model="questions.{{ $index }}.expected_answer" class="mt-1 block w-full border-gray-300 rounded-md text-sm" placeholder="Expected answer">
                                        @endif
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-gray-700">Match Points</label>
                                        <input type="number" wire:model="questions.{{ $index }}.points" class="mt-1 block w-full border-gray-300 rounded-md text-sm" min="1">
                                    </div>
                                @else
                                    <div class="md:col-span-6 flex items-center rounded-md border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-800">
                                        Final agreement: candidate reads the full terms and checks “I have read and I understand”. This step has no marks.
                                    </div>
                                @endif
                            </div>

                            @if(($question['phase'] ?? 'iq') !== 'rules' && ($question['question_type'] ?? 'multiple_choice') === 'multiple_choice')
                                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @for($optionIndex = 0; $optionIndex < 4; $optionIndex++)
                                        <div>
                                            <label class="block text-[11px] font-medium text-gray-600">Option {{ $optionIndex + 1 }}</label>
                                            <input type="text" wire:model="questions.{{ $index }}.options.{{ $optionIndex }}" class="mt-1 block w-full border-gray-300 rounded-md text-sm" placeholder="Option {{ $optionIndex + 1 }}">
                                        </div>
                                    @endfor
                                </div>
                            @elseif(($question['phase'] ?? 'iq') !== 'rules' && ($question['question_type'] ?? 'multiple_choice') === 'yes_no')
                                <div class="mt-4 flex gap-3">
                                    <div class="flex-1 p-3 border border-gray-200 rounded-md bg-white text-sm font-medium">Yes</div>
                                    <div class="flex-1 p-3 border border-gray-200 rounded-md bg-white text-sm font-medium">No</div>
                                </div>
                            @elseif(($question['phase'] ?? 'iq') !== 'rules')
                                <div class="mt-4 p-3 border border-dashed border-gray-300 rounded-md bg-white text-xs text-gray-500">
                                    Short text answer preview: candidate will type a response in a textarea.
                                </div>
                            @endif

                        </div>
                    @endforeach
                </div>

                @if($showCandidatePreview)
                    <div class="mb-6 border border-slate-300 rounded-xl overflow-hidden bg-slate-50">
                        <div class="px-6 py-5 bg-slate-800 text-white">
                            <div class="flex justify-between items-start gap-4">
                                <div>
                                    <p class="text-xs uppercase tracking-wider text-slate-300">Candidate Preview</p>
                                    <h4 class="text-xl font-bold mt-1">{{ $title ?: 'Your Job Title' }}</h4>
                                    <p class="text-sm text-slate-300 mt-1">This is the complete assessment as the candidate will see it.</p>
                                </div>
                                <span class="px-3 py-1 rounded-full bg-white/10 text-xs whitespace-nowrap">{{ count($questions) }} questions</span>
                            </div>
                        </div>

                        <div class="p-6 space-y-5">
                            @forelse($questions as $previewIndex => $previewQuestion)
                                @php
                                    $previewType = $previewQuestion['question_type'] ?? 'multiple_choice';
                                    $previewPhase = $previewQuestion['phase'] ?? 'iq';
                                    $phaseLabel = ['iq' => 'IQ Test', 'departmental' => 'Departmental', 'rules' => 'Office Rules'][$previewPhase] ?? 'Assessment';
                                @endphp
                                <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm">
                                    <div class="flex justify-between items-center gap-3 mb-3">
                                        <span class="text-xs font-bold uppercase tracking-wide text-indigo-600">{{ $phaseLabel }}</span>
                                        <span class="text-xs text-slate-500">Question {{ $previewIndex + 1 }} · {{ $previewQuestion['points'] ?? 0 }} points</span>
                                    </div>

                                    @if($previewPhase === 'rules')
                                        <h5 class="text-lg font-bold text-slate-900 mb-3">BuzzBlu Office Rules & Code of Conduct</h5>
                                        <p class="text-sm text-slate-700 whitespace-pre-line">{{ $previewQuestion['question_text'] ?: 'Office rules and code of conduct agreement text' }}</p>
                                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <div class="p-3 border border-slate-200 rounded-md">◯ <span class="font-medium">I agree</span></div>
                                            <div class="p-3 border border-slate-200 rounded-md">◯ <span class="font-medium">I do not agree</span></div>
                                        </div>
                                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <div class="h-10 border-b border-slate-300 text-xs text-slate-400 pt-2">Signature</div>
                                            <div class="h-10 border-b border-slate-300 text-xs text-slate-400 pt-2">Date</div>
                                        </div>
                                    @else
                                        <h5 class="text-base font-semibold text-slate-900">{{ $previewQuestion['question_text'] ?: 'Question text will appear here' }}</h5>
                                        @if($previewType === 'multiple_choice')
                                            <div class="mt-4 space-y-2">
                                                @forelse($previewQuestion['options'] ?? [] as $option)
                                                    @if(trim((string) $option) !== '')
                                                        <div class="flex items-center gap-3 p-3 border border-slate-200 rounded-md">◯ <span>{{ $option }}</span></div>
                                                    @endif
                                                @empty
                                                    <div class="text-sm text-slate-400">No options added yet.</div>
                                                @endforelse
                                            </div>
                                        @elseif($previewType === 'yes_no')
                                            <div class="mt-4 grid grid-cols-2 gap-3">
                                                <div class="p-3 border border-slate-200 rounded-md">◯ Yes</div>
                                                <div class="p-3 border border-slate-200 rounded-md">◯ No</div>
                                            </div>
                                        @else
                                            <div class="mt-4 h-20 border border-slate-200 rounded-md p-3 text-sm text-slate-400">Candidate answer box</div>
                                        @endif
                                    @endif
                                </div>
                            @empty
                                <div class="text-center text-sm text-slate-500 py-8">Add questions to see the complete candidate assessment preview.</div>
                            @endforelse

                            <div class="flex justify-end">
                                <button type="button" disabled class="px-5 py-2 bg-indigo-600 text-white rounded-md opacity-60">Submit Assessment</button>
                            </div>
                        </div>
                    </div>
                @endif

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