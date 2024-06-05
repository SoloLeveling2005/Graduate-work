<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentAccountGenerate extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_students')->insert([
            [
                'login'=>'student1',
                'password'=>Hash::make('student1')
            ],
            [
                'login'=>'student2',
                'password'=>Hash::make('student2')
            ]
        ]);
    }
}
