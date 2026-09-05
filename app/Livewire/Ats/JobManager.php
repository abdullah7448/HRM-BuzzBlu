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
    public $showCandidatePreview = false;

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

    public function updatedQuestions($value, $key)
    {
        [$index, $field] = array_pad(explode('.', (string) $key, 2), 2, null);

        if ($field === 'phase' && isset($this->questions[$index]) && $value === 'rules') {
            $this->questions[$index]['question_type'] = 'yes_no';
            $this->questions[$index]['options'] = [];
            $this->questions[$index]['expected_answer'] = 'Yes';
            $this->questions[$index]['points'] = 0;
        }
    }

    public function addQuestion()
    {
        $this->questions[] = [
            'phase' => 'iq',
            'question_text' => '',
            'question_type' => 'multiple_choice',
            'options' => ['', '', '', ''],
            'expected_answer' => '',
            'points' => 10,
            'sort_order' => count($this->questions) + 1,
        ];
    }

    protected function officeRulesText(): string
    {
        return (string) config('ats.office_rules');
    }

    public function updateQuestionType($index, $type)
    {
        if (!isset($this->questions[$index])) {
            return;
        }

        $this->questions[$index]['question_type'] = $type;

        if ($type === 'yes_no') {
            $this->questions[$index]['options'] = ['Yes', 'No'];
            $this->questions[$index]['expected_answer'] = 'Yes';
        } elseif ($type === 'multiple_choice') {
            $this->questions[$index]['options'] = array_pad($this->questions[$index]['options'] ?? [], 4, '');
            $this->questions[$index]['expected_answer'] = '';
        }
    }

    public function toggleCandidatePreview()
    {
        $this->showCandidatePreview = !$this->showCandidatePreview;
    }

    public function removeQuestion($index)
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
        foreach ($this->questions as $i => $question) {
            $this->questions[$i]['sort_order'] = $i + 1;
        }
    }

    public function reorderQuestions($fromIndex, $toIndex)
    {
        if (!isset($this->questions[$fromIndex]) || !isset($this->questions[$toIndex])) {
            return;
        }

        $moved = $this->questions[$fromIndex];
        array_splice($this->questions, $fromIndex, 1);
        array_splice($this->questions, $toIndex, 0, [$moved]);

        foreach ($this->questions as $i => $question) {
            $this->questions[$i]['sort_order'] = $i + 1;
        }
    }

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
        foreach ($job->questions->sortBy('sort_order') as $q) {
            $this->questions[] = [
                'id' => $q->id,
                'phase' => $q->phase ?: 'iq',
                'question_text' => $q->question_text,
                'question_type' => $q->question_type,
                'options' => $q->options ?? ['', '', '', ''],
                'expected_answer' => $q->expected_answer,
                'points' => $q->points,
                'sort_order' => $q->sort_order ?? 1,
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
            'questions' => 'required|array|min:1',
            'questions.*.phase' => 'required|in:iq,departmental,rules',
            'questions.*.question_text' => 'required|string',
            'questions.*.question_type' => 'required|in:text,yes_no,multiple_choice',
            'questions.*.points' => 'required|integer|min:0',
            'questions.*.options' => 'nullable|array',
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

        foreach ($this->questions as $index => $question) {
            $isRulesAgreement = ($question['phase'] ?? null) === 'rules';
            $options = null;
            if (!$isRulesAgreement && ($question['question_type'] ?? 'text') === 'multiple_choice') {
                $options = array_values(array_filter(array_map(function ($option) {
                    return trim((string) $option);
                }, $question['options'] ?? []), fn ($option) => $option !== ''));
            }

            JobQuestion::create([
                'job_posting_id' => $job->id,
                'phase' => $question['phase'],
                'question_text' => $isRulesAgreement ? $this->officeRulesText() : $question['question_text'],
                'question_type' => $isRulesAgreement ? 'yes_no' : $question['question_type'],
                'options' => $options,
                'expected_answer' => $isRulesAgreement ? 'Yes' : ($question['expected_answer'] ?? null),
                'points' => $isRulesAgreement ? 0 : (int) $question['points'],
                'sort_order' => $index + 1,
            ]);
        }

        if (!collect($this->questions)->contains('phase', 'rules')) {
            JobQuestion::create([
                'job_posting_id' => $job->id,
                'phase' => 'rules',
                'question_text' => $this->officeRulesText(),
                'question_type' => 'yes_no',
                'options' => null,
                'expected_answer' => 'Yes',
                'points' => 0,
                'sort_order' => count($this->questions) + 1,
            ]);
        }

        $this->cancelEdit();
        $this->successMessage = $this->isEditMode ? 'Job & Questions updated!' : 'Job created successfully!';
    }

    public function cancelEdit()
    {
        $this->reset(['title', 'department_id', 'designation_id', 'description', 'deadline', 'status', 'isEditMode', 'editJobId', 'showCandidatePreview']);
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