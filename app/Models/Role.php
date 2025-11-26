<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Models\Scopes\RoleAccessScope;
use App\Traits\HasPublicId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission;

class Role extends Model
{
    use HasFactory, HasPublicId;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'role_type',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new RoleAccessScope);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function has_permission(int $permission_id): bool
    {
        return $this->permissions->contains("id", $permission_id);
    }

    public function role_name()
    {
        return Str::title(Str::replace("_", " ", $this->name));
    }
}
