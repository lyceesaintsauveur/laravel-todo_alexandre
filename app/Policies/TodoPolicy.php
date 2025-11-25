<?php

namespace App\Policies;

use App\Models\Todos;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TodoPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Todos $todo)
    {
        return $user->id === $todo->user_id;
    }

    public function update(User $user, Todos $todo)
    {
        return $user->id === $todo->user_id;
    }

    public function delete(User $user, Todos $todo)
    {
        return $user->id === $todo->user_id;
    }

    public function attach(User $user, Todos $todo)
    {
        return $user->id === $todo->user_id;
    }
}
