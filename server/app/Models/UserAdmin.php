<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class UserAdmin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['login', 'password'];

    protected $hidden = ['password', 'remember_token'];

    public function privileges()
    {
        return $this->hasMany(AdminPrivilege::class, 'userAdminId');
    }
}

