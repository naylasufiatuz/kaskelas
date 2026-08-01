<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Transaction extends Model
{
    use HasFactory;

    public const TYPE_INCOME = 'income';
    public const TYPE_EXPENSE = 'expense';

    protected $fillable = [
        'type',
        'category_id',
        'amount',
        'transaction_date',
        'description',
        'notes',
        'receipt_path',
        'created_by',
        'cash_payment_id',
    ];

    protected function casts(): array
    {
        return [
            'transaction_date' => 'date',
            'amount' => 'integer',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function cashPayment()
    {
        return $this->belongsTo(CashPayment::class);
    }

    public function scopeIncome(Builder $query): Builder
    {
        return $query->where('transactions.type', self::TYPE_INCOME);
    }

    public function scopeExpense(Builder $query): Builder
    {
        return $query->where('transactions.type', self::TYPE_EXPENSE);
    }
}