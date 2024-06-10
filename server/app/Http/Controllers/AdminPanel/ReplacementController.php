<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\GroupScheduleClassReplacementRq;
use App\Models\GroupScheduleClassReplacement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReplacementController extends Controller
{
    /**
     * Получение запросов на замену (всех).
     */
    public function requestsList()
    {
        $requests = GroupScheduleClassReplacementRq::with(['teacher', 'scheduleClass'])->get();

        return response()->json(['status'=>200,'data'=>$requests], 200);
    }

    /**
     * Получение запросов на замену за текущий месяц или указанный в параметре.
     */
    public function requestsListMonth(Request $request)
    {
        $month = $request->query('month', Carbon::now()->month);
        $year = $request->query('year', Carbon::now()->year);

        $requests = GroupScheduleClassReplacementRq::with(['teacher', 'scheduleClass'])
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get();

        return response()->json(['status'=>200, 'data'=>$requests], 200);
    }

    /**
     * Получение запросов на замену за указанный период.
     */
    public function requestsListDateRange(Request $request)
    {
        // Получение параметров начала и конца периода
        $startDate = $request->query('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->query('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Валидация формата дат
        try {
            $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date format. Example: 2024-05-11', 'status' => 400], 400);
        }

        // Получение запросов на замену за указанный период
        $requests = GroupScheduleClassReplacementRq::with(['subject', 'group', 'subject.teacherSubject.teacher.auditorium'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        return response()->json(['status' => 200, 'data' => $requests], 200);
    }

    /**
     * Отказать в замене.
     */
    public function rejectReplacement(Request $request, $replacementId)
    {
        $request = GroupScheduleClassReplacementRq::find($replacementId);

        if (!$request) {
            return response()->json(['error' => '404 Request Not Found', 'status'=>404], 404);
        }

        $request->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Одобрить замену. Удаляет все запросы которые были на данную дату и данный номер пары.
     */
    public function confirmReplacement(Request $request, $replacementId)
    {
        // Найти запрос на замену по id
        $requestReplacement = GroupScheduleClassReplacementRq::find($replacementId);

        if (!$requestReplacement) {
            return response()->json(['error' => '404 Request Not Found', 'status' => 404], 404);
        }

        // Создание подтвержденной замены
        $replacement = GroupScheduleClassReplacement::create([
            'userTeacherId' => $requestReplacement->userTeacherId,
            'groupScheduleClassId' => $requestReplacement->groupScheduleClassId,
            'subgroup' => $requestReplacement->subgroup,
            'reason' => $requestReplacement->reason,
        ]);

        // Удаление всех запросов на замену для данной даты и номера пары
        GroupScheduleClassReplacementRq::where('date', $requestReplacement->date)
            ->where('number', $requestReplacement->number)
            ->delete();

        // Удаление текущего запроса
        $requestReplacement->delete();

        return response()->json(['success' => true, 'data' => $replacement]);
    }


    /**
     * Получение всех одобренных замен.
     */
    public function list(Request $request)
    {
        $replacements = GroupScheduleClassReplacement::with(['teacher', 'scheduleClass'])->get();

        return response()->json($replacements);
    }
}
