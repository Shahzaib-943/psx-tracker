<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasPublicId
{
    /**
     * Boot the trait and attach model events.
     */
    protected static function bootHasPublicId()
    {
        static::creating(function ($model) {
            if (empty($model->public_id)) {
                $model->public_id = static::generateUniquePublicId(get_class($model));
            }
        });
    }

    /**
     * Generate a unique, non-reversible public id.
     */
    public static function generateUniquePublicId(string $modelClass): string
    {
        do {
            $id = Str::random(6);
        } while ($modelClass::where('public_id', $id)->exists());

        return $id;
    }
}
