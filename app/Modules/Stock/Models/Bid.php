<?php
namespace App\Modules\Stock\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bid extends Model
{
    protected $table = 'bids';
    
    protected $fillable = [
        'auction_id',
        'user_id',
        'price',
        'quantity',
        'status',
    ];
    
    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
    ];
    
    public function auction(): BelongsTo
    {
        return $this->belongsTo(Auction::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    
    public function getTotalValueAttribute(): float
    {
        return $this->price * $this->quantity;
    }
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    public function scopeWon($query)
    {
        return $query->where('status', 'won');
    }
    
    public function scopeLost($query)
    {
        return $query->where('status', 'lost');
    }
    
    public function scopeByPrice($query, $direction = 'desc')
    {
        return $query->orderBy('price', $direction);
    }
}