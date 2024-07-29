<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'file_name',
        'path',
        'is_enable',
        'created_by',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
