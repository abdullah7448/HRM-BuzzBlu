<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobQuestion extends Model
{
    protected $fillable = ['job_posting_id', 'question_text', 'question_type', 'expected_answer', 'points'];

    public function jobPosting() {
        return $this->belongsTo(JobPosting::class);
    }
}