<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'comment_id',
        'file_name',
        'path',
        'is_enable',
        'created_by',
    ];

    public function task()
    {
        return $this->belongsTo(Comment::class);
    }
}
