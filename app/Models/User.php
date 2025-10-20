<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    
    protected $fillable = [
        'email', 'phone', 'password', 'fingerprint_id', 'status',
        'first_name', 'last_name', 'birth_date', 'gender', 'nationality', 'national_id', 'show_national_id', 'show_gender', 'avatar', 'show_birthdate', 'show_phone', 'show_email', 'show_name', 'biografie', 'social_networks', 'show_biografie', 'show_social_networks', 'show_documents', 'documents', 'experience_status', 'occupational_status', 'last_seen', 'show_groups', 'show_created_at', 'email_verified_at', 'edited'
    ];

    protected $hidden = ['password'];

    protected $dates = [
        'last_seen',
        'birth_date',
    ];

    // روابط چند به چند:
    public function occupationalFields()
    {
        return $this->belongsToMany(OccupationalField::class, 'user_occupational_field');
    }

    public function experienceFields()
    {
        return $this->belongsToMany(ExperienceField::class, 'user_experience_field');
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'user_location');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user', 'user_id', 'group_id');
    }    

    public function fullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function specialties()
    {
        return $this->belongsToMany(OccupationalField::class, 'user_occupational_field', 'user_id', 'occupational_field_id');
    }

    public function experiences()
    {
        return $this->belongsToMany(ExperienceField::class, 'user_experience_field', 'user_id', 'experience_field_id');
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }
    
    public function groupUser()
    {
        return $this->hasMany(GroupUser::class);
    }

    public function profile(){
        if($this->avatar == null){
            $fColor = rand(1, 255);
            $sColor = rand(1, 255);
            $tColor = rand(1, 255);

            return '<div class="group-avatar" style="width: 5rem; height: 5rem; font-size: 2rem; margin: 0; background-color: rgba(' . $fColor . ', ' . $sColor . ', ' . $tColor . ', .1); color: rgb(' . $fColor . ', ' . $sColor . ', ' . $tColor . ');">
                        <span>' . strtoupper(substr($this->email, 0, 1)) . '</span>
                    </div>';
        }else{
            return '<img alt="تصویر پروفایل" class="rounded-circle" width="150" height="150" src=' . asset('/images/users/avatars/' . $this->avatar) . '>';
        }
    }


    public function profileInChat(){
        if($this->avatar == null){
            return '<div class="group-avatar" style="width: 2rem; height: 2rem; font-size: .6rem; margin: 0; background-color: #e3f2fd; color: #1976d2;">
                        <span>' . mb_substr($this->first_name, 0, 1) . ' ' . mb_substr($this->last_name, 0, 1) . '</span>
                    </div>';
        }else{
            return '<img alt="تصویر پروفایل" class="rounded-circle" width="32" height="32" src=' . asset('/images/users/avatars/' . $this->avatar) . '>';
        }
    }

    public function isOnline()
    {
        if (!$this->last_seen || !auth()->check() || auth()->id() !== $this->id) {
            return false;
        }
        return $this->last_seen->diffInMinutes(now()) < 5;
    }
}