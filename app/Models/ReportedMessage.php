<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportedMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'reported_by',
        'reason',
        'status',
        'admin_note',
        'group_id',
        'description'
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
} 