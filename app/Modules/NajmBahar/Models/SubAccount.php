<?php

namespace App\Modules\NajmBahar\Models;

use Illuminate\Database\Eloquent\Model;

class SubAccount extends Model
{
    protected $table = 'najm_sub_accounts';
    protected $fillable = [
        'account_id',
        'sub_account_code', // e.g. 0000000000-001
        'name',
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
