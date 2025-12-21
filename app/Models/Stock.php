<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = ['sector_id', 'symbol', 'name', 'slug', 'closing_price', 'price_updated_at'];

    protected $casts = [
        'closing_price' => 'decimal:4',
        'price_updated_at' => 'datetime',
    ];

    public function portfolioHoldings()
    {
        return $this->hasMany(PortfolioHolding::class);
    }
}
