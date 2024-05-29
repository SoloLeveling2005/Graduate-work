<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminAccountGenerate extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_admins')->insert([
            'login'=>'admin',
            'password'=>Hash::make('admin'),
            'created_at'=>now()
        ]);

        DB::table('admin_privileges')->insert([
            'userAdminId'=>DB::table('user_admins')->where(['login'=>'admin'])->value('id'),
            'privilege'=>'UserManager'
        ]);

        DB::table('admin_privileges')->insert([
            'userAdminId'=>DB::table('user_admins')->where(['login'=>'admin'])->value('id'),
            'privilege'=>'GroupManager'
        ]);

        DB::table('admin_privileges')->insert([
            'userAdminId'=>DB::table('user_admins')->where(['login'=>'admin'])->value('id'),
            'privilege'=>'ScheduleManager'
        ]);
    }
}
