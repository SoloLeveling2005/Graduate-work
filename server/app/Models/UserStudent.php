<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class UserStudent extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['login', 'password', 'fio', 'groupId', 'subgroup'];

    protected $hidden = ['password'];

    public function group()
    {
        return $this->belongsTo(Group::class, 'groupId');
    }
}
