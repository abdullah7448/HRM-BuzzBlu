<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    // Add this line to allow mass assignment
    protected $fillable = ['job_posting_id', 'user_id', 'match_score', 'status'];

    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}