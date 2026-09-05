<?php

namespace App\Livewire\Candidate;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\JobApplication;
use App\Models\JobApplicationDocument;
use App\Models\JobQuestion;
use App\Models\JobApplicationAnswer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExamPortal extends Component
{
    use WithFileUploads;

    public $applicationId;
    public $applicationStep;
    public $currentPhase = 'iq';
    public $answers = [];
    public $isTakingExam = false;
    public $cv;
    public $nid_or_birth_certificate;
    public $education_certificate;

    public function mount()
    {
        $this->applicationId = JobApplication::where('user_id', Auth::id())->value('id');
        $this->applicationStep = JobApplication::where('user_id', Auth::id())->value('application_step') ?? 'documents';
        $this->currentPhase = in_array($this->applicationStep, ['iq', 'departmental', 'rules'], true)
            ? $this->applicationStep
            : 'iq';
    }

    protected function getApplication(): ?JobApplication
    {
        if (!$this->applicationId) {
            return null;
        }

        return JobApplication::with(['jobPosting', 'documents'])->find($this->applicationId);
    }

    protected function ensureOfficeRules(JobApplication $application): void
    {
        if (!JobQuestion::where('job_posting_id', $application->job_posting_id)->where('phase', 'rules')->exists()) {
            JobQuestion::create([
                'job_posting_id' => $application->job_posting_id,
                'phase' => 'rules',
                'question_text' => (string) config('ats.office_rules'),
                'question_type' => 'yes_no',
                'options' => null,
                'expected_answer' => 'Yes',
                'points' => 0,
                'sort_order' => (int) JobQuestion::where('job_posting_id', $application->job_posting_id)->max('sort_order') + 1,
            ]);
        }
    }

    public function uploadDocuments(array $documents = null)
    {
        if ($documents === null) {
            $documents = [
                'cv' => $this->cv,
                'nid_or_birth_certificate' => $this->nid_or_birth_certificate,
                'education_certificate' => $this->education_certificate,
            ];
        }

        $this->validate([
            'cv' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'nid_or_birth_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'education_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $application = $this->getApplication();
        if (!$application) {
            session()->flash('error', 'No application found for this candidate.');
            return;
        }

        foreach ($documents as $type => $file) {
            if (!$file) {
                continue;
            }

            $path = $file->storeAs('candidate-documents/' . $application->id, uniqid() . '-' . $file->getClientOriginalName(), 'public');

            JobApplicationDocument::create([
                'job_application_id' => $application->id,
                'document_type' => $type,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
            ]);
        }

        $application->update([
            'application_step' => 'iq',
            'status' => 'applied',
        ]);

        $this->applicationStep = 'iq';
        $this->currentPhase = 'iq';
        $this->reset(['cv', 'nid_or_birth_certificate', 'education_certificate']);
        session()->flash('success', 'Your required documents have been uploaded successfully. You can now proceed to the assessment.');
    }

    public function startExam()
    {
        $application = $this->getApplication();
        if (!$application) {
            return;
        }

        $this->ensureOfficeRules($application);
        $this->currentPhase = in_array($application->application_step, ['iq', 'departmental', 'rules'], true)
            ? $application->application_step
            : 'iq';
        $application->update(['application_step' => $this->currentPhase]);
        $this->applicationStep = $this->currentPhase;
        $this->isTakingExam = true;

        $questions = JobQuestion::where('job_posting_id', $application->job_posting_id)
            ->where('phase', $this->currentPhase)
            ->get();
        foreach ($questions as $q) {
            $this->answers[$q->id] = $this->answers[$q->id] ?? '';
        }
    }

    public function continueExam()
    {
        $application = $this->getApplication();
        if (!$application) {
            return;
        }

        $questions = JobQuestion::where('job_posting_id', $application->job_posting_id)
            ->where('phase', $this->currentPhase)
            ->get();

        foreach ($questions as $question) {
            $answer = trim((string) ($this->answers[$question->id] ?? ''));
            if ($answer === '') {
                $this->addError('answers.' . $question->id, 'Please answer this question before continuing.');
            }

            if ($question->phase === 'rules' && $answer !== 'Yes') {
                $this->addError('answers.' . $question->id, 'You must select "I agree" to continue.');
            }
        }

        if ($this->getErrorBag()->isNotEmpty()) {
            return;
        }

        foreach ($questions as $question) {
            JobApplicationAnswer::updateOrCreate(
                [
                    'job_application_id' => $application->id,
                    'job_question_id' => $question->id,
                ],
                ['answer_text' => $this->answers[$question->id]]
            );
        }

        $nextPhase = match ($this->currentPhase) {
            'iq' => 'departmental',
            'departmental' => 'rules',
            default => 'submitted',
        };

        if ($nextPhase === 'submitted') {
            $application->update([
                'application_step' => 'submitted',
                'status' => 'screening',
            ]);
            $this->isTakingExam = false;
            $this->applicationStep = 'submitted';
            session()->flash('success', 'Your assessment has been submitted successfully. Your application is now under evaluation.');
            return;
        }

        $this->currentPhase = $nextPhase;
        $this->applicationStep = $nextPhase;
        $application->update(['application_step' => $nextPhase]);
        $nextQuestions = JobQuestion::where('job_posting_id', $application->job_posting_id)
            ->where('phase', $nextPhase)
            ->get();
        foreach ($nextQuestions as $question) {
            $this->answers[$question->id] = $this->answers[$question->id] ?? '';
        }
        session()->flash('success', ucfirst($nextPhase) . ' stage completed. Continue to the next stage.');
    }

    public function submitExam()
    {
        $this->continueExam();
    }

    public function render()
    {
        $application = $this->getApplication();
        $questions = [];
        if ($this->isTakingExam && $application) {
            $questions = JobQuestion::where('job_posting_id', $application->job_posting_id)
                ->where('phase', $this->currentPhase)
                ->get();
        }

        $this->applicationStep = $application ? ($application->application_step ?? 'documents') : 'documents';

        return view('livewire.candidate.exam-portal', [
            'application' => $application,
            'applicationStep' => $this->applicationStep,
            'questions' => $questions,
        ]);
    }

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