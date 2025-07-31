<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Borrowing;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'last_seen_at',
        'profile_photo_path',
        'otp_code',
        'otp_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen_at' => 'datetime',
        ];
    }

    // Helper methods untuk checking permissions
    public function canAccessBorrowings()
    {
        return $this->hasPermissionTo('view-borrowings') || $this->hasRole('Admin');
    }

    public function canAccessItems()
    {
        return $this->hasPermissionTo('view-items') || $this->hasRole('Admin');
    }

    public function canAccessReports()
    {
        return $this->hasPermissionTo('view-reports') || $this->hasRole('Admin');
    }

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
            ? asset('storage/' . $this->profile_photo_path)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name);
    }

    public function isOnline()
    {
        return $this->last_seen_at && $this->last_seen_at->gt(now()->subMinutes(5));
    }

    public function peminjaman()
    {
        return $this->hasMany(\App\Models\Borrowing::class);
    }



}