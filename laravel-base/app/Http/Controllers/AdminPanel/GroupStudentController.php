<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\UserStudent;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupStudentController extends Controller
{
    /**
     * Получение всех студентов группы.
     */
    public function list($groupId)
    {
        $students = UserStudent::where('groupId', $groupId)->get(['id', 'fio', 'subgroup']);

        return response()->json($students);
    }

    /**
     * Получение всех свободных студентов (которые находятся вне группы).
     */
    public function freeStudentList()
    {
        $students = UserStudent::whereNull('groupId')->get(['id', 'fio']);

        return response()->json($students);
    }

    /**
     * Добавление студента в группу.
     */
    public function add($groupId, $studentId)
    {
        $student = UserStudent::findOrFail($studentId);
        $student->groupId = $groupId;
        $student->save();

        return response()->json(['success' => true]);
    }

    /**
     * Открепление студента из группы.
     */
    public function remove($groupId, $studentId)
    {
        $student = UserStudent::where('groupId', $groupId)->findOrFail($studentId);
        $student->groupId = null;
        $student->save();

        return response()->json(['success' => true]);
    }

    /**
     * Изменение подгруппы студента.
     */
    public function changeSubgroup(Request $request, $groupId, $studentId)
    {
        $request->validate([
            'subgroup' => 'required|string|in:A,B',
        ]);

        $student = UserStudent::where('groupId', $groupId)->findOrFail($studentId);
        $student->subgroup = $request->input('subgroup');
        $student->save();

        return response()->json(['success' => true]);
    }
}