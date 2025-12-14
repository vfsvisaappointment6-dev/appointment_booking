<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;

class ServicePolicy
{
    /**
     * Determine if user can view any services
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if user can view a service
     */
    public function view(User $user, Service $service): bool
    {
        return true; // Services are public
    }

    /**
     * Determine if user can create a service
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if user can update a service
     */
    public function update(User $user, Service $service): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if user can delete a service
     */
    public function delete(User $user, Service $service): bool
    {
        return $user->role === 'admin';
    }
}
