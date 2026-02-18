<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectSubmission extends Model
{
    protected $fillable = [
        'user_id','status','note','links_json','submitted_at','reviewed_at','review_note'
    ];

    protected $casts = [
        'links_json' => 'array',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function files()
    {
        return $this->hasMany(ProjectFile::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
