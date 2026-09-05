<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Designation;
use App\Models\JobApplication;
use App\Models\JobApplicationDocument;
use App\Models\JobPosting;
use App\Models\JobQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Illuminate\Http\UploadedFile;
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

    public function test_candidate_gets_system_office_rules_automatically(): void
    {
        Role::create(['name' => 'Candidate']);

        $department = Department::factory()->create();
        $designation = Designation::factory()->create(['department_id' => $department->id]);
        $job = JobPosting::create([
            'title' => 'System Rules Test',
            'department_id' => $department->id,
            'designation_id' => $designation->id,
            'description' => 'Test role',
            'deadline' => now()->addDays(10)->toDateString(),
            'status' => 'open',
        ]);
        $candidate = User::factory()->create(['department_id' => $department->id, 'designation_id' => $designation->id]);
        $candidate->assignRole('Candidate');
        $application = JobApplication::create([
            'job_posting_id' => $job->id,
            'user_id' => $candidate->id,
            'status' => 'applied',
            'application_step' => 'iq',
        ]);

        $this->actingAs($candidate);
        Livewire::test(\App\Livewire\Candidate\ExamPortal::class)
            ->set('applicationId', $application->id)
            ->call('startExam');

        $this->assertDatabaseHas('job_questions', [
            'job_posting_id' => $job->id,
            'phase' => 'rules',
            'question_type' => 'yes_no',
            'expected_answer' => 'Yes',
            'points' => 0,
        ]);
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

    public function test_candidate_can_upload_required_documents_before_exam(): void
    {
        Role::create(['name' => 'Candidate']);

        $department = Department::factory()->create();
        $designation = Designation::factory()->create(['department_id' => $department->id]);
        $job = JobPosting::create([
            'title' => 'Designer',
            'department_id' => $department->id,
            'designation_id' => $designation->id,
            'description' => 'Design role',
            'deadline' => now()->addDays(10)->toDateString(),
            'status' => 'open',
        ]);

        $candidate = User::factory()->create([
            'name' => 'Document Candidate',
            'email' => 'docs@example.com',
            'department_id' => $department->id,
            'designation_id' => $designation->id,
        ]);
        $candidate->assignRole('Candidate');

        $application = JobApplication::create([
            'job_posting_id' => $job->id,
            'user_id' => $candidate->id,
            'status' => 'applied',
            'application_step' => 'documents',
        ]);

        $this->actingAs($candidate);

        $component = new \App\Livewire\Candidate\ExamPortal();
        $component->applicationId = $application->id;
        $component->applicationStep = 'documents';
        $component->cv = UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf');
        $component->nid_or_birth_certificate = UploadedFile::fake()->create('nid.pdf', 100, 'application/pdf');
        $component->education_certificate = UploadedFile::fake()->create('education.pdf', 100, 'application/pdf');

        $component->uploadDocuments();

        $this->assertSame('iq', $application->fresh()->application_step);
        $this->assertEquals(3, JobApplicationDocument::where('job_application_id', $application->id)->count());
    }

    public function test_rules_agreement_question_uses_final_consent_ui(): void
    {
        Role::create(['name' => 'Candidate']);

        $department = Department::factory()->create();
        $designation = Designation::factory()->create(['department_id' => $department->id]);
        $job = JobPosting::create([
            'title' => 'Operations Lead',
            'department_id' => $department->id,
            'designation_id' => $designation->id,
            'description' => 'Operations',
            'deadline' => now()->addDays(12)->toDateString(),
            'status' => 'open',
        ]);

        $candidate = User::factory()->create([
            'name' => 'Agreement Candidate',
            'email' => 'agreement@example.com',
            'department_id' => $department->id,
            'designation_id' => $designation->id,
        ]);
        $candidate->assignRole('Candidate');

        $application = JobApplication::create([
            'job_posting_id' => $job->id,
            'user_id' => $candidate->id,
            'status' => 'applied',
            'application_step' => 'questions',
        ]);

        JobQuestion::create([
            'job_posting_id' => $job->id,
            'phase' => 'rules',
            'question_text' => 'BuzzBlu Office Rules & Code of Conduct: I agree to comply with confidentiality, attendance, workplace conduct, and safety policies.',
            'question_type' => 'yes_no',
            'options' => ['Yes', 'No'],
            'expected_answer' => 'Yes',
            'points' => 10,
            'sort_order' => 1,
        ]);

        $this->actingAs($candidate);

        Livewire::test(\App\Livewire\Candidate\ExamPortal::class)
            ->call('startExam')
            ->set('currentPhase', 'rules')
            ->set('applicationStep', 'rules')
            ->assertSee('BuzzBlu Office Rules & Code of Conduct')
            ->assertSee('I have read and I understand');
    }
}
