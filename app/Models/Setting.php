<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $table = 'setting';
    protected $fillable = ['invation_status', 'finger_status', 'expire_invation_time', 'count_invation', 'najm_summary', 'welcome_titre', 'welcome_content', 'home_titre', 'home_content'];
}
