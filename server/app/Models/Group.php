<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'departmentId', 'userTeacherId', 'color'];

    public function department()
    {
        return $this->belongsTo(Department::class, 'departmentId');
    }

    public function curator()
    {
        return $this->belongsTo(UserTeacher::class, 'userTeacherId');
    }

    public function subjects()
    {
        return $this->hasMany(GroupSubject::class, 'groupId');
    }

    public function students()
    {
        return $this->hasMany(UserStudent::class, 'groupId');
    }

    public function schedules()
    {
        return $this->hasMany(GroupScheduleClass::class, 'groupId');
    }
}
