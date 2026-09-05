<x-app-layout>
    <!-- Premium Header -->
    <x-dashboard-header 
        title="Candidate Recruitment Portal"
        description="Apply for jobs and complete assessments"
        icon='<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9 12a3 3 0 100-6 3 3 0 000 6zm-9 1a9 9 0 1118 0 9 9 0 01-18 0z" stroke="currentColor" stroke-width="1.5" fill="none"></path></svg>'
    />

    <div class="py-5 sm:py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            
            <!-- Candidate Portal Section -->
            <div class="bg-white rounded-xl shadow-card p-3 sm:p-6">
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6 flex items-center gap-2">
                    <span class="w-1 h-6 bg-primary-600 rounded"></span>
                    Assessment & Application Portal
                </h3>
                
                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-primary-50 to-secondary-50 border-l-4 border-primary-600 p-4 sm:p-6 rounded-lg mb-5 sm:mb-8">
                    <p class="text-gray-700 font-semibold">Welcome to our Recruitment Portal!</p>
                    <p class="text-gray-600 mt-2">Complete the assessments and submit your applications to apply for open positions in our company.</p>
                </div>

                <!-- Portal Component -->
                <livewire:candidate.exam-portal />
            </div>

            <!-- Info Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mt-5 sm:mt-8">
                <div class="bg-white rounded-xl shadow-card p-4 sm:p-6 border-t-4 border-success-500">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-success-100 rounded-lg">
                            <svg class="w-6 h-6 text-success-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm font-semibold uppercase tracking-wide">Applications Submitted</p>
                            <p class="text-2xl font-bold text-gray-900">2</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-card p-4 sm:p-6 border-t-4 border-primary-500">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-primary-100 rounded-lg">
                            <svg class="w-6 h-6 text-primary-600" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" /></svg>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm font-semibold uppercase tracking-wide">Assessments Completed</p>
                            <p class="text-2xl font-bold text-gray-900">1</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-card p-4 sm:p-6 border-t-4 border-warning-500">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-warning-100 rounded-lg">
                            <svg class="w-6 h-6 text-warning-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" /></svg>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm font-semibold uppercase tracking-wide">Under Review</p>
                            <p class="text-2xl font-bold text-gray-900">1</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>