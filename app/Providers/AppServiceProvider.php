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
        // Render (and most PaaS proxies) terminate SSL before the request reaches
        // this container, so Laravel would otherwise think every request is plain
        // HTTP and generate http:// links for CSS/JS - which browsers silently
        // block as mixed content on an https:// page.
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        Gate::policy(Student::class, StudentPolicy::class);
        Gate::policy(Transaction::class, TransactionPolicy::class);
        Gate::policy(CashPayment::class, CashPaymentPolicy::class);
        Gate::policy(ActivityLog::class, ActivityLogPolicy::class);
    }
}