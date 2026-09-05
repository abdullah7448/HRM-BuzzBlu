<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Designation;
use App\Models\JobPosting;
use App\Models\JobQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AtsWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_question_can_store_phase_options_and_serial_order(): void
    {
        $department = Department::factory()->create();
        $designation = Designation::factory()->create(['department_id' => $department->id]);
        $job = JobPosting::create([
            'title' => 'Senior Designer',
            'department_id' => $department->id,
            'designation_id' => $designation->id,
            'description' => 'Test role',
            'deadline' => now()->addDays(10)->toDateString(),
            'status' => 'open',
        ]);

        $question = JobQuestion::create([
            'job_posting_id' => $job->id,
            'phase' => 'iq',
            'question_text' => 'Which color is blue?',
            'question_type' => 'multiple_choice',
            'options' => ['Red', 'Blue', 'Green'],
            'expected_answer' => 'Blue',
            'points' => 10,
            'sort_order' => 1,
        ]);

        $this->assertSame('iq', $question->phase);
        $this->assertSame(['Red', 'Blue', 'Green'], $question->options);
        $this->assertSame(1, $question->sort_order);
    }

    public function test_candidate_directory_filters_by_phone_and_department(): void
    {
        Role::create(['name' => 'Candidate']);

        $department = Department::factory()->create(['name' => 'Engineering']);
        $designation = Designation::factory()->create(['department_id' => $department->id, 'name' => 'Developer']);

        $candidate = User::factory()->create([
            'name' => 'Test Candidate',
            'email' => 'candidate@example.com',
            'phone' => '01712345678',
            'department_id' => $department->id,
            'designation_id' => $designation->id,
        ]);
        $candidate->assignRole('Candidate');

        $job = JobPosting::create([
            'title' => 'Developer',
            'department_id' => $department->id,
            'designation_id' => $designation->id,
            'description' => 'Test',
            'deadline' => now()->addDays(7)->toDateString(),
            'status' => 'open',
        ]);

        \App\Models\JobApplication::create([
            'job_posting_id' => $job->id,
            'user_id' => $candidate->id,
            'status' => 'applied',
        ]);

        Livewire::test(\App\Livewire\Ats\CandidateManager::class)
            ->set('search', '017123')
            ->set('department_filter', $department->id)
            ->assertSet('candidates.0.name', 'Test Candidate');
    }
}
