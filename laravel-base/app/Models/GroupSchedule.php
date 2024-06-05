<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['groupId', 'date'];

    public function group()
    {
        return $this->belongsTo(Group::class, 'groupId');
    }

    public function classes()
    {
        return $this->hasMany(GroupScheduleClass::class, 'groupScheduleId');
    }
}
