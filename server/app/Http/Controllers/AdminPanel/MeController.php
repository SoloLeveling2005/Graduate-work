<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\UserAdmin;

class MeController extends Controller
{
    public function me(Request $request) {
        $adminId = ($request->user)['id'];
        $admin = ($request->user);

        return response()->json($admin, 200);
    }
}
