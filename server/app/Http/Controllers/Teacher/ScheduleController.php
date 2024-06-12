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

    public function getDaysOfWeek($startDate, $endDate) {
        $start = Carbon::createFromFormat('Y-m-d', $startDate);
        $end = Carbon::createFromFormat('Y-m-d', $endDate);
        
        $days = [];

        while ($start->lte($end)) {
            $days[] = [
                'day' => $start->format('Y-m-d'),
                'dayWeek' => $start->dayOfWeek+1
            ];
            $start->addDay();
        }

        return $days;
    }

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

        $dayScheduleList = collect($this->getDaysOfWeek($startDate, $endDate))->map(function($daySchedule) use ($teacher) {
            $day = $daySchedule['day'];
            $dayWeek = $daySchedule['dayWeek'];

            $scheduleClasses = GroupScheduleClass::with(['subject.teacherSubject.teacher' => function($query) use ($teacher) {
                $query->where('id', $teacher['id']);
            }, 'subject.teacherSubject.subject'])->where('dayWeek', $dayWeek)->get();

            // Разделение общих занятий на подгруппы и группировка по номеру
            $processedClasses = [];

                dd(GroupScheduleClass::with(['subject.teacherSubject.teacher' => function($query) use ($teacher) {
                $query->where('id', $teacher['id']);
            }, 'subject.teacherSubject.subject'])->where('dayWeek', 6)->get()->toArray());

            foreach ($scheduleClasses as $class) {
                if (is_null($class->subgroup)) {
                    $processedClasses[intval($class->number)]['A'] = $this->createSubgroupClass($class, 'A');
                    $processedClasses[intval($class->number)]['B'] = $this->createSubgroupClass($class, 'B');
                } else {
                    $processedClasses[intval($class->number)][$class->subgroup] = $this->createSubgroupClass($class, $class->subgroup);
                }
            }

            // Сортировка по номеру занятия
            ksort($processedClasses);

            $daySchedule['scheduleClasses'] = $processedClasses;

            return $daySchedule;
        });

        return response()->json($dayScheduleList);
    }

    /**
     * Создает копию класса для подгруппы.
     *
     * @param  GroupScheduleClass  $class
     * @param  string  $subgroup
     * @return object
     */
    private function createSubgroupClass($class, $subgroup)
    {
        return (object) [
            'id' => $class->id,
            'groupId' => $class->groupId,
            'subjectId' => $class->subjectId,
            'subgroup' => $subgroup,
            'number' => $class->number,
            'dayWeek' => $class->dayWeek,
        ];
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
            'number' => $request->input('number'),
            'reason' => $request->input('reason')
        ]);

        return response()->json(['status' => 201], 201);
    }
}
