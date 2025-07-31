<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Blog;

class BlogPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Semua user bisa lihat blog
    }

    public function view(User $user, Blog $blog): bool
    {
        return true; // Semua user bisa lihat blog
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Admin') || $user->hasRole('Staff');
    }

    public function update(User $user, Blog $blog): bool
    {
        return $user->hasRole('Admin') || $user->hasRole('Staff');
    }

    public function delete(User $user, Blog $blog): bool
    {
        return $user->hasRole('Admin') || $user->hasRole('Staff');
    }
}
