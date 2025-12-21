<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Support\Str;
use App\Traits\HasPublicId;

class Role extends SpatieRole
{
    use HasPublicId;

    protected static function booted()
    {
        static::creating(function ($role) {
            if (empty($role->public_id)) {
                $role->public_id = (string) Str::uuid();
            }
        });
    }

    protected $fillable = [
        'name',
        'guard_name',
        'public_id',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'public_id';
    }
}
