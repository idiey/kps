<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    /**
     * Determine whether the user can view any customers.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view customers
        return true;
    }

    /**
     * Determine whether the user can view the customer.
     */
    public function view(User $user, Customer $customer): bool
    {
        // All authenticated users can view individual customers
        return true;
    }

    /**
     * Determine whether the user can create customers.
     */
    public function create(User $user): bool
    {
        // Admin, technicians, and front desk can create customers
        return $user->hasAnyRole(['pentadbiran', 'juruteknik', 'kaunter']);
    }

    /**
     * Determine whether the user can update the customer.
     */
    public function update(User $user, Customer $customer): bool
    {
        // Admin can update any customer
        if ($user->hasRole('pentadbiran')) {
            return true;
        }

        // Technicians and front desk can update customers
        if ($user->hasAnyRole(['juruteknik', 'kaunter'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the customer.
     */
    public function delete(User $user, Customer $customer): bool
    {
        // Only admin can delete customers
        return $user->hasRole('pentadbiran');
    }

    /**
     * Determine whether the user can restore the customer.
     */
    public function restore(User $user, Customer $customer): bool
    {
        // Only admin can restore customers
        return $user->hasRole('pentadbiran');
    }

    /**
     * Determine whether the user can permanently delete the customer.
     */
    public function forceDelete(User $user, Customer $customer): bool
    {
        // Only admin can force delete customers
        return $user->hasRole('pentadbiran');
    }
}
