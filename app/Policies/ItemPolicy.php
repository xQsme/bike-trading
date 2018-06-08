<?php

namespace App\Policies;

use App\User;
use App\Item;
use Illuminate\Auth\Access\HandlesAuthorization;

class ItemPolicy
{
    use HandlesAuthorization;

    public function self(User $user, Item $item)
    {
        return ($user->id==$item->dono);
    }
}
