<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
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
     * Seed the application's database.
     */
    public function run(): void
    {
        // ^ Предметы

        DB::table('subjects')->insert([
            'title'=>'математика',
        ]);

        DB::table('subjects')->insert([
            'title'=>'Основа права',
        ]);

        DB::table('subjects')->insert([
            'title'=>'Физкультура',
        ]);

        DB::table('subjects')->insert([
            'title'=>'Физика',
        ]);

        DB::table('subjects')->insert([
            'title'=>'Основа предпринимательства',
        ]);

        DB::table('subjects')->insert([
            'title'=>'Информатика',
        ]);

        DB::table('subjects')->insert([
            'title'=>'Основы Frontend',
        ]);

        DB::table('subjects')->insert([
            'title'=>'Разработка мобильных приложений',
        ]);



        // ^ Аудитории

        DB::table('auditoria')->insert([
            'number'=>2117
        ]);

        DB::table('auditoria')->insert([
            'number'=>3213
        ]);



        // ^ Администраторы с разными ролями

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




        // ^ Преподаватели

        DB::table('user_teachers')->insert([
            'login' => self::toLogin('Попов Денис Валентинович'),
            'password' => Hash::make('popov12'),
            'fio' => 'Попов Денис Валентинович',
            'auditoriaId' => DB::table('auditoria')->where(['number'=>3213])->value('id'),
            'created_at'=>now()
        ]);

        DB::table('user_teachers')->insert([
            'login' => self::toLogin('Гульнар Нурхамитовна'),
            'password' => Hash::make('gulnar12'),
            'fio' => 'Гульнар Нурхамитовна',
            'auditoriaId' => DB::table('auditoria')->where(['number'=>2117])->value('id'),
            'created_at'=>now()
        ]);



        // ^ Предметы преподавателей

        DB::table('user_teacher_subjects')->insert([
            'userTeacherId' => DB::table('user_teachers')->where(['login'=>self::toLogin('Попов Денис Валентинович')])->value('id'),
            'subjectId' => DB::table('subjects')->where(['title'=>'Основы Frontend'])->value('id'),
            'created_at'=>now()
        ]);

        DB::table('user_teacher_subjects')->insert([
            'userTeacherId' => DB::table('user_teachers')->where(['login'=>self::toLogin('Попов Денис Валентинович')])->value('id'),
            'subjectId' => DB::table('subjects')->where(['title'=>'Разработка мобильных приложений'])->value('id'),
            'created_at'=>now()
        ]);

        DB::table('user_teacher_subjects')->insert([
            'userTeacherId' => DB::table('user_teachers')->where(['login'=>self::toLogin('Гульнар Нурхамитовна')])->value('id'),
            'subjectId' => DB::table('subjects')->where(['title'=>'Физика'])->value('id'),
            'created_at'=>now()
        ]);



        // ^ Специальности

        DB::table('departments')->insert([
            'title' => 'Разработчик ПО'
        ]);
        
        DB::table('departments')->insert([
            'title' => 'Техник информационных систем'
        ]);
        
        DB::table('departments')->insert([
            'title' => 'Техник информационной безопасности'
        ]);
        


        // ^ Группы

        DB::table('groups')->insert([
            'title' => 'П-21-57к',
            'departmentId' => DB::table('departments')->where(['title'=>'Разработчик ПО'])->value('id'),
            'userTeacherId' => DB::table('user_teachers')->where(['fio'=>'Гульнар Нурхамитовна'])->value('id'),
            'color' => '008000',  // Hex
            'created_at'=>now()
        ]);
        


        // ^ Предметы группы

        DB::table('group_subjects')->insert([
            'groupId' => DB::table('groups')->where(['title'=>'П-21-57к'])->value('id'),
            'teacherSubjectId' => DB::table('user_teachers')->where(['login'=>self::toLogin('Попов Денис Валентинович')])->value('id'),
            'created_at'=>now()
        ]);
        


        // ^ Студенты

        DB::table('user_students')->insert([
            'login' => self::toLogin('Ульданов Мансур Азатович'),
            'password' => Hash::make('uldanov12'),
            'fio' => 'Ульданов Мансур Азатович',
            'groupId' => DB::table('groups')->where(['title'=>'П-21-57к'])->value('id'),
            'subgroup' => 'B',
            'created_at'=>now()
        ]);

        DB::table('user_students')->insert([
            'login' => self::toLogin('Кишибаев Нуржан Еркешович'),
            'password' => Hash::make('kishibaev12'),
            'fio' => 'Кишибаев Нуржан Еркешович',
            'groupId' => DB::table('groups')->where(['title'=>'П-21-57к'])->value('id'),
            'subgroup' => 'A',
            'created_at'=>now()
        ]);

        DB::table('user_students')->insert([
            'login' => self::toLogin('Ким Богдан Данилович'),
            'password' => Hash::make('kim12'),
            'fio' => 'Ким Богдан Данилович',
            'groupId' => DB::table('groups')->where(['title'=>'П-21-57к'])->value('id'),
            'subgroup' => 'A',
            'created_at'=>now()
        ]);
        


        // ^ Расписание

        DB::table('group_schedule_classes')->insert([
            'groupId' => DB::table('groups')->where(['title'=>'П-21-57к'])->value('id'),
            'date' => '2024-01-01',
            'subjectId' => DB::table('group_subjects')->where(['groupId' => DB::table('groups')->where(['title'=>'П-21-57к'])->value('id')])->value('id'),
            'number' => 2,
            'subgroup' => '',
            'created_at'=>now()
        ]);

        DB::table('group_schedule_classes')->insert([
            'groupId' => DB::table('groups')->where(['title'=>'П-21-57к'])->value('id'),
            'date' => '2024-01-01',
            'subjectId' => DB::table('group_subjects')->where(['groupId' => DB::table('groups')->where(['title'=>'П-21-57к'])->value('id')])->value('id'),
            'number' => 3,
            'subgroup' => '',
            'created_at'=>now()
        ]);

        DB::table('group_schedule_classes')->insert([
            'groupId' => DB::table('groups')->where(['title'=>'П-21-57к'])->value('id'),
            'date' => '2024-01-01',
            'subjectId' => DB::table('group_subjects')->where(['groupId' => DB::table('groups')->where(['title'=>'П-21-57к'])->value('id')])->value('id'),
            'number' => 4,
            'subgroup' => '',
            'created_at'=>now()
        ]);

        

        
        
        
        








    }
}
