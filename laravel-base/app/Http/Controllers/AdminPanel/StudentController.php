<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\UserStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    /**
     * Создание студента.
     */
    public function create(Request $request)
    {
        $request->validate([
            'fio' => 'required|string|max:155',
        ]);

        $fio = $request->input('fio');
        $login = Str::slug($fio);
        $password = Hash::make('12345678');
        
        $student = UserStudent::create([
            'fio' => $fio,
            'login' => $login,
            'password' => $password,
        ]);

        return response()->json(['success' => true, 'student' => $student], 201);
    }

    /**
     * Получение информации о студенте.
     */
    public function info($studentId)
    {
        $student = UserStudent::with(['group', 'group.curator', 'group.department'])
            ->findOrFail($studentId);

        $info = [
            'id' => $student->id,
            'fio' => $student->fio,
            'group' => $student->group,
            'curator' => $student->group ? $student->group->curator : null,
            'specialization' => $student->group ? $student->group->department : null,
        ];

        return response()->json($info);
    }

    /**
     * Изменение ФИО студента.
     */
    public function changeFIO(Request $request, $studentId)
    {
        $request->validate([
            'fio' => 'required|string|max:155',
        ]);

        $student = UserStudent::findOrFail($studentId);
        $student->fio = $request->input('fio');
        $student->save();

        return response()->json(['success' => true]);
    }
}
