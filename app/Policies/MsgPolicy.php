<?php

namespace App\Policies;

use App\User;
use App\Mensagem;
use Illuminate\Auth\Access\HandlesAuthorization;

class MsgPolicy
{
    use HandlesAuthorization;

    public function self(User $user, Mensagem $mensagem)
    {
        return ($user->id==$mensagem->user);
    }
}
