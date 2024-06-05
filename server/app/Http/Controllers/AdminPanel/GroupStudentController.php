<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\UserStudent;
use App\Models\Group as GroupModel;
use Illuminate\Http\Request;

class GroupStudentController extends Controller
{
    /**
     * Получение всех студентов группы. (Проверено)
     */
    public function list($groupId)
    {
        $students = UserStudent::where('groupId', $groupId)->get(['id', 'fio', 'login', 'subgroup']);

        return response()->json($students);
    }

    /**
     * Получение всех свободных студентов (которые находятся вне группы). (Проверено)
     */
    public function freeStudentList()
    {
        $students = UserStudent::whereNull('groupId')->get(['id', 'fio']);

        return response()->json($students);
    }

    /**
     * Назначение студента в группу. (Проверено)
     */
    public function add($groupId, $studentId)
    {
        $student = UserStudent::find($studentId);
        $group = GroupModel::find($groupId);

        if (!$student) {
            return response()->json(['error' => '404 Student Not Found', 'status'=>404], 404);
        }

        if (!$group) {
            return response()->json(['error' => '404 Group Not Found', 'status'=>404], 404);
        }

        $student->groupId = $groupId;
        $student->save();

        return response()->json(['success' => true]);
    }

    /**
     * Открепление студента из группы. (Проверено)
     */
    public function remove($groupId, $studentId)
    {
        $student = UserStudent::where('groupId', $groupId)->find($studentId);

        if (!$student) {
            return response()->json(['error' => '404 Student In Group Not Found', 'status'=>404], 404);
        }

        $student->groupId = null;
        $student->save();

        return response()->json(['success' => true]);
    }

    /**
     * Изменение подгруппы студента. (Проверено)
     */
    public function changeSubgroup(Request $request, $groupId, $studentId)
    {
        $request->validate([
            'subgroup' => 'required|string|in:A,B',
        ]);

        $student = UserStudent::find($studentId);
        $group = GroupModel::find($groupId);

        if (!$student) {
            return response()->json(['error' => '404 Student Not Found', 'status'=>404], 404);
        }

        if (!$group) {
            return response()->json(['error' => '404 Group Not Found', 'status'=>404], 404);
        }

        $student = UserStudent::where('groupId', $groupId)->find($studentId);

        if (!$student) {
            return response()->json(['error' => '404 User In Group Not Found', 'status'=>404], 404);
        }

        $student->subgroup = $request->input('subgroup');
        $student->save();

        return response()->json(['success' => true]);
    }
}