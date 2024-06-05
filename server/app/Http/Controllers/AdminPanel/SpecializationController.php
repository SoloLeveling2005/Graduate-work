<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class SpecializationController extends Controller
{
    /**
     * Получение списка специальностей.
     * Возвращает id и название каждой специальности.
     */
    public function getList()
    {
        $specializations = Department::all(['id', 'title']);
        return response()->json($specializations);
    }
}
