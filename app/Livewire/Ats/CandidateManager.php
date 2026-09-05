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
    public $name, $email, $phone, $job_posting_id;
    public $search = '';
    public $phone_filter = '';
    public $date_filter = '';
    public $month_filter = '';
    public $department_filter = '';
    public $position_filter = '';
    public $successMessage = '';
    public $generatedPassword = '';

    public $isEditModalOpen = false;
    public $edit_candidate_id, $edit_name, $edit_email, $edit_phone, $edit_job_posting_id, $hr_new_password;

    public function createCandidate()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'job_posting_id' => 'required|exists:job_postings,id',
        ]);

        $this->generatedPassword = Str::random(8);
        $job = JobPosting::findOrFail($this->job_posting_id);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => Hash::make($this->generatedPassword),
            'department_id' => $job->department_id,
            'designation_id' => $job->designation_id,
            'status' => 'active',
        ]);

        $user->assignRole('Candidate');

        JobApplication::create([
            'job_posting_id' => $this->job_posting_id,
            'user_id' => $user->id,
            'status' => 'applied',
        ]);

        $this->successMessage = 'Candidate account created and assigned successfully!';
        $this->reset(['name', 'email', 'phone', 'job_posting_id']);
    }

    public function editCandidate($id)
    {
        $candidate = User::findOrFail($id);
        $this->edit_candidate_id = $candidate->id;
        $this->edit_name = $candidate->name;
        $this->edit_email = $candidate->email;
        $this->edit_phone = $candidate->phone;

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
            'edit_phone' => 'nullable|string|max:20',
        ]);

        $candidate = User::findOrFail($this->edit_candidate_id);
        $candidate->update([
            'name' => $this->edit_name,
            'email' => $this->edit_email,
            'phone' => $this->edit_phone,
        ]);

        if (!empty($this->hr_new_password)) {
            $candidate->update(['password' => Hash::make($this->hr_new_password)]);
        }

        if ($this->edit_job_posting_id) {
            $job = JobPosting::findOrFail($this->edit_job_posting_id);
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
        $this->successMessage = 'Candidate profile, password, and job role updated successfully!';
    }

    public function render()
    {
        $query = User::role('Candidate')
            ->with(['department', 'designation', 'jobApplications.jobPosting'])
            ->latest();

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->phone_filter !== '') {
            $query->where('phone', 'like', '%' . $this->phone_filter . '%');
        }

        if ($this->date_filter !== '') {
            $query->whereDate('created_at', $this->date_filter);
        }

        if ($this->month_filter !== '') {
            $query->whereMonth('created_at', $this->month_filter);
        }

        if ($this->department_filter !== '') {
            $query->where('department_id', $this->department_filter);
        }

        if ($this->position_filter !== '') {
            $query->whereHas('jobApplications', function ($q) {
                $q->whereHas('jobPosting', function ($jobQuery) {
                    $jobQuery->where('title', 'like', '%' . $this->position_filter . '%');
                });
            });
        }

        return view('livewire.ats.candidate-manager', [
            'candidates' => $query->get(),
            'openJobs' => JobPosting::with(['department', 'designation'])->where('status', 'open')->get(),
            'departments' => \App\Models\Department::all(),
        ]);
    }
}