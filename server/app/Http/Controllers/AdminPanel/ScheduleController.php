<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Group as GroupModal;
use App\Models\GroupScheduleClass;
use App\Models\GroupScheduleClassReplacement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Получение расписания группы без замен. (Проверено)
     */
    public function list(Request $request, $groupId)
    {
        $validated = $request->validate([
            'dayWeek' => 'integer|min:1|max:6',
        ]);

        $group = GroupModal::find($groupId);

        if (!$group) {
            return response()->json(['error' => '404 Group Not Found', 'status'=>404], 404);
        }

        $dates = [];

        // Получаем расписание для указанного дня недели
        if (isset($validated['dayWeek'])) {
            $scheduleClasses = GroupScheduleClass::where('groupId', $groupId)
                ->where('dayWeek', $validated['dayWeek'])
                ->get();
        } else {
            $scheduleClasses = GroupScheduleClass::where('groupId', $groupId)
                ->get();
            
            foreach ([1,2,3,4,5,6,7] as $dayWeek) {
                $dates[] = GroupScheduleClass::with(['subject.teacherSubject.teacher.auditorium'])->where('groupId', $groupId)->where('dayWeek', $dayWeek)->get()->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'groupId' => $item->groupId,
                        'subjectId' => $item->subjectId,
                        'subgroup' => $item->subgroup,
                        'number' => $item->number,
                        'dayWeek' => $item->dayWeek,
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at,
                        'auditorium' => $item->subject->teacherSubject->teacher->auditorium->number ?? null,
                    ];
                });
            }
        }


        return response()->json([
            'message' => 'Schedule with replacements retrieved successfully',
            'data' => $dates
        ], 200);
    }


    // /**
    //  * Изменение предмета в расписании за определенный день. (Проверено)
    //  */
    // public function changeSubject(Request $request, $groupId)
    // {
    //     $request->validate([
    //         'date' => 'required|date_format:Y-m-d',
    //         'subjectId' => 'required|exists:group_subjects,id',
    //         'subgroup' => 'nullable|string|in:A,B',
    //         'number' => 'required|integer'
    //     ]);

    //     $group = GroupModal::find($groupId);

    //     if (!$group) {
    //         return response()->json(['error' => '404 Group Not Found', 'status'=>404], 404);
    //     }

    //     $conditions = [
    //         'date' => $request->input('date'),
    //         'number' => $request->input('number'),
    //         'groupId' => $groupId,
    //     ];

    //     if ($request->input('subgroup')) {
    //         $conditions['subgroup'] = $request->input('subgroup');
    //         // Удаление общей пары, если существует
    //         GroupScheduleClass::where([
    //             'date' => $request->input('date'),
    //             'number' => $request->input('number'),
    //             'groupId' => $groupId,
    //             'subgroup' => null
    //         ])->delete();
    //     } else {
    //         // Если запрос идет на создание общей пары, удаляем записи для обеих подгрупп
    //         GroupScheduleClass::where([
    //             'date' => $request->input('date'),
    //             'number' => $request->input('number'),
    //             'groupId' => $groupId,
    //         ])->delete();

    //         $conditions['subgroup'] = null;
    //     }

    //     $scheduleClass = GroupScheduleClass::where($conditions)->first();

    //     if ($scheduleClass) {
    //         // Если занятие за эту дату и пару уже есть, то меняем предмет
    //         $scheduleClass->subjectId = $request->input('subjectId');
    //         $scheduleClass->subgroup = $request->input('subgroup');
    //         $scheduleClass->save();
    //     } else {
    //         // Если занятие пустует, то мы создаем занятие
    //         $scheduleClass = GroupScheduleClass::create([
    //             'groupId' => $groupId,
    //             'date' => $request->input('date'),
    //             'subjectId' => $request->input('subjectId'),
    //             'subgroup' => $request->input('subgroup'),
    //             'number' => $request->input('number'),
    //         ]);
    //     }

    //     return response()->json(['success' => true, 'status'=>200], 200);
    // }

    public function removeSubject(Request $request, $groupId) 
    {
        $validated = $request->validate([
            'number' => 'required|integer',
            'dayWeek' => 'required|integer|min:1|max:6',
        ]);

        $group = GroupModal::find($groupId);

        if (!$group) {
            return response()->json(['error' => '404 Group Not Found', 'status'=>404], 404);
        }

        $groupSchedule = GroupScheduleClass::where([
            'dayWeek' => $request->input('dayWeek'),
            'number' => $request->input('number'),
            'groupId' => $groupId,
        ])->exists();

        if (!$groupSchedule) {
            return response()->json(['error' => '404 Group shedule by this day and lesson number Not Found', 'status'=>404], 404);
        }

        GroupScheduleClass::where([
            'dayWeek' => $request->input('dayWeek'),
            'number' => $request->input('number'),
            'groupId' => $groupId,
        ])->delete();

        return response()->json(['status'=>200], 200);
    }

    public function changeSubject(Request $request, $groupId)
    {
        $validated = $request->validate([
            'subjectId' => 'nullable|exists:group_subjects,id',
            'subgroup' => 'nullable|string|max:1',
            'number' => 'required|integer',
            'dayWeek' => 'required|integer|min:1|max:6',
        ]);

        $group = GroupModal::find($groupId);

        if (!$group) {
            return response()->json(['error' => '404 Group Not Found', 'status'=>404], 404);
        }

        $conditions = [
            'dayWeek' => $request->input('dayWeek'),
            'number' => $request->input('number'),
            'groupId' => $groupId,
        ];

        if ($request->input('subgroup')) {
            $conditions['subgroup'] = $request->input('subgroup');
            // Удаление общей пары, если существует
            GroupScheduleClass::where([
                'dayWeek' => $request->input('dayWeek'),
                'number' => $request->input('number'),
                'groupId' => $groupId,
                'subgroup' => null
            ])->delete();
        } else {
            // Если запрос идет на создание общей пары, удаляем записи для обеих подгрупп
            GroupScheduleClass::where([
                'dayWeek' => $request->input('dayWeek'),
                'number' => $request->input('number'),
                'groupId' => $groupId,
            ])->delete();

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
                'dayWeek' => $request->input('dayWeek'),
                'subjectId' => $request->input('subjectId'),
                'subgroup' => $request->input('subgroup'),
                'number' => $request->input('number'),
            ]);
        }

        return response()->json([
            'message' => 'Schedule added successfully',
            'data' => $scheduleClass
        ], 201);
    }



}
