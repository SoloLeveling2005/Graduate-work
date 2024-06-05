<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminPrivilege extends Model
{
    use HasFactory;

    protected $fillable = ['userAdminId', 'privilege'];

    public function admin()
    {
        return $this->belongsTo(UserAdmin::class, 'userAdminId');
    }
}
