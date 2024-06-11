<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\UserTeacherSubject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Получение списка предметов.
     * Возвращает id и название каждого предмета.
     */
    public function getList(Request $request)
    {
        $subjects = Subject::all(['id', 'title']);
        return response()->json($subjects);
    }


    public function getTeacherSubjectList(Request $request) {
        $subjects = UserTeacherSubject::with(['teacher', 'subject'])->get();
        return response()->json($subjects);
    }
}