<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserStudent;

class MeController extends Controller
{
    public function me(Request $request) {
        $studetId = ($request->user)['id'];

        $student = UserStudent::with(['group', 'group.department', 'group.curator', 'group.subjects'])->find($studetId);

        return response()->json($student, 200);
    }
}
