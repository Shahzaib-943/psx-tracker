<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasPublicId;

class Portfolio extends Model
{
    use HasPublicId;
    protected $fillable = ['name', 'description', 'user_id', 'slug'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function holdings()
    {
        return $this->hasMany(PortfolioHolding::class);
    }

    public function getRouteKeyName()
    {
        return 'public_id';
    }
}
