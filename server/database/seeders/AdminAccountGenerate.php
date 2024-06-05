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
            'privilege'=>'SuperAdmin'
        ]);

        DB::table('user_admins')->insert([
            'login'=>'operator',
            'password'=>Hash::make('operator'),
            'created_at'=>now()
        ]);

        DB::table('admin_privileges')->insert([
            'userAdminId'=>DB::table('user_admins')->where(['login'=>'operator'])->value('id'),
            'privilege'=>'Operator'
        ]);

        DB::table('user_admins')->insert([
            'login'=>'group manager',
            'password'=>Hash::make('group manager'),
            'created_at'=>now()
        ]);
        
        DB::table('admin_privileges')->insert([
            'userAdminId'=>DB::table('user_admins')->where(['login'=>'group manager'])->value('id'),
            'privilege'=>'GroupManager'
        ]);

        DB::table('user_admins')->insert([
            'login'=>'schedule user',
            'password'=>Hash::make('schedule user'),
            'created_at'=>now()
        ]);

        DB::table('admin_privileges')->insert([
            'userAdminId'=>DB::table('user_admins')->where(['login'=>'schedule user'])->value('id'),
            'privilege'=>'ScheduleCoordinator'
        ]);

        // SuperAdmin
        // Operator
        // GroupManager
        // ScheduleCoordinator
    }
}
