<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('job_questions')) {
            return;
        }

        Schema::table('job_questions', function (Blueprint $table) {
            if (!Schema::hasColumn('job_questions', 'phase')) {
                $table->string('phase')->default('iq')->after('job_posting_id');
            }

            if (!Schema::hasColumn('job_questions', 'options')) {
                $table->json('options')->nullable()->after('question_type');
            }

            if (!Schema::hasColumn('job_questions', 'sort_order')) {
                $table->integer('sort_order')->default(1)->after('points');
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('job_questions')) {
            Schema::table('job_questions', function (Blueprint $table) {
                if (Schema::hasColumn('job_questions', 'phase')) {
                    $table->dropColumn('phase');
                }

                if (Schema::hasColumn('job_questions', 'options')) {
                    $table->dropColumn('options');
                }

                if (Schema::hasColumn('job_questions', 'sort_order')) {
                    $table->dropColumn('sort_order');
                }
            });
        }
    }
};
