<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\UserTeacher as UserTeacher;
use App\Models\Group as Group;

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

    public function info(Request $request, $groupId) {
        $teacher = $request->user;
        $teacherId = $teacher['id'];

        $group = Group::wth(['subjects','students','schedules'])->find($groupId);

        if (!$group) {
            return response()->json(['error' => '404 Group Not Found', 'status'=>404], 404);
        }

        // TODO - Какие предметы ведет в группе этот препод
        // TODO - Какое расписание на неделю у этого препода в этой группе
        // TODO - Список группы

        $info = [
            'id' => $group->id,
            'title' => $group->title,
            'specialization' => $group->department,
            'curator' => $group->curator,
        ];

        return response()->json($info, 200);
    }
}
