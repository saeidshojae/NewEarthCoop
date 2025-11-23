<?php

namespace App\Modules\NajmBahar\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'najm_accounts';
    protected $fillable = [
        'account_number', // e.g. 1000000254 or 0000000000
        'user_id',
        'name',
        'type', // central|user|legal_entity|bank
        'balance',
        'meta',
        'status'
    ];

    protected $casts = [
        'balance' => 'integer',
        'meta' => 'array',
    ];

    public $timestamps = true;
}
