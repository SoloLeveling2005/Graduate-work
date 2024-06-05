<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\UserAdmin;
use App\Models\AdminPrivilege;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Создание администратора.
     */
    public function create(Request $request)
    {
        $request->validate([
            'fio' => 'required|string|max:155',
            'privileges' => 'array',
            'privileges.*' => 'string'
        ]);

        $fio = $request->input('fio');
        $login = Str::slug($fio);
        $password = Hash::make('12345678');

        $admin = UserAdmin::create([
            'login' => $login,
            'password' => $password,
        ]);

        $privileges = $request->input('privileges', []);
        foreach ($privileges as $privilege) {
            AdminPrivilege::create([
                'userAdminId' => $admin->id,
                'privilege' => $privilege,
            ]);
        }

        return response()->json(['success' => true, 'admin' => $admin], 201);
    }

    /**
     * Получение информации об администраторе.
     */
    public function info($adminId)
    {
        $admin = UserAdmin::with('privileges')->findOrFail($adminId);
        
        return response()->json($admin->makeHidden(['password', 'remember_token']));
    }
}
