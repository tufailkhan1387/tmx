<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'scope',
        'company_id',
        'password',
        'profile_pic',
        'joining_date',
        'expiry_date',
        'start_time',
        'end_time',
        'phone',
        'whatsapp',
        'created_by',
        'updated_by',
        'department_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_user');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function forget_password_otp()
    {
        return $this->hasOne(ForgetPasswordOtp::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'created_by');
    }
}
