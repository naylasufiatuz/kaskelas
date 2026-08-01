<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    public function viewAny(User $user): bool
    {
        // Everyone logged in can see the roster; students just get a read-only, limited view in the UI.
        return true;
    }

    public function view(User $user, Student $student): bool
    {
        if ($user->isTreasurer() || $user->isClassLeader()) {
            return true;
        }

        // A student may only view their own record.
        return $user->isStudent() && $user->student_id === $student->id;
    }

    public function create(User $user): bool
    {
        return $user->isTreasurer();
    }

    public function update(User $user, Student $student): bool
    {
        return $user->isTreasurer();
    }

    public function delete(User $user, Student $student): bool
    {
        return $user->isTreasurer();
    }
}
