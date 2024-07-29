<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskTimeTracking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'task_id',
        'summary',
        'date',
        'time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function getFormattedDateAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['date'])->format('d-m-Y');
    }

    public function getFormattedTimeAttribute() {
        $minutes = $this->attributes['time'];
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        return sprintf('%dH %02dM', $hours, $remainingMinutes);

        // 1 day equal to 480 min 8hour duty
        // $minutes = $this->attributes['time'];
        // $days = floor($minutes / 480);
        // $hours = floor(($minutes % 480) / 60);
        // $remainingMinutes = $minutes % 60;
        // return sprintf('%dD %02dH %02dM', $days, $hours, $remainingMinutes);
    }
}
