<?php

namespace App\Livewire\Candidate;

use Livewire\Component;
use App\Models\JobApplication;
use App\Models\JobQuestion;
use App\Models\JobApplicationAnswer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ExamPortal extends Component
{
    public $application;
    public $answers = []; 
    public $isTakingExam = false;

    public function mount()
    {
        // Candidate will always have exactly one application created by HR
        $this->application = JobApplication::with('jobPosting')->where('user_id', Auth::id())->first();
    }

    public function startExam()
    {
        $this->isTakingExam = true;
        
        $questions = JobQuestion::where('job_posting_id', $this->application->job_posting_id)->get();
        foreach ($questions as $q) {
            $this->answers[$q->id] = '';
        }
    }

    public function submitExam()
    {
        // Save all answers
        foreach ($this->answers as $questionId => $answerText) {
            JobApplicationAnswer::create([
                'job_application_id' => $this->application->id,
                'job_question_id' => $questionId,
                'answer_text' => $answerText,
            ]);
        }

        // Update status from 'applied' to 'screening'
        $this->application->update(['status' => 'screening']);
        
        $this->isTakingExam = false;
        session()->flash('success', 'Your online exam has been submitted successfully. We will notify you after the evaluation.');
    }

    public function render()
    {
        $questions = [];
        if ($this->isTakingExam) {
            $questions = JobQuestion::where('job_posting_id', $this->application->job_posting_id)->get();
        }

        return view('livewire.candidate.exam-portal', [
            'questions' => $questions
        ]);
    }

    // Password Change Properties
    public $isPasswordModalOpen = false;
    public $current_password, $new_password, $new_password_confirmation;

    public function openPasswordModal() { $this->isPasswordModalOpen = true; }
    public function closePasswordModal() 
    { 
        $this->isPasswordModalOpen = false; 
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
    }
    
    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|min:6|confirmed',
        ]);

        Auth::user()->update(['password' => Hash::make($this->new_password)]);
        $this->closePasswordModal();
        session()->flash('success', 'Your password has been changed successfully.');
    }
}