<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auditorium extends Model
{
    use HasFactory;

    protected $fillable = ['number'];

    public function teachers()
    {
        return $this->hasMany(UserTeacher::class, 'auditoriaId');
    }
}
