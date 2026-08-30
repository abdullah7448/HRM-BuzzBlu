<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up(): void
    {
        Schema::create('job_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_posting_id')->constrained()->cascadeOnDelete();
            $table->string('question_text');
            $table->string('question_type'); // e.g., 'text', 'yes_no', 'multiple_choice'
            $table->string('expected_answer')->nullable(); // Used for Match Score calculation
            $table->integer('points')->default(10); // Weight of this question for the total score
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_questions');
    }
};
