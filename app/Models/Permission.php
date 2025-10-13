<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'category',
    ];

    /**
     * Get the roles that have this permission.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    /**
     * Get permissions grouped by category.
     */
    public static function getByCategory(): array
    {
        return static::orderBy('category')
            ->orderBy('display_name')
            ->get()
            ->groupBy('category')
            ->toArray();
    }

    /**
     * Get all permission categories.
     */
    public static function getCategories(): array
    {
        return static::distinct('category')
            ->orderBy('category')
            ->pluck('category')
            ->toArray();
    }
}
