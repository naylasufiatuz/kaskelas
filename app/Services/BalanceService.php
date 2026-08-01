<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Carbon;

class BalanceService
{
    public function totalIncome(): int
    {
        return (int) Transaction::income()->sum('amount');
    }

    public function totalExpense(): int
    {
        return (int) Transaction::expense()->sum('amount');
    }

    /** Current balance = total income - total expense. Never stored, always computed. */
    public function currentBalance(): int
    {
        return $this->totalIncome() - $this->totalExpense();
    }

    public function totalsForPeriod(?Carbon $from, ?Carbon $to): array
    {
        $incomeQuery = Transaction::income();
        $expenseQuery = Transaction::expense();

        if ($from) {
            $incomeQuery->whereDate('transaction_date', '>=', $from);
            $expenseQuery->whereDate('transaction_date', '>=', $from);
        }
        if ($to) {
            $incomeQuery->whereDate('transaction_date', '<=', $to);
            $expenseQuery->whereDate('transaction_date', '<=', $to);
        }

        $income = (int) $incomeQuery->sum('amount');
        $expense = (int) $expenseQuery->sum('amount');

        return [
            'income' => $income,
            'expense' => $expense,
            'net' => $income - $expense,
        ];
    }

    public function wouldOverdraw(int $expenseAmount): bool
    {
        return $this->currentBalance() - $expenseAmount < 0;
    }
}
