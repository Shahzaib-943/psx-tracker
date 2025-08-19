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
    protected static function generateUniquePublicId(string $modelClass): string
    {
        do {
            $data = uniqid('', true) . '|' . Str::random(8) . '|' . config('app.key');
            $id   = substr(hash('sha256', $data), 0, rand(12, 15)); // safer min length = 8
        } while ($modelClass::where('public_id', $id)->exists());

        return $id;
    }
}
