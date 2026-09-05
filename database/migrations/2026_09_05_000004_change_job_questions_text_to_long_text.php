<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('job_questions') && Schema::hasColumn('job_questions', 'question_text')) {
            Schema::table('job_questions', function (Blueprint $table) {
                $table->text('question_text')->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('job_questions') && Schema::hasColumn('job_questions', 'question_text')) {
            Schema::table('job_questions', function (Blueprint $table) {
                $table->string('question_text')->change();
            });
        }
    }
};