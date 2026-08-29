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
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('leave_type'); // e.g., Sick, Casual, Annual
            $table->date('start_date');
            $table->date('end_date');
            $table->text('reason');
            
            // This tracks the multi-step approval workflow
            $table->enum('status', [
                'pending_head_approval', 
                'pending_hr_approval', 
                'approved', 
                'rejected'
            ])->default('pending_head_approval');
            
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
