<?php

namespace App\Livewire\Ats;

use Livewire\Component;
use App\Models\JobApplication;
use App\Models\JobApplicationAnswer;
use App\Models\User;

class ApplicationEvaluator extends Component
{
    public $applications = [];
    public $selectedApplication = null;
    public $answers = [];
    public $isModalOpen = false;

    public function mount()
    {
        $this->loadApplications();
    }

    public function loadApplications()
    {
        // Fetch applications that have submitted the exam
        $this->applications = JobApplication::with(['user', 'jobPosting.designation', 'jobPosting.department'])
            ->where('status', '!=', 'applied')
            ->latest()
            ->get();
    }

    public function viewAnswers($applicationId)
    {
        $this->selectedApplication = JobApplication::with(['user', 'jobPosting.designation', 'jobPosting.department'])->findOrFail($applicationId);
        
        $this->answers = JobApplicationAnswer::with('question')
            ->where('job_application_id', $applicationId)
            ->get();
            
        $this->isModalOpen = true;
    }

    public function updateStatus($newStatus)
    {
        if ($this->selectedApplication) {
            $this->selectedApplication->update(['status' => $newStatus]);
            
            $this->isModalOpen = false;
            $this->loadApplications();
            
            session()->flash('success', "Candidate status has been updated to '" . ucfirst($newStatus) . "'.");
        }
    }

    // --- MAGIC FUNCTION: Convert Candidate to Employee ---
    public function convertToEmployee($userId)
    {
        $user = User::findOrFail($userId);
        
        if ($user->hasRole('Candidate')) {
            $user->removeRole('Candidate');
            $user->assignRole('Employee'); // Give them standard employee access
            
            session()->flash('success', "🎉 Amazing! {$user->name} has been successfully converted to a regular Employee!");
            $this->loadApplications();
        }
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->selectedApplication = null;
        $this->answers = [];
    }

    public function render()
    {
        return view('livewire.ats.application-evaluator');
    }
}