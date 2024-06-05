<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserStudent;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    // POST - Создание студента
    public function create(Request $request) {

        $user = $request->user;

        // Проверка на обладание правами редактирования пользователей.
        if (!AdminPrivilege::where(['userAdminId'=>$user['id'],'privilege'=>'UserManager'])->first()) {
            return response()->json([
                'status'=>'error', 
                'statusMessage'=>'У вас не хвататет прав. Необходимы права редактора пользователей.'
            ], 403);
        }

        $valid_data = $request->validate([
            'login' => 'required|exists:user_students,login',
            'password' => 'required',
            'fio' => 'required'
        ]);

        $student = UserStudent::create([
            'login'=>$valid_data['login'],
            'password'=>Hash::make($valid_data['password']),
            'fio'=>$valid_data['fio'],
            'created_at'=>now()
        ]);

        return resposne()->json(['status'=>'success', 'statusMessage'=>''], 200);
    }
    // GET - Получение информации о студенте. Инфо, группа, куратор и т.д.
    public function info(Request $request, $student_id) {
        $user = $request->user;

        // Проверка на обладание правами редактирования пользователей.
        if (!AdminPrivilege::where(['userAdminId'=>$user['id'],'privilege'=>'UserManager'])->first()) {
            return response()->json(['status'=>'error', 'statusMessage'=>'У вас не хвататет прав. Необходимы права редактора пользователей.'], 403);
        }

        $student = UserStudent::where(['id'=>$student_id])->first();
        
        if ($student) {
            return resposne()->json(['status'=>'success', 'statusMessage'=>'', 'data'=>[
                'login'=>$student->login,
                'fio'=>$student->fio,
                'created_at'=>$student->created_at
            ]], 200);
        }
        return response()->json(['status'=>'error', 'statusMessage'=>'Пользователей не найден.'], 204);
    }
    
    // POST - Удалние студента
    public function delete(Request $request) {
        $user = $request->user;

        // Проверка на обладание правами редактирования пользователей.
        if (!AdminPrivilege::where(['userAdminId'=>$user['id'],'privilege'=>'UserManager'])->first()) {
            return response()->json(['status'=>'error', 'statusMessage'=>'У вас не хвататет прав. Необходимы права редактора пользователей.'], 403);
        }

        // Получаем данные о новом студенте.
        $valid_data = $request->validate([
            'login' => 'required|exists:user_students,login',
        ]);

        $student = UserStudent::where(['login'=>$valid_data['login']])->first();

        if ($student) {
            $student->delete();
            return resposne()->json(['status'=>'success', 'statusMessage'=>'Студент успешно удален'], 200);
        }
        return response()->json(['status'=>'error', 'statusMessage'=>'Пользователей не найден.'], 204);
    }
}
