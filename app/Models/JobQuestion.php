<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobQuestion extends Model
{
    protected $fillable = [
        'job_posting_id',
        'phase',
        'question_text',
        'question_type',
        'options',
        'expected_answer',
        'points',
        'sort_order',
    ];

    protected $casts = [
        'options' => 'array',
        'sort_order' => 'integer',
    ];

    public function jobPosting() {
        return $this->belongsTo(JobPosting::class);
    }
}