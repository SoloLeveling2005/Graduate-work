<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Получение списка групп с фильтрацией по специальности и поиском.
     * Возвращает id группы, литеру группы, количество студентов и ФИО куратора.
     */
    public function getList(Request $request)
    {
        $query = Group::query();

        // Фильтрация по специальности
        if ($request->has('specializationId')) {
            $query->where('departmentId', $request->input('specializationId'));
        }

        // Поиск по трем полям (литера, специальность, куратор)
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhereHas('department', function($q) use ($search) {
                          $q->where('title', 'like', "%{$search}%");
                      })
                      ->orWhereHas('curator', function($q) use ($search) {
                          $q->where('fio', 'like', "%{$search}%");
                      });
            });
        }

        // Получение списка групп
        $groups = $query->withCount('students')->with('curator:id,fio')->get(['id', 'title']);

        // Форматирование ответа
        $response = $groups->map(function($group) {
            return [
                'id' => $group->id,
                'title' => $group->title,
                'students_count' => $group->students_count,
                'curator' => $group->curator ? $group->curator->fio : null,
            ];
        });

        return response()->json($response);
    }

    /**
     * Проверка на существование названия группы.
     */
    public function checkName(Request $request)
    {
        $name = $request->query('name');
        $exists = Group::where('title', $name)->exists();

        return $exists ? response()->json(['message' => 'Name not available'], 409)
                       : response()->json(['message' => 'Name available'], 200);
    }

    /**
     * Создание группы.
     */
    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string|unique:groups,title|max:255',
            'departmentId' => 'required|exists:departments,id',
            'userTeacherId' => 'required|exists:user_teachers,id',
            'color' => 'required|string|max:7',
        ]);

        $group = Group::create($request->all());

        return response()->json(['success' => true, 'group' => $group], 201);
    }

    /**
     * Обновление специальности группы.
     */
    public function updateSpecialization(Request $request, $groupId)
    {
        $request->validate([
            'departmentId' => 'required|exists:departments,id',
        ]);

        $group = Group::findOrFail($groupId);
        $group->departmentId = $request->input('departmentId');
        $group->save();

        return response()->json(['success' => true]);
    }

    /**
     * Обновление куратора группы.
     */
    public function updateTeacher(Request $request, $groupId)
    {
        $request->validate([
            'userTeacherId' => 'required|exists:user_teachers,id',
        ]);

        $group = Group::findOrFail($groupId);
        $group->userTeacherId = $request->input('userTeacherId');
        $group->save();

        return response()->json(['success' => true]);
    }

    /**
     * Получение информации о группе.
     */
    public function getInfo($groupId)
    {
        $group = Group::with(['department', 'curator'])->findOrFail($groupId);

        $info = [
            'id' => $group->id,
            'title' => $group->title,
            'specialization' => $group->department,
            'curator' => $group->curator,
        ];

        return response()->json($info);
    }
}
