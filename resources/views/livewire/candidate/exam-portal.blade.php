<div class="bg-white p-3 sm:p-6 border border-gray-200 rounded-lg shadow-sm">
    
    <!-- Success Message -->
    @if (session()->has('success'))
        <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200 flex justify-between items-center">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="flex justify-end mb-4">
        <button wire:click="openPasswordModal" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-md shadow-sm hover:bg-gray-50 transition">
            Change My Password
        </button>
    </div>

    @php
        $application = \App\Models\JobApplication::with(['jobPosting.department'])->where('user_id', auth()->id())->first();
    @endphp

    @if(!$application)
        <div class="text-center py-8">
            <h3 class="text-lg font-bold text-gray-500">You have not been assigned to any assessment yet.</h3>
            <p class="text-sm text-gray-400">Please contact HR if you believe this is an error.</p>
        </div>
    @else
        <h3 class="text-xl font-bold text-gray-900 mb-2">Role: {{ $application->jobPosting->title }}</h3>
        <p class="text-sm text-gray-500 mb-6">Department: {{ $application->jobPosting->department->name ?? 'General' }}</p>

        @php
            $step = $applicationStep ?? $application->application_step ?? 'documents';
            $status = $application->status;
            $documentDone = $step !== 'documents';
            $iqDone = in_array($step, ['departmental', 'rules', 'submitted'], true) || in_array($status, ['screening', 'interview', 'selected', 'joined'], true);
            $departmentalDone = in_array($step, ['rules', 'submitted'], true) || in_array($status, ['screening', 'interview', 'selected', 'joined'], true);
            $rulesDone = $step === 'submitted' || in_array($status, ['screening', 'interview', 'selected', 'joined'], true);
            $evaluationDone = in_array($status, ['interview', 'selected', 'joined'], true);
            $interviewDone = in_array($status, ['selected', 'joined'], true);
            $joinedDone = $status === 'joined';
            $trackerSteps = [
                ['label' => 'Documents', 'done' => $documentDone, 'current' => $step === 'documents'],
                ['label' => 'IQ Test', 'done' => $iqDone, 'current' => $step === 'iq'],
                ['label' => 'Departmental', 'done' => $departmentalDone, 'current' => $step === 'departmental'],
                ['label' => 'Office Rules', 'done' => $rulesDone, 'current' => $step === 'rules'],
                ['label' => 'Evaluation', 'done' => $evaluationDone, 'current' => $status === 'screening'],
                ['label' => 'Video Interview', 'done' => $interviewDone, 'current' => $status === 'interview'],
                ['label' => 'Joined', 'done' => $joinedDone, 'current' => $status === 'selected'],
            ];
        @endphp

        <div class="mb-6 rounded-xl border bg-white p-3 shadow-sm sm:p-4" style="border-color: #d9e2ec;">
            <div class="mb-3 flex items-center justify-between gap-2">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest" style="color: #718096;">Application progress</p>
                    <h4 class="mt-1 text-sm font-bold" style="color: #1a202c;">Your hiring journey</h4>
                </div>
                <span class="rounded-full px-2 py-1 text-[10px] font-bold" style="background: {{ $status === 'rejected' ? '#fff1f2' : '#edf2f7' }}; color: {{ $status === 'rejected' ? '#c53030' : '#4a5568' }};">{{ ucfirst($status) }}</span>
            </div>
            <div class="flex w-full flex-col gap-0 sm:flex-row sm:items-stretch sm:overflow-x-auto sm:pb-1">
                @foreach($trackerSteps as $trackerStep)
                    @php
                        $cardBorder = $trackerStep['done'] ? '#18a66a' : ($trackerStep['current'] ? '#0891b2' : '#a0aec0');
                        $iconBackground = $trackerStep['done'] ? '#d9fbe8' : ($trackerStep['current'] ? '#d9f4fb' : '#edf2f7');
                        $iconColor = $trackerStep['done'] ? '#087f52' : ($trackerStep['current'] ? '#0e7490' : '#718096');
                        $textColor = $trackerStep['done'] ? '#087f52' : ($trackerStep['current'] ? '#0e7490' : '#4a5568');
                    @endphp
                    <div class="flex w-full min-w-0 flex-col items-stretch sm:flex-1 sm:flex-row sm:min-w-[166px] sm:items-stretch sm:flex-initial">
                        <div class="w-full rounded-lg border border-t-4 bg-white px-2 py-2 shadow-sm" style="border-color: #e2e8f0; border-top-color: {{ $cardBorder }};">
                            <div class="flex items-center gap-2">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-md text-sm font-extrabold" style="background: {{ $iconBackground }}; color: {{ $iconColor }};">
                                    {{ $trackerStep['done'] ? '✓' : $loop->iteration }}
                                </div>
                                <div class="min-w-0">
                                    <span class="block text-[11px] font-bold uppercase leading-tight tracking-wide" style="color: {{ $textColor }};">{{ $trackerStep['label'] }}</span>
                                    <span class="mt-1 block text-[10px] font-medium leading-tight" style="color: {{ $textColor }};">{{ $trackerStep['done'] ? 'Completed' : ($trackerStep['current'] ? 'In progress' : 'Upcoming') }}</span>
                                </div>
                            </div>
                        </div>
                        @if(!$loop->last)
                            <div class="mx-auto h-3 w-0 border-l-2 border-dashed sm:my-auto sm:h-0 sm:w-4 sm:border-l-0 sm:border-t-2" style="border-color: {{ $trackerStep['done'] ? '#8bd8b5' : '#cbd5e0' }};"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        @if(($applicationStep ?? $application->application_step ?? 'documents') === 'documents' && !$isTakingExam)
            <div class="p-6 bg-indigo-50 border border-indigo-200 rounded-lg">
                <h4 class="text-lg font-bold text-indigo-900 mb-4">Step 1: Upload Required Documents</h4>
                <p class="text-sm text-indigo-700 mb-6">Please upload your CV, NID/Birth Certificate, and Educational Certificate to continue.</p>

                <form wire:submit.prevent="uploadDocuments" class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">CV / Resume</label>
                        <input type="file" wire:model="cv" class="mt-1 block w-full text-sm text-gray-600">
                        @error('cv') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">NID / Birth Certificate</label>
                        <input type="file" wire:model="nid_or_birth_certificate" class="mt-1 block w-full text-sm text-gray-600">
                        @error('nid_or_birth_certificate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Educational Certificate</label>
                        <input type="file" wire:model="education_certificate" class="mt-1 block w-full text-sm text-gray-600">
                        @error('education_certificate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded shadow hover:bg-indigo-700 transition">
                            Upload and Continue
                        </button>
                    </div>
                </form>
            </div>

        @elseif(in_array(($applicationStep ?? $application->application_step ?? 'documents'), ['questions', 'iq', 'departmental', 'rules'], true) && !$isTakingExam)
            <div class="p-6 bg-indigo-50 border border-indigo-200 rounded-lg text-center">
                <h4 class="text-lg font-bold text-indigo-900 mb-2">Action Required: Online Assessment</h4>
                <p class="text-sm text-indigo-700 mb-4">Complete the IQ, departmental, and final office-rules stages in order.</p>
                <button wire:click="startExam" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded shadow hover:bg-indigo-700 transition">
                    Start Written Test Now
                </button>
            </div>

        @elseif($isTakingExam)
            <div class="border-t border-gray-200 pt-6">
                @php
                    $phaseLabels = ['iq' => 'Step 2: IQ Test', 'departmental' => 'Step 3: Departmental Questions', 'rules' => 'Step 4: Office Rules Agreement'];
                    $phaseLabel = $phaseLabels[$currentPhase] ?? 'Assessment';
                @endphp
                <h4 class="text-lg font-bold text-gray-800 mb-2">{{ $phaseLabel }}</h4>
                <p class="text-sm text-gray-500 mb-4">Complete this stage before continuing to the next one.</p>
                <form wire:submit.prevent="submitExam" class="space-y-6">
                    @foreach($questions as $index => $q)
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            @if($q->phase === 'rules')
                                <div class="bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-xl p-5">
                                    <div class="flex items-center justify-between mb-3">
                                        <h5 class="text-lg font-bold text-indigo-900">BuzzBlu Office Rules & Code of Conduct</h5>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-700">Final Agreement</span>
                                    </div>

                                    <div class="bg-white border border-indigo-100 rounded-lg p-4 text-sm text-gray-700 space-y-3">
                                        <p class="font-semibold text-gray-800 whitespace-pre-line">{{ $q->question_text }}</p>
                                    </div>

                                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-md bg-white">
                                            <input type="radio" wire:model="answers.{{ $q->id }}" value="Yes" class="text-indigo-600">
                                            <span class="font-semibold text-gray-700">I have read and I understand</span>
                                        </label>
                                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-md bg-white">
                                            <input type="radio" wire:model="answers.{{ $q->id }}" value="No" class="text-indigo-600">
                                            <span class="font-semibold text-gray-700">I do not agree</span>
                                        </label>
                                    </div>
                                    @error('answers.' . $q->id) <span class="block mt-2 text-sm text-red-600">{{ $message }}</span> @enderror

                                </div>
                            @else
                                <label class="block text-md font-bold text-gray-800 mb-2">{{ $index + 1 }}. {{ $q->question_text }}</label>
                            @endif

                            @if($q->question_type === 'yes_no' && $q->phase !== 'rules')
                                <select wire:model="answers.{{ $q->id }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Select Answer</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            @elseif($q->question_type === 'multiple_choice' && $q->phase !== 'rules')
                                <div class="space-y-2 mt-2">
                                    @foreach($q->options ?? [] as $option)
                                        <label class="flex items-center gap-3 p-2 border border-gray-200 rounded-md bg-white">
                                            <input type="radio" wire:model="answers.{{ $q->id }}" value="{{ $option }}" class="text-indigo-600">
                                            <span>{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @elseif($q->phase !== 'rules')
                                <textarea wire:model="answers.{{ $q->id }}" required rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Write your answer here..."></textarea>
                            @endif
                        </div>
                    @endforeach

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-bold rounded shadow hover:bg-blue-700 transition">
                            {{ $currentPhase === 'rules' ? 'Submit Assessment for Evaluation' : 'Save and Continue' }}
                        </button>
                    </div>
                </form>
            </div>

        <!-- Status: Tracking (After submission) -->
        @else
            <div class="p-6 bg-gray-50 border border-gray-200 rounded-lg mt-4">
                <h4 class="text-md font-bold text-gray-800 mb-4">Current Recruitment Status</h4>
                
                <!-- Status Timeline -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 mb-8">
                    <span class="px-4 py-2 text-sm font-bold rounded-full bg-green-100 text-green-800">
                        &#10003; Test Submitted
                    </span>
                    <span class="hidden sm:inline text-gray-400">&#8594;</span>
                    
                    <span class="px-4 py-2 text-sm font-bold rounded-full 
                        {{ $application->status === 'screening' ? 'bg-blue-100 text-blue-800 ring-2 ring-blue-400' : ($application->status === 'interview' || $application->status === 'selected' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-600') }}">
                        {{ $application->status === 'interview' || $application->status === 'selected' ? '✓ Evaluated' : 'Under Evaluation' }}
                    </span>
                    <span class="hidden sm:inline text-gray-400">&#8594;</span>
                    
                    <span class="px-4 py-2 text-sm font-bold rounded-full 
                        {{ $application->status === 'interview' ? 'bg-purple-100 text-purple-800 ring-2 ring-purple-400' : ($application->status === 'selected' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-600') }}">
                        {{ $application->status === 'selected' ? '✓ Interviewed' : 'Video Interview' }}
                    </span>
                    <span class="hidden sm:inline text-gray-400">&#8594;</span>
                    
                    <span class="px-4 py-2 text-sm font-bold rounded-full 
                        {{ $application->status === 'selected' ? 'bg-green-100 text-green-800 ring-2 ring-green-400' : ($application->status === 'joined' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-600') }}">
                        {{ $application->status === 'joined' ? '✓ Joined' : 'Final Select' }}
                    </span>
                </div>
                
                <!-- Dynamic Instruction Blocks based on Status -->
                @if($application->status === 'screening')
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-md text-blue-800">
                        <p class="font-bold">Your answers are currently being evaluated.</p>
                        <p class="text-sm mt-1">Our department heads are reviewing your submission. Please check back here regularly for updates.</p>
                    </div>

                @elseif($application->status === 'interview')
                    <div class="p-6 bg-purple-50 border border-purple-200 rounded-md">
                        <h4 class="text-lg font-bold text-purple-900 mb-2">🎥 Congratulations! You are selected for a Video Interview.</h4>
                        <p class="text-sm text-purple-800 mb-4">Your written answers were impressive. We would like to invite you to an online video interview.</p>
                        
                        <div class="bg-white p-4 rounded border border-purple-100 text-sm text-gray-700 space-y-3 shadow-sm">
                            <p class="font-bold border-b pb-2">How to join the Video Interview:</p>
                            <ul class="list-disc ml-5 space-y-2">
                                <li><strong>Check your Email:</strong> HR will send you an email containing the exact Time and the <strong>Google Meet / Zoom</strong> meeting link shortly.</li>
                                <li><strong>Preparation:</strong> Please ensure you have a stable internet connection, a working webcam, and a clear microphone.</li>
                                <li><strong>Join 5 minutes early:</strong> Click the meeting link provided in your email 5 minutes before the scheduled time.</li>
                            </ul>
                            <p class="mt-4 text-xs text-gray-500 font-bold">* If you do not receive an email within 24 hours, please contact the HR department.</p>
                        </div>
                    </div>

                @elseif($application->status === 'selected')
                    <div class="p-6 bg-green-50 border border-green-200 rounded-md text-green-900 text-center">
                        <h4 class="text-xl font-bold mb-2">🎉 Congratulations! You have been Selected!</h4>
                        <p class="text-md mb-4">You have successfully passed the Video Interview and we are thrilled to offer you the position.</p>
                        <div class="bg-white p-4 rounded border border-green-100 text-sm text-left">
                            <p class="font-bold mb-2">Next Steps for Onboarding:</p>
                            <ul class="list-disc ml-5 space-y-1">
                                <li>HR will issue your official <strong>Appointment Letter</strong> via email.</li>
                                <li>Please reply to the email to confirm your acceptance.</li>
                                <li>Once confirmed, this candidate account will be converted to a full Employee Account with system access.</li>
                            </ul>
                        </div>
                    </div>

                @elseif($application->status === 'joined')
                    <div class="p-6 bg-green-50 border border-green-200 rounded-md text-green-900 text-center">
                        <h4 class="text-xl font-bold mb-2">Welcome to the team!</h4>
                        <p class="text-md">Your joining process is complete. HR will share your onboarding details.</p>
                    </div>

                @elseif($application->status === 'rejected')
                    <div class="p-4 bg-red-50 text-red-700 border border-red-200 rounded">
                        <p class="font-bold">Application Update</p>
                        <p class="text-sm mt-1">Thank you for your interest and time. Unfortunately, we will not be moving forward with your application at this time. We wish you the best in your future endeavors.</p>
                    </div>
                @endif

            </div>
        @endif
    @endif

    <!-- Change Password Modal -->
    @if($isPasswordModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-gray-500 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>
            <form wire:submit.prevent="updatePassword">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Current Password</label>
                    <input type="password" wire:model="current_password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('current_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">New Password</label>
                    <input type="password" wire:model="new_password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('new_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                    <input type="password" wire:model="new_password_confirmation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" wire:click="closePasswordModal" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Password</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>