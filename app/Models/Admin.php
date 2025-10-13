<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable implements FilamentUser
{
    use Notifiable, SoftDeletes;

    protected $table = 'admins';

    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role', // Keep for backwards compatibility
        'role_id', // New role system
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the role for this admin.
     */
    public function adminRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Check if admin has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        return $this->adminRole?->hasPermission($permission) ?? false;
    }

    /**
     * Check if admin has any of the given permissions.
     */
    public function hasAnyPermission(array $permissions): bool
    {
        return $this->adminRole?->hasAnyPermission($permissions) ?? false;
    }

    /**
     * Check if admin has all of the given permissions.
     */
    public function hasAllPermissions(array $permissions): bool
    {
        return $this->adminRole?->hasAllPermissions($permissions) ?? false;
    }

    /**
     * Check if admin has a specific role.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->adminRole?->name === $roleName;
    }

    /**
     * Get admin's role name.
     */
    public function getRoleName(): ?string
    {
        return $this->adminRole?->name;
    }

    /**
     * Get admin's role display name.
     */
    public function getRoleDisplayName(): ?string
    {
        return $this->adminRole?->display_name;
    }

    /**
     * Determine if the user can access the Filament admin panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if (! $this->is_active) {
            return false;
        }

        // If using new role system
        if ($this->adminRole) {
            return $this->adminRole->is_active &&
                   $this->hasAnyPermission(['access_admin_panel', 'manage_system']);
        }

        // Fallback to old system
        return $this->role === 'admin';
    }

    /**
     * Check if admin is super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin') || $this->role === 'super_admin';
    }
}
