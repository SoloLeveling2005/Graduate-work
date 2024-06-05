<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\UserStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentController extends Controller
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
     * Создание студента. (Проверено)
     */
    public function create(Request $request)
    {
        $request->validate([
            'fio' => 'required|string|max:155'
        ]);

        $fio = $request->input('fio');
        $login = self::toLogin($fio);
        $password = Hash::make('123456780');

        $studentExists = UserTeacher::where(['login'=>$login])->exists();

        if ($studentExists) {
            return response()->json(['error' => 'HTTP 409 Conflict', 'status'=>409], 409);
        }
        
        $student = UserStudent::create([
            'fio' => $fio,
            'login' => $login,
            'password' => $password,
            'subgroup' => ''
        ]);

        return response()->json(['success' => true, 'student' => $student], 201);
    }

    /**
     * Получение информации о студенте. (Проверено)
     */
    public function info($studentId)
    {
        $student = UserStudent::with(['group', 'group.curator', 'group.department'])
            ->find($studentId);

        if (!$student) {
            return response()->json(['error' => '404 Student Not Found', 'status'=>404], 404);
        }

        $info = [
            'id' => $student->id,
            'fio' => $student->fio,
            'subgroup' => $student->subgroup,
            'group' => $student->group,
            'curator' => $student->group ? $student->group->curator : null,
            'specialization' => $student->group ? $student->group->department : null,
        ];

        return response()->json($info);
    }

    /**
     * Изменение ФИО студента. (Проверено)
     */
    public function changeFIO(Request $request, $studentId)
    {
        $request->validate([
            'fio' => 'required|string|max:155',
        ]);

        $student = UserStudent::find($studentId);

        if (!$student) {
            return response()->json(['error' => '404 Student Not Found', 'status'=>404], 404);
        }
        
        $student->fio = $request->input('fio');
        $student->save();

        return response()->json(['success' => true]);
    }
}
