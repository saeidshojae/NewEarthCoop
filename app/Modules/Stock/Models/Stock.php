<?php
namespace App\Modules\Stock\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stock extends Model
{
    protected $table = 'stocks';
    
    protected $fillable = [
        'startup_valuation', // ارزش پایه استارتاپ
        'total_shares',      // تعداد کل سهام
        'base_share_price',  // ارزش پایه هر سهم
        'info',              // اطلاعات تکمیلی
    ];
    
    protected $casts = [
        'startup_valuation' => 'decimal:2',
        'base_share_price' => 'decimal:2',
        'total_shares' => 'integer',
    ];
    
    public function auctions(): HasMany
    {
        return $this->hasMany(Auction::class);
    }
    
    public function holdings(): HasMany
    {
        return $this->hasMany(Holding::class);
    }
    
    public function getMarketCapAttribute(): float
    {
        return $this->total_shares * $this->base_share_price;
    }
}
