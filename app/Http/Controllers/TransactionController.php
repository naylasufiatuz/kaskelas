<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use App\Services\ActivityLogService;
use App\Services\BalanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function __construct(
        protected ActivityLogService $activityLog,
        protected BalanceService $balance,
    ) {}

    public function index(Request $request)
    {
        $query = Transaction::with(['category', 'creator']);

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }
        if ($category = $request->get('category_id')) {
            $query->where('category_id', $category);
        }
        if ($search = $request->get('search')) {
            $query->where('description', 'like', "%{$search}%");
        }
        if ($from = $request->get('from')) {
            $query->whereDate('transaction_date', '>=', $from);
        }
        if ($to = $request->get('to')) {
            $query->whereDate('transaction_date', '<=', $to);
        }

        $transactions = $query->orderByDesc('transaction_date')->orderByDesc('id')->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('transactions.index', compact('transactions', 'categories'));
    }

    public function create(Request $request)
    {
        Gate::authorize('create', Transaction::class);

        $type = $request->get('type', 'expense');
        $categories = Category::where('type', $type)->orderBy('name')->get();

        return view('transactions.create', compact('type', 'categories'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Transaction::class);

        $data = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'category_id' => ['required', 'exists:categories,id'],
            'amount' => ['required', 'integer', 'min:1'],
            'transaction_date' => ['required', 'date'],
            'description' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        // Backend enforced: an expense can never overdraw the class balance.
        if ($data['type'] === 'expense' && $this->balance->wouldOverdraw($data['amount'])) {
            throw ValidationException::withMessages([
                'amount' => 'Saldo kelas tidak mencukupi untuk transaksi ini.',
            ]);
        }

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'local');
        }

        $transaction = Transaction::create([
            ...$data,
            'receipt_path' => $receiptPath,
            'created_by' => Auth::id(),
        ]);

        $this->activityLog->log(
            $receiptPath ? 'upload_receipt' : 'create',
            ($data['type'] === 'income' ? 'Menambahkan pemasukan: ' : 'Menambahkan pengeluaran: ') . $data['description'],
            $transaction,
            null,
            $data
        );

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil disimpan.');
    }

    public function edit(Transaction $transaction)
    {
        Gate::authorize('update', $transaction);

        $categories = Category::where('type', $transaction->type)->orderBy('name')->get();

        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        Gate::authorize('update', $transaction);

        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'amount' => ['required', 'integer', 'min:1'],
            'transaction_date' => ['required', 'date'],
            'description' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        // If this is an expense being increased, re-check balance excluding its own current amount.
        if ($transaction->type === 'expense') {
            $delta = $data['amount'] - $transaction->amount;
            if ($delta > 0 && $this->balance->wouldOverdraw($delta)) {
                throw ValidationException::withMessages([
                    'amount' => 'Saldo kelas tidak mencukupi untuk perubahan transaksi ini.',
                ]);
            }
        }

        $old = $transaction->only(['category_id', 'amount', 'transaction_date', 'description', 'notes']);

        if ($request->hasFile('receipt')) {
            if ($transaction->receipt_path) {
                Storage::disk('local')->delete($transaction->receipt_path);
            }
            $data['receipt_path'] = $request->file('receipt')->store('receipts', 'local');
        }

        $transaction->update($data);

        $this->activityLog->log('update', 'Memperbarui transaksi: ' . $transaction->description, $transaction, $old, $data);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction)
    {
        Gate::authorize('delete', $transaction);

        $old = $transaction->toArray();
        $description = $transaction->description;

        if ($transaction->receipt_path) {
            Storage::disk('local')->delete($transaction->receipt_path);
        }

        $transaction->delete();

        $this->activityLog->log('delete', 'Menghapus transaksi: ' . $description, $transaction, $old, null);

        return back()->with('success', 'Transaksi berhasil dihapus.');
    }

    public function receipt(Transaction $transaction)
    {
        Gate::authorize('view', $transaction);
        abort_unless($transaction->receipt_path, 404);

        return Storage::disk('local')->response($transaction->receipt_path);
    }
}
