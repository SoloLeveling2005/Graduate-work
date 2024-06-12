<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\GroupSubject;
use App\Models\UserTeacherSubject;
use App\Models\Classroom as ClassroomModel;
use Illuminate\Http\Request;

class GroupSubjectController extends Controller
{
    /**
     * Получение списка предметов группы с их преподавателями. (Проверено)
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

        return response()->json(['subjects'=>$response, 'status'=>200], 200);
    }

    /**
     * Получение всех предметов этой группы. (Проверено)
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

        return response()->json(['status'=>200, 'subjects'=>$response]);
    }

    /**
     * Добавление предмета в группу. (Проверено)
     */
    public function add(Request $request, $groupId)
    {
        $request->validate([
            'teacherSubjectId' => 'required|exists:user_teacher_subjects,id',
        ]);

        $groupSubjectExists = GroupSubject::where([
            'groupId' => $groupId,
            'teacherSubjectId' => $request->input('teacherSubjectId'),
        ])->exists();

        if ($groupSubjectExists) {
            return response()->json(['error' => '409 Conflict. Subject has already been added', 'status'=>409], 409);
        }

        $groupSubject = GroupSubject::create([
            'groupId' => $groupId,
            'teacherSubjectId' => $request->input('teacherSubjectId'),
        ]);

        // Создаем классрум
        ClassroomModel::create([
            'groupSubjectId' => $groupSubject->id
        ]);

        return response()->json(['status' => 201, 'groupSubject' => $groupSubject], 201);
    }

    /**
     * Удаление предмета из группы. (Проверено)
     */
    public function remove($groupId, $subjectId)
    {
        $groupSubjectExists = GroupSubject::find($subjectId);

        if (!$groupSubjectExists) {
            return response()->json(['error' => '404 Group Subject In Group Not Found', 'status'=>404], 404);
        }

        $groupSubjectExists->delete();

        return response()->json(['status' => 200], 200);
    }
}
