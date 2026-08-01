<?php

namespace App\Providers;

use App\Models\ActivityLog;
use App\Models\CashPayment;
use App\Models\Student;
use App\Models\Transaction;
use App\Policies\ActivityLogPolicy;
use App\Policies\CashPaymentPolicy;
use App\Policies\StudentPolicy;
use App\Policies\TransactionPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Student::class, StudentPolicy::class);
        Gate::policy(Transaction::class, TransactionPolicy::class);
        Gate::policy(CashPayment::class, CashPaymentPolicy::class);
        Gate::policy(ActivityLog::class, ActivityLogPolicy::class);
    }
}
