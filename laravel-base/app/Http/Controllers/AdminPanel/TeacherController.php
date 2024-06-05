<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\UserTeacher;
use App\Models\UserTeacherSubject;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    /**
     * Создание преподавателя.
     */
    public function create(Request $request)
    {
        $request->validate([
            'fio' => 'required|string|max:155',
            'subjects' => 'array',
            'subjects.*' => 'exists:subjects,id'
        ]);

        $fio = $request->input('fio');
        $login = Str::slug($fio);
        $password = Hash::make('12345678');
        
        $teacher = UserTeacher::create([
            'fio' => $fio,
            'login' => $login,
            'password' => $password,
        ]);

        $subjects = $request->input('subjects', []);
        foreach ($subjects as $subjectId) {
            UserTeacherSubject::create([
                'userTeacherId' => $teacher->id,
                'subjectId' => $subjectId,
            ]);
        }

        return response()->json(['success' => true, 'teacher' => $teacher], 201);
    }

    /**
     * Получение списка преподавателей.
     */
    public function getList(Request $request)
    {
        $query = UserTeacher::query();

        // Сортировка по количеству прикрепленных групп
        if ($request->has('sort') && $request->input('sort') === 'group_count') {
            $query->withCount('groups')->orderBy('groups_count', 'desc');
        }

        $teachers = $query->get(['id', 'fio']);
        return response()->json($teachers);
    }

    /**
     * Получение информации о преподавателе.
     */
    public function info($teacherId)
    {
        $teacher = UserTeacher::with(['auditorium', 'groups', 'subjects.subject'])
            ->findOrFail($teacherId);

        $info = [
            'id' => $teacher->id,
            'fio' => $teacher->fio,
            'auditorium' => $teacher->auditorium,
            'curator_groups' => $teacher->groups,
            'subjects' => $teacher->subjects->map(function ($teacherSubject) {
                return $teacherSubject->subject;
            }),
        ];

        return response()->json($info);
    }

    /**
     * Изменение ФИО преподавателя.
     */
    public function changeFIO(Request $request, $teacherId)
    {
        $request->validate([
            'fio' => 'required|string|max:155',
        ]);

        $teacher = UserTeacher::findOrFail($teacherId);
        $teacher->fio = $request->input('fio');
        $teacher->save();

        return response()->json(['success' => true]);
    }

    /**
     * Удаление предмета у преподавателя.
     */
    public function deleteSubject(Request $request, $teacherId)
    {
        $request->validate([
            'subjectId' => 'required|exists:subjects,id',
        ]);

        UserTeacherSubject::where('userTeacherId', $teacherId)
            ->where('subjectId', $request->input('subjectId'))
            ->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Добавление предмета преподавателю.
     */
    public function addSubject(Request $request, $teacherId)
    {
        $request->validate([
            'subjectId' => 'required|exists:subjects,id',
        ]);

        UserTeacherSubject::create([
            'userTeacherId' => $teacherId,
            'subjectId' => $request->input('subjectId'),
        ]);

        return response()->json(['success' => true]);
    }
}
