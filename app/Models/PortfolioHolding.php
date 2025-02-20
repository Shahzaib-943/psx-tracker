<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioHolding extends Model
{
    protected $fillable = [
        'portfolio_id',
        'stock_id',
        'quantity',
        'average_cost',
        'total_investment',
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}
