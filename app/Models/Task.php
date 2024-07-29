<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'status',
    ];

    protected static function booted()
    {
        static::addGlobalScope('company', function (Builder $builder) {
            if (!system_role()) {
                $builder->where('company_id', Auth::user()->company_id);
            }
        });
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'task_user');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function tracking()
    {
        return $this->hasMany(TaskTimeTracking::class);
    }

    // accessor for formatted created_at
    public function getFormattedCreatedAtAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['created_at'])->format('d-m-Y h:i A');
    }

    public function getFormattedStartDateAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['start_date'])->format('d-m-Y');
    }

    public function getFormattedEndDateAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['end_date'])->format('d-m-Y');
    }

    public function getFormattedClosedDateAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['closed_date'])->format('d-m-Y');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function getTotalTimeAttribute()
    {
        // $totalMinutes = $this->tracking->sum('time');
        // $hours = floor($totalMinutes / 60);
        // $remainingMinutes = $totalMinutes % 60;
        // return sprintf('%dH %02dM', $hours, $remainingMinutes);

        // 1 day equal to 480 min 8hour duty
        $totalMinutes = $this->tracking->sum('time');
        $days = floor($totalMinutes / 480);
        $hours = floor(($totalMinutes % 480) / 60);
        $remainingMinutes = $totalMinutes % 60;
        return sprintf('%dD %02dH %02dM', $days, $hours, $remainingMinutes);
    }
}
