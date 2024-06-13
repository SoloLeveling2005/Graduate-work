<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\UserAdmin;

class MeController extends Controller
{
    public function me(Request $request) {
        dd($request->user());
        $adminId = ($request->user)['id'];

        $admin = UserAdmin::with(['privileges'])->find($adminId);

        return response()->json($admin, 200);
    }
}
