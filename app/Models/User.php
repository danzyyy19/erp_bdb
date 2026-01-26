<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'signature_path',
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
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if user is owner
     */
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    /**
     * Check if user is operasional
     */
    public function isOperasional(): bool
    {
        return $this->role === 'operasional';
    }

    /**
     * Check if user is finance
     */
    public function isFinance(): bool
    {
        return $this->role === 'finance';
    }

    /**
     * Get SPKs created by this user
     */
    public function createdSpks(): HasMany
    {
        return $this->hasMany(Spk::class, 'created_by');
    }

    /**
     * Get SPKs approved by this user
     */
    public function approvedSpks(): HasMany
    {
        return $this->hasMany(Spk::class, 'approved_by');
    }

    /**
     * Get user's notifications
     */
    public function userNotifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get unread notifications count
     */
    public function unreadNotificationsCount(): int
    {
        return $this->userNotifications()->where('is_read', false)->count();
    }

    /**
     * Check if user has a signature uploaded
     */
    public function hasSignature(): bool
    {
        return !empty($this->signature_path) && \Storage::disk('public')->exists($this->signature_path);
    }

    /**
     * Get signature URL
     */
    public function getSignatureUrlAttribute(): ?string
    {
        if ($this->hasSignature()) {
            return \Storage::disk('public')->url($this->signature_path);
        }
        return null;
    }
}
