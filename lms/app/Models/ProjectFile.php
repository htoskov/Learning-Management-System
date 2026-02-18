<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectFile extends Model
{
    protected $fillable = [
        'project_submission_id','type','path','original_name','size','mime'
    ];

    public function submission()
    {
        return $this->belongsTo(ProjectSubmission::class, 'project_submission_id');
    }
}
