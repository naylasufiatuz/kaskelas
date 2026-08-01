<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashPayment extends Model
{
    use HasFactory;

    public const STATUS_PAID = 'paid';
    public const STATUS_UNPAID = 'unpaid';
    public const STATUS_PARTIAL = 'partial';

    protected $fillable = [
        'student_id',
        'week_start',
        'amount',
        'paid_at',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'week_start' => 'date',
            'paid_at' => 'datetime',
            'amount' => 'integer',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }
}
