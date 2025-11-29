<?php

namespace App\Policies;

use App\Models\Pengguna;

class PenggunaPolicy
{
    public function viewAny(Pengguna $user)
    {
        return in_array($user->level, [1]);
    }

    public function view(Pengguna $user, Pengguna $model)
    {
        return in_array($user->level, [1]) || $user->id_pengguna === $model->id_pengguna;
    }

    public function create(Pengguna $user)
    {
        return in_array($user->level, [1]);
    }

    public function update(Pengguna $user, Pengguna $model)
    {
        return in_array($user->level, [1]);
    }

    public function delete(Pengguna $user, Pengguna $model)
    {
        return in_array($user->level, [1]);
    }
}

