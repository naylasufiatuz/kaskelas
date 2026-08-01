<?php

namespace App\Http\Controllers;

use App\Models\CashPayment;
use App\Models\Category;
use App\Models\Student;
use App\Models\Transaction;
use App\Services\BalanceService;
use App\Services\WeekService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        protected BalanceService $balance,
        protected WeekService $weekService,
    ) {}

    public function index()
    {
        $user = Auth::user();

        if ($user->isStudent()) {
            return $this->studentDashboard();
        }

        return $this->staffDashboard();
    }

    protected function staffDashboard()
    {
        $activeStudentCount = Student::active()->count();
        $weekStart = $this->weekService->currentWeekStart();
        $weeklyAmount = $this->weekService->weeklyAmount();

        $paidCount = CashPayment::where('week_start', $weekStart->toDateString())
            ->where('status', CashPayment::STATUS_PAID)
            ->count();

        $target = $activeStudentCount * $weeklyAmount;
        $collected = $paidCount * $weeklyAmount;
        $progress = $target > 0 ? round(($collected / $target) * 100, 1) : 0;

        $recentTransactions = Transaction::with(['category', 'creator'])
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        $expenseByCategory = Transaction::expense()
            ->join('categories', 'categories.id', '=', 'transactions.category_id')
            ->selectRaw('categories.name as category, SUM(transactions.amount) as total')
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->get();

        $monthlyChart = Transaction::selectRaw("DATE_FORMAT(transaction_date, '%Y-%m') as ym, type, SUM(amount) as total")
            ->groupBy('ym', 'type')
            ->orderBy('ym')
            ->get()
            ->groupBy('ym');

        return view('dashboard.staff', [
            'balance' => $this->balance->currentBalance(),
            'totalIncome' => $this->balance->totalIncome(),
            'totalExpense' => $this->balance->totalExpense(),
            'totalStudents' => $activeStudentCount,
            'weekLabel' => $this->weekService->weekLabel($weekStart),
            'target' => $target,
            'collected' => $collected,
            'shortfall' => max($target - $collected, 0),
            'paidCount' => $paidCount,
            'progress' => $progress,
            'recentTransactions' => $recentTransactions,
            'expenseByCategory' => $expenseByCategory,
            'monthlyChart' => $monthlyChart,
            'hasStudents' => $activeStudentCount > 0,
        ]);
    }

    protected function studentDashboard()
    {
        $user = Auth::user();
        $weekStart = $this->weekService->currentWeekStart();
        $weeklyAmount = $this->weekService->weeklyAmount();

        $myPayment = null;
        if ($user->student_id) {
            $myPayment = CashPayment::where('student_id', $user->student_id)
                ->where('week_start', $weekStart->toDateString())
                ->first();
        }

        return view('dashboard.student', [
            'balance' => $this->balance->currentBalance(),
            'totalIncome' => $this->balance->totalIncome(),
            'totalExpense' => $this->balance->totalExpense(),
            'weekLabel' => $this->weekService->weekLabel($weekStart),
            'weeklyAmount' => $weeklyAmount,
            'paidAmount' => $myPayment && $myPayment->isPaid() ? $myPayment->amount : 0,
            'isPaid' => $myPayment?->isPaid() ?? false,
        ]);
    }
}
