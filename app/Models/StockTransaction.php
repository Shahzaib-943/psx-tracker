<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    protected $fillable = [
        'portfolio_id',
        'stock_id',
        'transaction_type',
        'quantity',
        'price_per_share',
        'gross_amount',
        'broker_commission',
        'total_deductions',
        'net_amount',
        'final_price_per_share',
        'transaction_date',
        'notes'
    ];
}
