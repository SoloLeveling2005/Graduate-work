<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use app\Models\UserTeacher;

class ScheduleController extends Controller
{
    public function getWeeklySchedule(Request $request)
    {
        $teacher = $request->user();
        $today = now();
        $startOfWeek = $today->copy()->startOfWeek();
        $endOfWeek = $today->copy()->endOfWeek();

        $schedule = $teacher->schedule()->whereBetween('date', [$startOfWeek, $endOfWeek])->get();

        return response()->json($schedule);
    }


    public function list(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d'
        ]);
        $teacher = $request->user();

        // Получение входных данных или установка значений по умолчанию
        $startDate = $request->input('start_date') ?: Carbon::now()->startOfWeek()->format('Y-m-d');
        $endDate = $request->input('end_date') ?: Carbon::now()->addMonth()->format('Y-m-d');
        $teacherId = $teacher->id;

        // Запрос для получения расписания
        $schedule = GroupScheduleClass::whereHas('groupSubject.teacherSubject.userTeacher', function ($query) use ($teacherId) {
            $query->where('id', $teacherId);
        })
        ->whereBetween('date', [$startDate, $endDate])
        ->with(['group', 'groupSubject.teacherSubject.userTeacher', 'groupSubject.subject'])
        ->get();

        return response()->json($schedule);

        
    }
}