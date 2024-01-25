<?php

namespace App\Policies;

//Models
use App\Models\{
    Expense,
    User
};

class ExpensePolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Expense $expense): bool
    {
        return $user->getAttribute('id') === $expense->getAttribute('user_id');
    }


    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Expense $expense): bool
    {
        return $user->getAttribute('id') === $expense->getAttribute('user_id');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Expense $expense): bool
    {
        return $user->getAttribute('id') === $expense->getAttribute('user_id');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Expense $expense): bool
    {
        return $user->getAttribute('id') === $expense->getAttribute('user_id');
    }
}
