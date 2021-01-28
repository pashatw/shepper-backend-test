<?php

namespace App\Policies;

use App\Models\Location;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LocationPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Location $location)
    {
        return $user->id === $location->user_id;
    }
    
    public function delete(User $user, Location $location)
    {
        return $user->id === $location->user_id;
    }
}
