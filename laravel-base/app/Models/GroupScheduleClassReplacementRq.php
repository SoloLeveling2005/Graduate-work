<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupScheduleClassReplacementRequest extends Model
{
    use HasFactory;

    protected $table = 'group_schedule_class_replacement_rq';

    protected $fillable = ['userTeacherId', 'groupScheduleClassId', 'subgroup', 'reason'];

    public function teacher()
    {
        return $this->belongsTo(UserTeacher::class, 'userTeacherId');
    }

    public function scheduleClass()
    {
        return $this->belongsTo(GroupScheduleClass::class, 'groupScheduleClassId');
    }
}
