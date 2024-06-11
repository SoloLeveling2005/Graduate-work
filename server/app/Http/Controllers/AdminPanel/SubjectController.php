<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Получение списка предметов.
     * Возвращает id и название каждого предмета.
     */
    public function getList()
    {
        $subjects = Subject::with(['teacherSubjects'])->get(['id', 'title']);
        return response()->json($subjects);
    }
}