<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Group as GroupModal;
use App\Models\GroupScheduleClass;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Получение расписания группы. (Проверено)
     */
    public function list(Request $request, $groupId)
    {
        $request->validate([
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d'
        ]);

        $group = GroupModal::find($groupId);

        if (!$group) {
            return response()->json(['error' => '404 Group Not Found', 'status'=>404], 404);
        }

        $startDate = $request->input('start_date') ?: Carbon::now()->startOfWeek()->format('Y-m-d');
        $endDate = $request->input('end_date') ?: Carbon::now()->addMonth()->format('Y-m-d');

        $schedule = GroupScheduleClass::where('groupId', $groupId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->orderBy('number')
            ->get();

        $result = [];

        foreach ($schedule as $scheduleClass) {
            $date = Carbon::parse($scheduleClass->date)->format('Y-m-d');
            $dayOfWeek = Carbon::parse($scheduleClass->date)->format('l'); // День недели

            if (!isset($result[$date])) {
                $result[$date] = [
                    'date' => $date,
                    'dayOfWeek' => $dayOfWeek,
                    'subjects' => []
                ];
            }

            $subject = $scheduleClass->subject;
            $teacherSubject = $subject->teacherSubject;
            $teacher = $teacherSubject->teacher;
            $auditorium = $teacher->auditorium;

            $subjectDetails = [
                'number' => $scheduleClass->number,
                'subgroup' => $scheduleClass->subgroup,
                'subject' => $teacherSubject->subject->title,
                'teacher' => $teacher->fio,
                'auditorium' => $auditorium ? $auditorium->number : null
            ];

            if ($scheduleClass->subgroup === null) {
                // Если пара общая, добавляем её как общую
                $result[$date]['subjects'][] = $subjectDetails;
            } else {
                // Иначе добавляем в соответствующую подгруппу
                $result[$date]['subjects'][] = $subjectDetails;
            }
        }

        return response()->json(['schedule' => array_values($result), 'status' => 200], 200);
    }


    /**
     * Изменение предмета в расписании за определенный день. (Проверено)
     */
    public function changeSubject(Request $request, $groupId)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'subjectId' => 'required|exists:group_subjects,id',
            'subgroup' => 'nullable|string|in:A,B',
            'number' => 'required|integer'
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

        if ($request->input('subgroup')) {
            $conditions['subgroup'] = $request->input('subgroup');
            // Удаление общей пары, если существует
            GroupScheduleClass::where([
                'date' => $request->input('date'),
                'number' => $request->input('number'),
                'groupId' => $groupId,
                'subgroup' => null
            ])->delete();
        } else {
            // Если запрос идет на создание общей пары, удаляем записи для обеих подгрупп
            GroupScheduleClass::where([
                'date' => $request->input('date'),
                'number' => $request->input('number'),
                'groupId' => $groupId,
            ])->whereIn('subgroup', ['A', 'B'])->delete();

            $conditions['subgroup'] = null;
        }

        $scheduleClass = GroupScheduleClass::where($conditions)->first();

        if ($scheduleClass) {
            // Если занятие за эту дату и пару уже есть, то меняем предмет
            $scheduleClass->subjectId = $request->input('subjectId');
            $scheduleClass->subgroup = $request->input('subgroup');
            $scheduleClass->save();
        } else {
            // Если занятие пустует, то мы создаем занятие
            $scheduleClass = GroupScheduleClass::create([
                'groupId' => $groupId,
                'date' => $request->input('date'),
                'subjectId' => $request->input('subjectId'),
                'subgroup' => $request->input('subgroup'),
                'number' => $request->input('number'),
            ]);
        }

        return response()->json(['success' => true, 'status'=>200], 200);
    }



}
