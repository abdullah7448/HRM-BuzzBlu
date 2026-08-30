<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplicationAnswer extends Model
{
    protected $fillable = ['job_application_id', 'job_question_id', 'answer_text'];

    public function question()
    {
        return $this->belongsTo(JobQuestion::class, 'job_question_id');
    }
}