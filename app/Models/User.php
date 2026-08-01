<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_TREASURER = 'treasurer';
    public const ROLE_CLASS_LEADER = 'class_leader';
    public const ROLE_STUDENT = 'student';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'student_id',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'must_change_password' => 'boolean',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'created_by');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function isTreasurer(): bool
    {
        return $this->role === self::ROLE_TREASURER;
    }

    public function isClassLeader(): bool
    {
        return $this->role === self::ROLE_CLASS_LEADER;
    }

    public function isStudent(): bool
    {
        return $this->role === self::ROLE_STUDENT;
    }

    /** Treasurer and class leader can both view financial data read-only+. */
    public function canManageFinance(): bool
    {
        return $this->isTreasurer();
    }
}
