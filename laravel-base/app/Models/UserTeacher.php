<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class UserTeacher extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['login', 'password', 'fio', 'auditoriaId'];

    protected $hidden = ['password'];

    public function auditorium()
    {
        return $this->belongsTo(Auditorium::class, 'auditoriaId');
    }

    public function subjects()
    {
        return $this->hasMany(UserTeacherSubject::class, 'userTeacherId');
    }

    public function groups()
    {
        return $this->hasMany(Group::class, 'userTeacherId');
    }

    public function scheduleClasses()
    {
        return $this->hasMany(GroupScheduleClass::class, 'userTeacherId');
    }
}
