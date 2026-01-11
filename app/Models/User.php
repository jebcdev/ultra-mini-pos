<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' =>'string',
        ];
    }

    /**
     * Check if the user has super admin role
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === Role::super_admin->value;
    }

    /**
     * Check if the user has admin role
     */
    public function isAdmin(): bool
    {
        return $this->role === Role::admin->value;
    }

    /**
     * Check if the user has user role
     */
    public function isUser(): bool
    {
        return $this->role === Role::user->value;
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
