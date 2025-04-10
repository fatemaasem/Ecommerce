<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Auth\Access\Response;

class WishlistPolicy
{
    public function viewAny(User $user)
    {
        return true;
        return $user->role->name === 'client';
    }

    public function create(User $user)
    {
        return true;
        return $user->role->name === 'client';
    }

    public function update(User $user, Wishlist $wishlist)
    {
        return $user->id === $wishlist->user_id;
    }

    public function delete(User $user, Wishlist $wishlist)
    {
        return $user->id === $wishlist->user_id;
    }
}
