<?php

namespace App\Livewire\Ats;

use Livewire\Component;
use App\Models\Department;
use App\Models\Designation;
use App\Models\JobPosting;
use App\Models\JobQuestion;

class JobManager extends Component
{
    public $title, $department_id, $designation_id, $description, $deadline;
    public $status = 'open';
    public $departments = [], $designations = [];
    public $questions = []; 
    public $successMessage = '';
    
    public $isEditMode = false;
    public $editJobId = null;

    public function mount()
    {
        $this->departments = Department::all();
        $this->addQuestion();
    }

    public function updatedDepartmentId($value)
    {
        $this->designations = Designation::where('department_id', $value)->get();
        $this->designation_id = null;
    }

    public function addQuestion()
    {
        $this->questions[] = ['question_text' => '', 'question_type' => 'text', 'expected_answer' => '', 'points' => 10];
    }

    public function removeQuestion($index)
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
    }

    // Load Job and Questions into Form for Editing
    public function editJob($id)
    {
        $this->isEditMode = true;
        $this->editJobId = $id;
        
        $job = JobPosting::with('questions')->findOrFail($id);
        $this->title = $job->title;
        $this->department_id = $job->department_id;
        $this->designations = Designation::where('department_id', $job->department_id)->get();
        $this->designation_id = $job->designation_id;
        $this->description = $job->description;
        $this->deadline = $job->deadline;
        $this->status = $job->status;

        $this->questions = [];
        foreach ($job->questions as $q) {
            $this->questions[] = [
                'id' => $q->id,
                'question_text' => $q->question_text,
                'question_type' => $q->question_type,
                'expected_answer' => $q->expected_answer,
                'points' => $q->points,
            ];
        }
    }

    public function saveJob()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'description' => 'required|string',
            'deadline' => 'required|date',
            'questions.*.question_text' => 'required|string',
        ]);

        if ($this->isEditMode) {
            $job = JobPosting::findOrFail($this->editJobId);
            $job->update([
                'title' => $this->title,
                'department_id' => $this->department_id,
                'designation_id' => $this->designation_id,
                'description' => $this->description,
                'deadline' => $this->deadline,
                'status' => $this->status,
            ]);
            // Delete old questions and recreate (simple sync approach)
            JobQuestion::where('job_posting_id', $job->id)->delete();
        } else {
            $job = JobPosting::create([
                'title' => $this->title,
                'department_id' => $this->department_id,
                'designation_id' => $this->designation_id,
                'description' => $this->description,
                'deadline' => $this->deadline,
                'status' => $this->status,
            ]);
        }

        foreach ($this->questions as $q) {
            JobQuestion::create([
                'job_posting_id' => $job->id,
                'question_text' => $q['question_text'],
                'question_type' => $q['question_type'],
                'expected_answer' => $q['expected_answer'] ?? null,
                'points' => $q['points'],
            ]);
        }

        $this->cancelEdit();
        $this->successMessage = $this->isEditMode ? 'Job & Questions updated!' : 'Job created successfully!';
    }

    public function cancelEdit()
    {
        $this->reset(['title', 'department_id', 'designation_id', 'description', 'deadline', 'status', 'isEditMode', 'editJobId']);
        $this->questions = [];
        $this->addQuestion();
    }

    public function render()
    {
        return view('livewire.ats.job-manager', [
            'jobs' => JobPosting::with(['department', 'designation'])->latest()->get()
        ])->layout('layouts.app');
    }
}