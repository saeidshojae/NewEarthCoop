<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id', 
        'user_id', 
        'message', 
        'parent_id',
        'file_path',
        'file_type',
        'file_name',
        'edited', 
        'edited_by',
        'removed_by'
    ];

    protected $casts = [
        'edited' => 'boolean'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }
}