<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherAccountGenerate extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_teachers')->insert([
            [
                'login'=>'teacher1',
                'password'=>Hash::make('teacher1')
            ],
            [
                'login'=>'teacher2',
                'password'=>Hash::make('teacher2')
            ]
        ]);
    }
}
