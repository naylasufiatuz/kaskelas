<?php

namespace App\Policies;

use App\Models\CashPayment;
use App\Models\User;

class CashPaymentPolicy
{
    public function viewAny(User $user): bool
    {
        // Treasurer/leader see the full weekly table; students are restricted in the controller
        // to only ever query their own student_id - see CashPaymentController::mine().
        return true;
    }

    public function view(User $user, CashPayment $cashPayment): bool
    {
        if ($user->isTreasurer() || $user->isClassLeader()) {
            return true;
        }

        return $user->isStudent() && $user->student_id === $cashPayment->student_id;
    }

    public function manage(User $user): bool
    {
        return $user->isTreasurer();
    }
}
