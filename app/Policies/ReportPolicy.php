<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can approve the report.
     */
    public function approve(User $user, Report $report)
    {
        // Allow Admin and Director to approve reports
        return $user->hasRole('Admin') || $user->hasRole('Director');
    }

    /**
     * Determine if the user can reject the report.
     */
    public function reject(User $user, Report $report)
    {
        // Allow Admin and Director to reject reports
        return $user->hasRole('Admin') || $user->hasRole('Director');
    }
}
