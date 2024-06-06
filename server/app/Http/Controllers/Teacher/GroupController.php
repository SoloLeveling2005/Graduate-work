<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\UserTeacher as UserTeacher;

class GroupController extends Controller
{
    public function list(Request $request) {
        $teacher = $request->user;
        $teacherId = $teacher['id'];

        // Получение предметов только для конкретного преподавателя
        $teacherSubjects = UserTeacher::with(['teacherSubject.groupSubjects.group', 'teacherSubject.subject', 'teacherSubject.groupSubjects.group.students'])
            ->find($teacherId)
            ->teacherSubject
            ->where('userTeacherId', $teacherId);

        $groups = $teacherSubjects->flatMap(function($teacherSubject) {
            return $teacherSubject->groupSubjects->map(function($groupSubject) use ($teacherSubject) {
                $group = $groupSubject->group->toArray();
                $group['subject'] = $teacherSubject->subject->toArray();
                return $group;
            });
        });

        return response()->json($groups->toArray());
    }

    public function tutorList(Request $request) {
        $teacher = $request->user;
        $teacherId = $teacher['id'];
        $data = UserTeacher::with(['groups'])
            ->find($teacherId);
        dd($data);
    }
}
