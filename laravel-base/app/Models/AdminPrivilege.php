<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminPrivilege extends Model
{
    use HasFactory;

    public function userAdmin()
    {
        return $this->belongsTo(UserAdmin::class, 'userAdminId', 'id');
    }
}
