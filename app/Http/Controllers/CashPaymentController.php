<?php

namespace App\Http\Controllers;

use App\Models\CashPayment;
use App\Models\Category;
use App\Models\Student;
use App\Models\Transaction;
use App\Services\ActivityLogService;
use App\Services\WeekService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CashPaymentController extends Controller
{
    public function __construct(
        protected WeekService $weekService,
        protected ActivityLogService $activityLog,
    ) {}

    public function index(Request $request)
    {
        Gate::authorize('viewAny', CashPayment::class);

        $weeks = $this->weekService->generateWeeks();
        $weekStart = $request->filled('week')
            ? Carbon::parse($request->get('week'))
            : $this->weekService->currentWeekStart();

        $weeklyAmount = $this->weekService->weeklyAmount();

        $students = Student::active()->orderBy('name')->get();
        $payments = CashPayment::where('week_start', $weekStart->toDateString())
            ->get()
            ->keyBy('student_id');

        $rows = $students->map(function (Student $student) use ($payments) {
            $payment = $payments->get($student->id);
            return [
                'student' => $student,
                'payment' => $payment,
                'status' => $payment->status ?? CashPayment::STATUS_UNPAID,
            ];
        });

        $paidCount = $rows->where('status', CashPayment::STATUS_PAID)->count();

        return view('cash-payments.index', [
            'weeks' => $weeks,
            'weekStart' => $weekStart,
            'weekLabel' => $this->weekService->weekLabel($weekStart),
            'weeklyAmount' => $weeklyAmount,
            'rows' => $rows,
            'target' => $students->count() * $weeklyAmount,
            'collected' => $paidCount * $weeklyAmount,
            'paidCount' => $paidCount,
            'totalStudents' => $students->count(),
        ]);
    }

    /** Student's own read-only view of their cash payment history. IDOR-safe: always scoped to Auth::id(). */
    public function mine()
    {
        $user = Auth::user();
        abort_unless($user->isStudent() && $user->student_id, 403);

        $payments = CashPayment::where('student_id', $user->student_id)
            ->orderByDesc('week_start')
            ->paginate(20);

        return view('cash-payments.mine', compact('payments'));
    }

    public function markPaid(Request $request, Student $student)
    {
        Gate::authorize('manage', CashPayment::class);

        $weekStart = Carbon::parse($request->get('week_start', $this->weekService->currentWeekStart()));
        $weeklyAmount = $this->weekService->weeklyAmount();

        DB::transaction(function () use ($student, $weekStart, $weeklyAmount) {
            $payment = CashPayment::firstOrNew([
                'student_id' => $student->id,
                'week_start' => $weekStart->toDateString(),
            ]);

            if ($payment->exists && $payment->isPaid()) {
                return; // already paid - never double count
            }

            $payment->amount = $weeklyAmount;
            $payment->status = CashPayment::STATUS_PAID;
            $payment->paid_at = now();
            $payment->save();

            // Guarantee exactly one income transaction per cash payment.
            if (! $payment->transaction()->exists()) {
                $category = Category::firstOrCreate(['name' => 'Kas Mingguan', 'type' => 'income']);

                Transaction::create([
                    'type' => Transaction::TYPE_INCOME,
                    'category_id' => $category->id,
                    'amount' => $weeklyAmount,
                    'transaction_date' => now()->toDateString(),
                    'description' => 'Kas minggu ' . $weekStart->translatedFormat('d F Y') . ' - ' . $student->name,
                    'created_by' => Auth::id(),
                    'cash_payment_id' => $payment->id,
                ]);
            }

            $this->activityLog->log('payment', "Menandai lunas kas {$student->name} - minggu {$weekStart->toDateString()}", $payment);
        });

        return back()->with('success', 'Pembayaran kas berhasil dicatat.');
    }

    public function markUnpaid(Request $request, Student $student)
    {
        Gate::authorize('manage', CashPayment::class);

        $weekStart = Carbon::parse($request->get('week_start', $this->weekService->currentWeekStart()));

        DB::transaction(function () use ($student, $weekStart) {
            $payment = CashPayment::where('student_id', $student->id)
                ->where('week_start', $weekStart->toDateString())
                ->first();

            if (! $payment) {
                return;
            }

            // Remove the linked income transaction so the balance stays accurate.
            $payment->transaction()->delete();

            $payment->status = CashPayment::STATUS_UNPAID;
            $payment->amount = 0;
            $payment->paid_at = null;
            $payment->save();

            $this->activityLog->log('update', "Menandai belum lunas kas {$student->name} - minggu {$weekStart->toDateString()}", $payment);
        });

        return back()->with('success', 'Status pembayaran diperbarui.');
    }

    public function destroy(CashPayment $cashPayment)
    {
        Gate::authorize('manage', CashPayment::class);

        DB::transaction(function () use ($cashPayment) {
            $cashPayment->transaction()->delete();
            $name = $cashPayment->student->name ?? '-';
            $cashPayment->delete();

            $this->activityLog->log('delete', "Menghapus pembayaran kas: {$name}", $cashPayment);
        });

        return back()->with('success', 'Pembayaran kas berhasil dihapus.');
    }
}
