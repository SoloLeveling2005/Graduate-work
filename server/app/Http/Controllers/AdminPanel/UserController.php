<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\UserAdmin;
use App\Models\UserTeacher;
use App\Models\UserStudent;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Получение списка пользователей с фильтрацией и сортировкой. (Проверено)
     */
    public function list(Request $request)
    {
        $query = collect();

        // Добавляем админов
        $admins = UserAdmin::query();
        if ($request->has('search')) {
            $search = $request->input('search');
            $admins->where('login', 'like', "%{$search}%");
        }
        $admins = $admins->get()->map(function ($admin) {
            return [
                'id' => $admin->id,
                'fio' => $admin->login,
                'role' => 'Admin',
                'privileges' => $admin->privileges->pluck('privilege')
            ];
        });

        // Добавляем преподавателей
        $teachers = UserTeacher::query();
        if ($request->has('search')) {
            $search = $request->input('search');
            $teachers->where('fio', 'like', "%{$search}%");
        }
        $teachers = $teachers->get()->map(function ($teacher) {
            return [
                'id' => $teacher->id,
                'fio' => $teacher->fio,
                'role' => 'Teacher',
            ];
        });

        // Добавляем студентов
        $students = UserStudent::query();
        if ($request->has('search')) {
            $search = $request->input('search');
            $students->where('fio', 'like', "%{$search}%");
        }
        $students = $students->get()->map(function ($student) {
            return [
                'id' => $student->id,
                'fio' => $student->fio,
                'role' => 'Student',
            ];
        });

        // Объединяем коллекции
        $query = $query->concat($admins)->concat($teachers)->concat($students);

        // Сортировка по роли (подроли)
        if ($request->has('sort')) {
            $query = $query->sortBy($request->input('sort'));
        }

        return response()->json($query->values()->all());
    }

    /**
     * Удаление админа. (Проверено)
     */
    public function removeAdmin($userId)
    {
        $user = UserAdmin::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $privileges = $user->privileges->map(function ($item) {
                return $item->privilege;
            });


        if (in_array('SuperAdmin', $privileges->toArray())) {
            return response()->json(['error' => 'Cannot delete Super Admin'], 403);
        }

        $user->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Удаление студента. (Проверено)
     */
    public function removeStudent($userId)
    {
        $user = UserStudent::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Удаление преподавателя. (Проверено)
     */
    public function removeTeacher($userId)
    {
        $user = UserTeacher::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['success' => true]);
    }
}
