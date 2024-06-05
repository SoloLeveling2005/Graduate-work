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

    public function transliterate($text) {
        $transliterationTable = array(
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
            'И' => 'I', 'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R',
            'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'Kh', 'Ц' => 'Ts', 'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Shch',
            'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch',
            'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya'
        );

        return strtr($text, $transliterationTable);
    }

    public function toLogin($name) {
        $parts = explode(' ', $name);
        $formattedName = '';

        foreach ($parts as $index => $part) {
            $transliteratedPart = self::transliterate($part);
            if ($index === 0) {
                $formattedName .= strtolower($transliteratedPart);
            } else {
                $formattedName .= ucfirst($transliteratedPart);
            }
        }

        return $formattedName;
    }

    /**
     * Создание администратора. (Проверено) 
     * 
     */
    public function create(Request $request)
    {

        $request->validate([
            'fio' => 'required|string|max:155',
            'privileges' => 'required|array',
            'privileges.*' => 'string'
        ]);


        $fio = $request->input('fio');
        $login = self::toLogin($fio);
        $password = Hash::make('12345678');

        $admin_exists = UserAdmin::where('login', $login)->exists();

        if ($admin_exists) {
            return response()->json(['error' => 'HTTP 409 Conflict', 'status'=>409], 409);
        }

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

        return response()->json(['status' => 200, 'admin' => $admin], 201);
    }

    /**
     * Получение информации об администраторе. (Проверено)
     */
    public function info($adminId)
    {
        $admin = UserAdmin::with('privileges')->find($adminId);

        if ($admin) {
            $admin->makeHidden(['password', 'remember_token']);

            $privileges = array_map(function($item) {
                return $item['privilege'];
            }, $admin['privileges']->toArray());
            
            $admin = $admin->toArray();
            $admin['privileges'] = $privileges;
        }
        

        return response()->json($admin, $admin ? 200 : 404);
    }
}
