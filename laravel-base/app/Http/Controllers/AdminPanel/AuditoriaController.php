<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Auditorium;
use Illuminate\Http\Request;

class AuditoriaController extends Controller
{
    /**
     * Получение списка аудиторий.
     * Возвращает id и номер каждой аудитории.
     */
    public function getList()
    {
        $auditorias = Auditorium::all(['id', 'number']);
        return response()->json($auditorias);
    }
}