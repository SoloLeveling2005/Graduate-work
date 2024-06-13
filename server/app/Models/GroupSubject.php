<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupSubject extends Model
{
    use HasFactory;

    protected $fillable = ['groupId', 'teacherSubjectId'];

    public function groups()
    {
        return $this->belongsTo(Group::class, 'groupId');
    }

    public function teacherSubject()
    {
        return $this->belongsTo(UserTeacherSubject::class, 'teacherSubjectId');
    }
}
