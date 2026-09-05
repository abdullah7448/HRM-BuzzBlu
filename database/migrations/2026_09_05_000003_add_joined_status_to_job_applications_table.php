<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('job_applications')) {
            return;
        }

        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE job_applications MODIFY status ENUM('applied', 'screening', 'interview', 'selected', 'joined', 'rejected') DEFAULT 'applied'");
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('job_applications')) {
            return;
        }

        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE job_applications MODIFY status ENUM('applied', 'screening', 'interview', 'selected', 'rejected') DEFAULT 'applied'");
        }
    }
};
