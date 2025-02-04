<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class FinanceCategory extends Model
{
    public const CATEGORY_INCOME = "income";
    public const CATEGORY_EXPENSE = "expense";
    protected $fillable = ['name', 'user_id', 'color', 'finance_type_id', 'is_common', 'slug'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function financeType()
    {
        return $this->belongsTo(FinanceType::class);
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => ucfirst($value),
        );
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
