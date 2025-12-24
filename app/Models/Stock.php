<?php

namespace App\Models;

use App\Traits\HasPublicId;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasPublicId;

    protected $fillable = ['public_id', 'symbol', 'price'];

    public function portfolioHoldings()
    {
        return $this->hasMany(PortfolioHolding::class);
    }
}
