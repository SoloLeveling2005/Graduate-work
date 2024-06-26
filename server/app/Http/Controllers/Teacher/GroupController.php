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

    public function myGroups(Request $request) {
        $teacher = $request->user;
        $teacherId = $teacher['id'];

        // Получаем все группы, у которых есть предметы, связанные с текущим преподавателем
        $groups = Group::whereHas('subjects.teacherSubject', function($query) use ($teacherId) {
            $query->where('userTeacherId', $teacherId);
        })->with(['subjects.teacherSubject.teacher'])->get();

        return response()->json($groups, 200);
    }


    public function info(Request $request, $groupId) {
        $teacher = $request->user;
        $teacherId = $teacher['id'];

        $group = Group::with(['subjects.teacherSubject.subject','subjects.teacherSubject.teacher', 'students','schedules.subject.teacherSubject.teacher.auditorium'])->find($groupId);

        if (!$group) {
            return response()->json(['error' => '404 Group Not Found', 'status'=>404], 404);
        }

        // TODO - Какие предметы ведет в группе этот препод
        // TODO - Какое расписание на неделю у этого препода в этой группе  (день недели, номер пары, название предмета, аудитория)
        // TODO - Список группы

        $subjects = ($group->subjects)->filter(function($item) use ($teacherId) {
            return $item->teacherSubject->userTeacherId == $teacherId;
        });
        $schedules = $group->schedules->filter(function($item) use ($teacherId) {
            return $item->subject->teacherSubject->userTeacherId == $teacherId;
        })->map(function($item) {
            return [
                "id"=> $item->id,
                "groupId"=> $item->groupId,
                "subjectId"=> $item->subjectId,
                "subgroup"=> $item->subgroup,
                "number"=> $item->number,
                "dayWeek"=> $item->dayWeek,
                "created_at"=> $item->created_at,
                "updated_at"=> $item->updated_at,
                "subject"=> $item->subject->teacherSubject->subject->title,
                "auditorium"=> $item->subject->teacherSubject->teacher->auditorium->number
            ];
        })->groupBy('dayWeek');
        $students = $group->students;

        $groupInfo = [
            "id" => $group->id,
            "title" => $group->title,
            "color" => $group->color,
            "subjects" => $subjects,
            "schedules" => $schedules,
            "students" => $students
        ];

        return response()->json($groupInfo, 200);
    }
}
