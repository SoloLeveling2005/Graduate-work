<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminPrivilege;
use App\Models\UserAdmin;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // GET - Информация о пользователе
    public function info(Request $request) {

        $user = $request->user;

        $response_user = [
            'id'=>$user['id'],
            'login'=>$user['login'],
            'privelege'=> [AdminPrivilege::where(['userAdminId'=>$user['id']])->get(['id','privilege'])] // Privileges: UserManager (у главных админов) / GroupManager / ScheduleManager
        ];

        return response()->json($response_user, 200);
    }

    // GET - Роли админов
    public function adminRoles(Request $request) {

        $user = $request->user;

        // Проверка на обладание правами редактирования пользователей.
        if (!AdminPrivilege::where(['userAdminId'=>$user['id'],'privilege'=>'UserManager'])->first()) {
            return response()->json(['status'=>'error', 'statusMessage'=>'У вас не хвататет прав'], 403);
        }

        return response()->json(['data'=>['GroupManager','ScheduleManager','UserManager'], 'status'=>'success', 'statusMessage'=>''], 200);
    }

    // POST - Создание админа
    public function createAdmin(Request $request) {

        $user = $request->user;

        // Проверка на обладание правами редактирования пользователей.
        if (!AdminPrivilege::where(['userAdminId'=>$user['id'],'privilege'=>'UserManager'])->first()) {
            return response()->json(['status'=>'error', 'statusMessage'=>'У вас не хвататет прав. Необходимы права редактора пользователей.'], 403);
        }

        // Получаем данные о новом админе
        $valid_data = $request->validate([
            'login' => 'required|unique:user_admins,login',
            'password' => 'required',
            'privilegeList' => 'required|array'
        ]);

        // Создание администатора
        $new_user_admin = UserAdmin::create([
            'login'=>$valid_data['login'],
            'password'=>Hash::make($valid_data['password']),
            'created_at'=>now()
        ]);

        foreach ($privilege as $valid_data['privilegeList']) {
            AdminPrivilege::create([
                'userAdminId'=>$new_user_admin->id,
                'privilege'=>$privilege,
                'created_at'=>now()
            ]);
        }

        return response()->json(['status'=>'success', 'statusMessage'=>'Админ удачно создан'], 200);

    }

    // POST - Удаление админа по логину
    public function deleteAdminByLogin(Request $request) {

        $user = $request->user;

        // Проверка на обладание правами редактирования пользователей.
        if (!AdminPrivilege::where(['userAdminId'=>$user['id'],'privilege'=>'UserManager'])->first()) {
            return response()->json(['status'=>'error', 'statusMessage'=>'У вас не хвататет прав. Необходимы права редактора пользователей.'], 403);
        }

        // Получаем данные о новом админе
        $valid_data = $request->validate([
            'login' => 'required|exists:user_admins,login',
        ]);

        $user = UserAdmin::where(['login'=>$valid_data['login']])->first();

        if ($user) {
            $user->delete();
            return resposne()->json(['status'=>'success', 'statusMessage'=>'Админ удачно удален'], 200);
        }
        return resposne()->json(['status'=>'success', 'statusMessage'=>'Админ не найден'], 204);
    }
}
