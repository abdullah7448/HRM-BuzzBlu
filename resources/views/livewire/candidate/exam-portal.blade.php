<div class="bg-white p-6 border border-gray-200 rounded-lg shadow-sm">
    
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

    @if(!$application)
        <div class="text-center py-8">
            <h3 class="text-lg font-bold text-gray-500">You have not been assigned to any assessment yet.</h3>
            <p class="text-sm text-gray-400">Please contact HR if you believe this is an error.</p>
        </div>
    @else
        <!-- Header Info -->
        <h3 class="text-xl font-bold text-gray-900 mb-2">Role: {{ $application->jobPosting->title }}</h3>
        <p class="text-sm text-gray-500 mb-6">Department: {{ $application->jobPosting->department->name ?? 'General' }}</p>

        <!-- Status: Applied (Needs to take exam) -->
        @if($application->status === 'applied' && !$isTakingExam)
            <div class="p-6 bg-indigo-50 border border-indigo-200 rounded-lg text-center">
                <h4 class="text-lg font-bold text-indigo-900 mb-2">Action Required: Online Assessment</h4>
                <p class="text-sm text-indigo-700 mb-4">You have been invited to complete the initial written assessment for this role.</p>
                <button wire:click="startExam" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded shadow hover:bg-indigo-700 transition">
                    Start Written Test Now
                </button>
            </div>

        <!-- Status: Taking Exam -->
        @elseif($isTakingExam)
            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4">Please answer all questions below:</h4>
                <form wire:submit.prevent="submitExam" class="space-y-6">
                    @foreach($questions as $index => $q)
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            <label class="block text-md font-bold text-gray-800 mb-2">{{ $index + 1 }}. {{ $q->question_text }}</label>
                            
                            @if($q->question_type === 'yes_no')
                                <select wire:model="answers.{{ $q->id }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Select Answer</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            @else
                                <textarea wire:model="answers.{{ $q->id }}" required rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Write your answer here..."></textarea>
                            @endif
                        </div>
                    @endforeach

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-bold rounded shadow hover:bg-blue-700 transition">
                            Submit Exam Answers
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
                        {{ $application->status === 'selected' ? 'bg-green-100 text-green-800 ring-2 ring-green-400' : 'bg-gray-200 text-gray-600' }}">
                        Final Select
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