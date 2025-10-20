<?php
namespace App\Modules\Stock\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    protected $table = 'stock_transactions';
    protected $fillable = [
        'user_id',
        'auction_id',
        'shares_count',
        'price',
        'type', // buy/sell
        'info',
    ];
}
