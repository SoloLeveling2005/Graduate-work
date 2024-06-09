<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GroupScheduleClass;
use App\Models\GroupScheduleClassReplacementRq;
use App\Models\Group as GroupModal;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ScheduleController extends Controller
{
    public function list(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d'
        ]);

        $teacher = $request->user;

        // Получение входных данных или установка значений по умолчанию
        $startDate = $request->input('start_date') ?: Carbon::now()->startOfWeek()->format('Y-m-d');
        $endDate = $request->input('end_date') ?: Carbon::now()->addMonth()->format('Y-m-d');
        $teacherId = $teacher['id'];

        // Получение расписания преподавателя за указанный период
        $scheduleClasses = GroupScheduleClass::whereHas('subject.teacherSubject', function ($query) use ($teacherId) {
            $query->where('userTeacherId', $teacherId);
        })
        ->whereBetween('date', [$startDate, $endDate])
        ->with(['group', 'subject.teacherSubject.subject'])
        ->get();

        // Форматирование расписания в нужный формат
        $schedule = [];
        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $date) {
            $dailySchedule = $scheduleClasses->filter(function ($class) use ($date) {
                return Carbon::parse($class->date)->format('Y-m-d') == $date->format('Y-m-d');
            });

            $formattedDailySchedule = $dailySchedule->map(function ($class) {
                return [
                    'subject' => $class->subject->teacherSubject->subject,
                    'group' => $class->group->title,
                    'subgroup' => $class->subgroup,
                    'number' => $class->number,
                ];
            });

            $schedule[] = [
                'day_of_week' => $date->format('l'),
                'date' => $date->format('Y-m-d'),
                'classes' => $formattedDailySchedule->values()->all(),
            ];
        }

        return response()->json($schedule);
    }

    public function addRequest(Request $request, $groupId) {
         $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'subjectId' => 'required|exists:group_subjects,id',
            'subgroup' => 'nullable|string|in:A,B',
            'number' => 'required|integer',
            'reason' => 'required|string|min:7'
        ]);

        $group = GroupModal::find($groupId);

        if (!$group) {
            return response()->json(['error' => '404 Group Not Found', 'status'=>404], 404);
        }

        $conditions = [
            'date' => $request->input('date'),
            'number' => $request->input('number'),
            'groupId' => $groupId,
        ];

        if (GroupScheduleClassReplacementRq::where(['number' => $request->input('number'), 'groupId' => $groupId])->exists()) {
            return response()->json(['error' => '409 Conflict. Request for this day already exists.', 'status'=>409], 409);
        }

        GroupScheduleClassReplacementRq::create([
            'groupId' => $groupId,
            'date' => $request->input('date'),
            'subjectId' => $request->input('subjectId'),
            'subgroup' => $request->input('subgroup', null) || null,
            'number' => $request->input('number')
        ]);

        return response()->json(['status' => 201], 201);
    }
}
