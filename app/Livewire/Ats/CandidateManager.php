<?php

namespace App\Livewire\Ats;

use Livewire\Component;
use App\Models\User;
use App\Models\JobPosting;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CandidateManager extends Component
{
    public $name, $email, $job_posting_id;
    public $successMessage = '';
    public $generatedPassword = '';

    // Edit Properties
    public $isEditModalOpen = false;
    public $edit_candidate_id, $edit_name, $edit_email, $edit_job_posting_id, $hr_new_password;

    public function createCandidate()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'job_posting_id' => 'required|exists:job_postings,id',
        ]);

        $this->generatedPassword = Str::random(8);
        
        // Fetch the job to sync department and designation
        $job = JobPosting::findOrFail($this->job_posting_id);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->generatedPassword),
            'department_id' => $job->department_id, // Syncing Department
            'designation_id' => $job->designation_id, // Syncing Designation
            'status' => 'active',
        ]);

        $user->assignRole('Candidate');

        JobApplication::create([
            'job_posting_id' => $this->job_posting_id,
            'user_id' => $user->id,
            'status' => 'applied',
        ]);

        $this->successMessage = "Candidate account created and assigned successfully!";
        $this->reset(['name', 'email', 'job_posting_id']);
    }

    public function editCandidate($id)
    {
        $candidate = User::findOrFail($id);
        $this->edit_candidate_id = $candidate->id;
        $this->edit_name = $candidate->name;
        $this->edit_email = $candidate->email;
        
        $app = JobApplication::where('user_id', $id)->first();
        $this->edit_job_posting_id = $app ? $app->job_posting_id : '';
        $this->hr_new_password = '';
        
        $this->isEditModalOpen = true;
    }

    public function closeEditModal()
    {
        $this->isEditModalOpen = false;
    }

    public function updateCandidate()
    {
        $this->validate([
            'edit_name' => 'required|string|max:255',
            'edit_email' => 'required|email|unique:users,email,' . $this->edit_candidate_id,
        ]);

        $candidate = User::findOrFail($this->edit_candidate_id);
        $candidate->update([
            'name' => $this->edit_name,
            'email' => $this->edit_email,
        ]);

        // Reset password if HR typed a new one
        if (!empty($this->hr_new_password)) {
            $candidate->update(['password' => Hash::make($this->hr_new_password)]);
        }

        // Update Job Assignment & User Profile if changed
        if ($this->edit_job_posting_id) {
            $job = JobPosting::findOrFail($this->edit_job_posting_id);
            
            // Sync the User's core profile with the new job assignment
            $candidate->update([
                'department_id' => $job->department_id,
                'designation_id' => $job->designation_id,
            ]);

            $app = JobApplication::where('user_id', $this->edit_candidate_id)->first();
            if ($app) {
                $app->update(['job_posting_id' => $this->edit_job_posting_id]);
            }
        }

        $this->isEditModalOpen = false;
        $this->successMessage = "Candidate profile, password, and job role updated successfully!";
    }

    public function render()
    {
        return view('livewire.ats.candidate-manager', [
            'candidates' => User::role('Candidate')->latest()->get(),
            'openJobs' => JobPosting::with(['department', 'designation'])->where('status', 'open')->get()
        ]);
    }
}