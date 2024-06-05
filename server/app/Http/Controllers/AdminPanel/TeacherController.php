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
    public function transliterate($text) {
        $transliterationTable = array(
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
            'И' => 'I', 'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R',
            'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'Kh', 'Ц' => 'Ts', 'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Shch',
            'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch',
            'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya'
        );

        return strtr($text, $transliterationTable);
    }

    public function toLogin($name) {
        $parts = explode(' ', $name);
        $formattedName = '';

        foreach ($parts as $index => $part) {
            $transliteratedPart = self::transliterate($part);
            if ($index === 0) {
                $formattedName .= strtolower($transliteratedPart);
            } else {
                $formattedName .= ucfirst($transliteratedPart);
            }
        }

        return $formattedName;
    }

    /**
     * Создание преподавателя. (Проверено)
     */
    public function create(Request $request)
    {
        $request->validate([
            'fio' => 'required|string|max:155',
            'subjects' => 'array',
            'subjects.*' => 'exists:subjects,id',
            'auditoriaId' => 'integer|nullable|exists:auditoria,id'
        ]);

        $fio = $request->input('fio');
        $login = self::toLogin($fio);
        $password = Hash::make('123456780');

        $teacherExists = UserTeacher::where(['login' => $login])->exists();

        if ($teacherExists) {
            return response()->json(['error' => 'HTTP 409 Conflict', 'status' => 409], 409);
        }

        $teacher = UserTeacher::create([
            'fio' => $fio,
            'login' => $login,
            'password' => $password,
            'auditoriaId' => $request->input('auditoriaId')
        ]);

        $subjects = $request->input('subjects', []);
        foreach ($subjects as $subjectId) {
            $userTeacherSubjectExists = UserTeacherSubject::where('userTeacherId', $teacher->id)->where('subjectId', $subjectId)->exists();
            if (!$userTeacherSubjectExists) {
                UserTeacherSubject::create([
                    'userTeacherId' => $teacher->id,
                    'subjectId' => $subjectId,
                ]);
            }
            
        }

        return response()->json(['status' => 201, 'teacher' => $teacher], 201);
    }

    /**
     * Получение списка преподавателей. (Проверено)
     */
    public function getList(Request $request)
    {
        $query = UserTeacher::query();

        // Сортировка по количеству прикрепленных групп
        if ($request->has('sort') && $request->input('sort') === 'group_count') {
            $query->withCount('groups')->orderBy('groups_count', 'desc');
        }

        $teachers = $query->get(['id', 'fio']);
        return response()->json(['status'=>200, 'teachers'=>$teachers], 200);
    }

    /**
     * Получение информации о преподавателе. (Проверено)
     */
    public function info($teacherId)
    {
        $teacher = UserTeacher::with(['auditorium', 'groups', 'subjects.subject'])
            ->find($teacherId);

        if (!$teacher) {
            return response()->json(['error' => 'HTTP 404 Teacher Not Found', 'status'=>404], 404);
        }

        $info = [
            'id' => $teacher->id,
            'fio' => $teacher->fio,
            'auditorium' => $teacher->auditorium,
            'curator_groups' => $teacher->groups,
            'subjects' => $teacher->subjects->map(function ($teacherSubject) {
                return $teacherSubject->subject;
            }),
        ];

        return response()->json(['status'=>200, 'info'=>$info], 200);
    }

    /**
     * Изменение ФИО преподавателя. (Проверено)
     */
    public function changeFIO(Request $request, $teacherId)
    {
        $request->validate([
            'fio' => 'required|string|max:155',
        ]);

        $teacher = UserTeacher::find($teacherId);

        if (!$teacher) {
            return response()->json(['error' => 'HTTP 404 Teacher Not Found', 'status'=>404], 404);
        }

        $teacher->fio = $request->input('fio');
        $teacher->save();

        return response()->json(['status' => 200]);
    }

    /**
     * Удаление предмета у преподавателя. (Проверено)
     */
    public function deleteSubject(Request $request, $teacherId)
    {
        $request->validate([
            'subjectId' => 'required|exists:subjects,id',
        ]);

        $userTeacherSubject = UserTeacherSubject::where('userTeacherId', $teacherId)
            ->where('subjectId', $request->input('subjectId'))
            ->first();

        if (!$userTeacherSubject) {
            return response()->json(['error' => 'HTTP 404 Subject Not Found', 'status'=>404], 404);
        }

        $userTeacherSubject->delete();

        return response()->json(['status' => 200]);
    }


    /**
     * Добавление предмета преподавателю. (Проверено)
     */
    public function addSubject(Request $request, $teacherId)
    {
        $request->validate([
            'subjectId' => 'required|exists:subjects,id',
        ]);

        $existingRecord = UserTeacherSubject::where('userTeacherId', $teacherId)
            ->where('subjectId', $request->input('subjectId'))
            ->first();

        if ($existingRecord) {
            return response()->json(['error' => 'HTTP 409 Conflict. Subject already in Teacher', 'status'=>409], 409);
        }

        UserTeacherSubject::create([
            'userTeacherId' => $teacherId,
            'subjectId' => $request->input('subjectId'),
        ]);

        return response()->json(['status' => 200]);
    }

}
