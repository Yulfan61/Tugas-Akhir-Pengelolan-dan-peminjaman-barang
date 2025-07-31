<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user): void
    {
        // Assign default role jika user belum memiliki role apa pun
        if ($user->roles()->count() === 0) {
            $user->assignRole('peminjam');
        }
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user): void
    {
        // Jika kamu ingin menangani perubahan role via observer, tambahkan logika di sini.
        // Biasanya, assignRole/syncRoles dilakukan di controller, jadi bagian ini bisa dikosongkan.
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user): void
    {
        // Opsional: Hapus semua role user saat user dihapus (tidak wajib)
        $user->syncRoles([]);
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
