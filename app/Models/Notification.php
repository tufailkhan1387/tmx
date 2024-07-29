<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id', 
        'title',
        'message',
        'user_id', 
        'is_read',
        'created_by'
    ];
}
