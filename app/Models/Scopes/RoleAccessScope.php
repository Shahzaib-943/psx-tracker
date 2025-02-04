<?php

namespace App\Models\Scopes;

use Illuminate\Support\Facades\Auth;use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class RoleAccessScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // dd(auth()->user());
        // if(auth()->user()?->role?->name != 'Admin' && auth()->user()?->role?->name != 'SuperAdmin'){
        //     $builder->whereRaw('1 = 0');
        // }else{
        //     if(auth()->user()?->role?->name == 'Admin'){
        //         $builder->where('name', '!=', 'SuperAdmin');
        //     }
        // }
    }
}
