<?php

/* ----------------------------------------------------------------------------
 * Apphold - Online Software Telemetry
 *
 * @package     Apphold
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://apphold.org
 * ---------------------------------------------------------------------------- */

namespace App\Policies;

use App\Models\IncidentComment;
use App\Models\User;

class IncidentCommentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, IncidentComment $incidentComment): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, IncidentComment $incidentComment): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, IncidentComment $incidentComment): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, IncidentComment $incidentComment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, IncidentComment $incidentComment): bool
    {
        return false;
    }
}
