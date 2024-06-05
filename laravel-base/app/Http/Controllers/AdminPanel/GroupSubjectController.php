<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\GroupSubject;
use App\Models\UserTeacherSubject;
use Illuminate\Http\Request;

class GroupSubjectController extends Controller
{
    /**
     * Получение списка предметов группы с их преподавателями.
     */
    public function getList($groupId)
    {
        $groupSubjects = GroupSubject::where('groupId', $groupId)
            ->with(['teacherSubject.subject', 'teacherSubject.teacher'])
            ->get();

        $response = $groupSubjects->map(function ($groupSubject) {
            return [
                'id' => $groupSubject->id,
                'subject' => [
                    'id' => $groupSubject->teacherSubject->subject->id,
                    'title' => $groupSubject->teacherSubject->subject->title,
                ],
                'teacher' => [
                    'id' => $groupSubject->teacherSubject->teacher->id,
                    'fio' => $groupSubject->teacherSubject->teacher->fio,
                ],
            ];
        });

        return response()->json($response);
    }

    /**
     * Получение всех предметов этой группы.
     */
    public function list($groupId)
    {
        $groupSubjects = GroupSubject::where('groupId', $groupId)
            ->with(['teacherSubject.subject', 'teacherSubject.teacher'])
            ->get();

        $response = $groupSubjects->map(function ($groupSubject) {
            return [
                'id' => $groupSubject->id,
                'subject' => [
                    'id' => $groupSubject->teacherSubject->subject->id,
                    'title' => $groupSubject->teacherSubject->subject->title,
                ],
                'teacher' => [
                    'id' => $groupSubject->teacherSubject->teacher->id,
                    'fio' => $groupSubject->teacherSubject->teacher->fio,
                ],
            ];
        });

        return response()->json($response);
    }

    /**
     * Добавление предмета в группу.
     */
    public function add(Request $request, $groupId)
    {
        $request->validate([
            'teacherSubjectId' => 'required|exists:user_teacher_subjects,id',
        ]);

        $groupSubject = GroupSubject::create([
            'groupId' => $groupId,
            'teacherSubjectId' => $request->input('teacherSubjectId'),
        ]);

        return response()->json(['success' => true, 'groupSubject' => $groupSubject], 201);
    }

    /**
     * Удаление предмета из группы.
     */
    public function remove($groupId, $subjectId)
    {
        GroupSubject::where('groupId', $groupId)
            ->where('teacherSubjectId', $subjectId)
            ->delete();

        return response()->json(['success' => true]);
    }
}
