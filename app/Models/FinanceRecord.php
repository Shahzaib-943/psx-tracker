<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class FinanceRecord extends Model
{
    protected $fillable = ['user_id', 'finance_category_id', 'amount', 'description', 'date'];

    public function financeCategory()
    {
        return $this->belongsTo(FinanceCategory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
