<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    protected $fillable = ['title', 'department_id', 'designation_id', 'description', 'status', 'deadline'];

    public function department() { return $this->belongsTo(Department::class); }
    public function designation() { return $this->belongsTo(Designation::class); }
    public function questions() { return $this->hasMany(JobQuestion::class); }
    public function applications() { return $this->hasMany(JobApplication::class); }
}