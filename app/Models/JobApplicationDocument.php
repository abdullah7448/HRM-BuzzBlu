<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplicationDocument extends Model
{
    protected $fillable = [
        'job_application_id',
        'document_type',
        'file_path',
        'file_name',
        'mime_type',
    ];

    public function application()
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }
}
