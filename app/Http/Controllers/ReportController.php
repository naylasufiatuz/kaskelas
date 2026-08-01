<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Setting;
use App\Models\Transaction;
use App\Services\BalanceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{
    public function __construct(protected BalanceService $balance) {}

    protected function resolvePeriod(Request $request): array
    {
        if ($request->filled('from') && $request->filled('to')) {
            return [Carbon::parse($request->get('from')), Carbon::parse($request->get('to'))];
        }

        if ($month = $request->get('month')) {
            $date = Carbon::parse($month . '-01');
            return [$date->copy()->startOfMonth(), $date->copy()->endOfMonth()];
        }

        $now = Carbon::now();
        return [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()];
    }

    protected function buildReport(Request $request): array
    {
        [$from, $to] = $this->resolvePeriod($request);

        $totals = $this->balance->totalsForPeriod($from, $to);

        $transactions = Transaction::with(['category', 'creator'])
            ->whereDate('transaction_date', '>=', $from)
            ->whereDate('transaction_date', '<=', $to)
            ->orderBy('transaction_date')
            ->get();

        $expenseByCategory = $transactions->where('type', 'expense')
            ->groupBy(fn ($t) => $t->category->name ?? 'Lainnya')
            ->map(fn ($group) => $group->sum('amount'))
            ->sortDesc();

        return [
            'from' => $from,
            'to' => $to,
            'totals' => $totals,
            'transactions' => $transactions,
            'expenseByCategory' => $expenseByCategory,
            'className' => Setting::get('class_name', config('kaskelas.class_name')),
        ];
    }

    public function index(Request $request)
    {
        return view('reports.index', $this->buildReport($request));
    }

    public function exportPdf(Request $request)
    {
        Gate::authorize('export', Transaction::class);

        $data = $this->buildReport($request);
        $pdf = Pdf::loadView('reports.pdf', $data)->setPaper('a4');

        $filename = 'Laporan-KasKelas-' . $data['from']->format('Y-m-d') . '_' . $data['to']->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}
