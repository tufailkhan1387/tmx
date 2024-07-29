<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JdTask extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'description', 'role', 'user_id', 'frequency', 'updated_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
