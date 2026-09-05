<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_questions', function (Blueprint $table) {
            $table->string('phase')->default('iq')->after('job_posting_id');
            $table->json('options')->nullable()->after('question_type');
            $table->integer('sort_order')->default(1)->after('points');
        });
    }

    public function down(): void
    {
        Schema::table('job_questions', function (Blueprint $table) {
            $table->dropColumn(['phase', 'options', 'sort_order']);
        });
    }
};
