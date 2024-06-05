<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupScheduleClass extends Model
{
    use HasFactory;

    protected $fillable = ['groupScheduleId', 'userTeacherId', 'subgroup'];

    public function schedule()
    {
        return $this->belongsTo(GroupSchedule::class, 'groupScheduleId');
    }

    public function teacher()
    {
        return $this->belongsTo(UserTeacher::class, 'userTeacherId');
    }

    public function replacementRequests()
    {
        return $this->hasMany(GroupScheduleClassReplacementRequest::class, 'groupScheduleClassId');
    }

    public function replacements()
    {
        return $this->hasMany(GroupScheduleClassReplacement::class, 'groupScheduleClassId');
    }
}
