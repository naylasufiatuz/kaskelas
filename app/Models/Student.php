<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'student_number',
        'gender',
        'phone',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function cashPayments()
    {
        return $this->hasMany(CashPayment::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }
}
