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
            ['title' => 'Математика'],
            ['title' => 'Основа права'],
            ['title' => 'Физкультура'],
            ['title' => 'Физика'],
            ['title' => 'Основа предпринимательства'],
            ['title' => 'Информатика'],
            ['title' => 'Основы Frontend'],
            ['title' => 'Разработка мобильных приложений'],
            ['title' => 'Химия'],
            ['title' => 'Биология'],
            ['title' => 'География'],
        ]);



        // ^ Аудитории

        DB::table('auditoria')->insert([
            'number'=>3213
        ]);

        DB::table('auditoria')->insert([
            'number'=>2117
        ]);

        DB::table('auditoria')->insert([
            'number'=>1010
        ]);

        DB::table('auditoria')->insert([
            'number'=>2020
        ]);

        DB::table('auditoria')->insert([
            'number'=>3030
        ]);

        DB::table('auditoria')->insert([
            'number'=>4040
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

        $teachers = [
            ['login' => self::toLogin('Попов Денис Валентинович'), 'password' => 123456780, 'fio' => 'Попов Денис Валентинович', 'auditoria' => 3213],
            ['login' => self::toLogin('Гульнар Нурхамитовна'), 'password' => 123456780, 'fio' => 'Гульнар Нурхамитовна', 'auditoria' => 2117],
            ['login' => self::toLogin('Иванов Иван Иванович'), 'password' => 123456780, 'fio' => 'Иванов Иван Иванович', 'auditoria' => 1010],
            ['login' => self::toLogin('Сидоров Петр Петрович'), 'password' => 123456780, 'fio' => 'Сидоров Петр Петрович', 'auditoria' => 2020],
            ['login' => self::toLogin('Смирнова Анна Владимировна'), 'password' => 123456780, 'fio' => 'Смирнова Анна Владимировна', 'auditoria' => 3030],
            ['login' => self::toLogin('Кузнецов Андрей Андреевич'), 'password' => 123456780, 'fio' => 'Кузнецов Андрей Андреевич', 'auditoria' => 4040],
        ];

        foreach ($teachers as $teacher) {
            DB::table('user_teachers')->insert([
                'login' => $teacher['login'],
                'password' => Hash::make($teacher['password']),
                'fio' => $teacher['fio'],
                'auditoriaId' => DB::table('auditoria')->where(['number' => $teacher['auditoria']])->value('id'),
                'created_at' => now()
            ]);
        }


        // ^ Предметы преподавателей

        $teacherSubjects = [
            ['login' => 'Попов Денис Валентинович', 'subjects' => ['Основы Frontend', 'Разработка мобильных приложений']],
            ['login' => 'Гульнар Нурхамитовна', 'subjects' => ['Физика']],
            ['login' => 'Иванов Иван Иванович', 'subjects' => ['Математика', 'Информатика']],
            ['login' => 'Сидоров Петр Петрович', 'subjects' => ['Физкультура', 'География']],
            ['login' => 'Смирнова Анна Владимировна', 'subjects' => ['Химия', 'Биология']],
            ['login' => 'Кузнецов Андрей Андреевич', 'subjects' => ['Основа права', 'Основа предпринимательства']],
        ];

        foreach ($teacherSubjects as $teacherSubject) {
            $userTeacherId = DB::table('user_teachers')->where(['login' => self::toLogin($teacherSubject['login'])])->value('id');
            foreach ($teacherSubject['subjects'] as $subject) {
                DB::table('user_teacher_subjects')->insert([
                    'userTeacherId' => $userTeacherId,
                    'subjectId' => DB::table('subjects')->where(['title' => $subject])->value('id'),
                    'created_at' => now()
                ]);
            }
        }



        // ^ Специальности

        DB::table('departments')->insert([
            ['title' => 'Разработчик ПО'],
            ['title' => 'Техник информационных систем'],
            ['title' => 'Техник информационной безопасности'],
            ['title' => 'Сетевой администратор'],
            ['title' => 'Администратор баз данных'],
        ]);
                


        // ^ Группы

        $groups = [
            ['title' => 'П-21-57к', 'department' => 'Разработчик ПО', 'teacher' => 'Гульнар Нурхамитовна', 'color' => 'F97316'],
            ['title' => 'П-21-58к', 'department' => 'Разработчик ПО', 'teacher' => 'Иванов Иван Иванович', 'color' => '0EA5E9'],
            ['title' => 'ТИ-21-21', 'department' => 'Техник информационных систем', 'teacher' => 'Сидоров Петр Петрович', 'color' => 'F87171'],
            ['title' => 'ТИ-21-22', 'department' => 'Техник информационных систем', 'teacher' => 'Смирнова Анна Владимировна', 'color' => 'C084FC'],
            ['title' => 'ТИБ-21-01', 'department' => 'Техник информационной безопасности', 'teacher' => 'Кузнецов Андрей Андреевич', 'color' => 'FDBA74'],
        ];

        foreach ($groups as $group) {
            DB::table('groups')->insert([
                'title' => $group['title'],
                'departmentId' => DB::table('departments')->where(['title' => $group['department']])->value('id'),
                'userTeacherId' => DB::table('user_teachers')->where(['fio' => $group['teacher']])->value('id'),
                'color' => $group['color'],
                'created_at' => now()
            ]);
        }
                


        // ^ Предметы группы

        $groupSubjects = [
            ['group' => 'П-21-57к', 'teacher' => 'Попов Денис Валентинович', 'subjects' => ['Основы Frontend', 'Разработка мобильных приложений']],
            ['group' => 'П-21-57к', 'teacher' => 'Иванов Иван Иванович', 'subjects' => ['Математика', 'Информатика']],
            ['group' => 'П-21-57к', 'teacher' => 'Сидоров Петр Петрович', 'subjects' => ['Физкультура', 'География']],
            ['group' => 'П-21-57к', 'teacher' => 'Смирнова Анна Владимировна', 'subjects' => ['Химия', 'Биология']],
            ['group' => 'П-21-58к', 'teacher' => 'Иванов Иван Иванович', 'subjects' => ['Математика', 'Информатика']],
            ['group' => 'ТИ-21-21', 'teacher' => 'Сидоров Петр Петрович', 'subjects' => ['Физкультура', 'География']],
            ['group' => 'ТИ-21-22', 'teacher' => 'Смирнова Анна Владимировна', 'subjects' => ['Химия', 'Биология']],
            ['group' => 'ТИБ-21-01', 'teacher' => 'Кузнецов Андрей Андреевич', 'subjects' => ['Основа права', 'Основа предпринимательства']],
        ];

        foreach ($groupSubjects as $groupSubject) {
            $groupId = DB::table('groups')->where(['title' => $groupSubject['group']])->value('id');
            foreach ($groupSubject['subjects'] as $subject) {
                $teacherSubjectId = DB::table('user_teacher_subjects')
                    ->join('user_teachers', 'user_teacher_subjects.userTeacherId', '=', 'user_teachers.id')
                    ->where('user_teachers.login', self::toLogin($groupSubject['teacher']))
                    ->where('user_teacher_subjects.subjectId', DB::table('subjects')->where(['title' => $subject])->value('id'))
                    ->value('user_teacher_subjects.id');
                
                DB::table('group_subjects')->insert([
                    'groupId' => $groupId,
                    'teacherSubjectId' => $teacherSubjectId,
                    'created_at' => now()
                ]);

                $group_subject = DB::table('group_subjects')->where([
                    'groupId' => $groupId,
                    'teacherSubjectId' => $teacherSubjectId
                ])->first();

                DB::table('classrooms')->insert([
                    'groupSubjectId' => $group_subject->id
                ]);
            }
        }
        


        // ^ Студенты

        $students = [
            ['login' => 'Ульданов Мансур Азатович', 'password' => 123456780, 'fio' => 'Ульданов Мансур Азатович', 'group' => 'П-21-57к', 'subgroup' => 'B'],
            ['login' => 'Кишибаев Нуржан Еркешович', 'password' => 123456780, 'fio' => 'Кишибаев Нуржан Еркешович', 'group' => 'П-21-57к', 'subgroup' => 'A'],
            ['login' => 'Ким Богдан Данилович', 'password' => 123456780, 'fio' => 'Ким Богдан Данилович', 'group' => 'П-21-57к', 'subgroup' => 'A'],
            ['login' => 'Иванова Анна Сергеевна', 'password' => 123456780, 'fio' => 'Иванова Анна Сергеевна', 'group' => 'П-21-58к', 'subgroup' => 'B'],
            ['login' => 'Петров Александр Дмитриевич', 'password' => 123456780, 'fio' => 'Петров Александр Дмитриевич', 'group' => 'П-21-58к', 'subgroup' => 'A'],
            ['login' => 'Смирнова Елена Васильевна', 'password' => 123456780, 'fio' => 'Смирнова Елена Васильевна', 'group' => 'ТИ-21-21', 'subgroup' => 'B'],
            ['login' => 'Кузнецов Михаил Игоревич', 'password' => 123456780, 'fio' => 'Кузнецов Михаил Игоревич', 'group' => 'ТИ-21-21', 'subgroup' => 'A'],
            ['login' => 'Сидорова Мария Алексеевна', 'password' => 123456780, 'fio' => 'Сидорова Мария Алексеевна', 'group' => 'ТИ-21-22', 'subgroup' => 'B'],
            ['login' => 'Николаев Дмитрий Петрович', 'password' => 123456780, 'fio' => 'Николаев Дмитрий Петрович', 'group' => 'ТИ-21-22', 'subgroup' => 'A'],
            ['login' => 'Федоров Сергей Иванович', 'password' => 123456780, 'fio' => 'Федоров Сергей Иванович', 'group' => 'ТИБ-21-01', 'subgroup' => 'B'],
            ['login' => 'Михайлова Ольга Викторовна', 'password' => 123456780, 'fio' => 'Михайлова Ольга Викторовна', 'group' => 'ТИБ-21-01', 'subgroup' => 'A'],
        ];

        foreach ($students as $student) {
            DB::table('user_students')->insert([
                'login' => self::toLogin($student['login']),
                'password' => Hash::make($student['password']),
                'fio' => $student['fio'],
                'groupId' => DB::table('groups')->where(['title' => $student['group']])->value('id'),
                'subgroup' => $student['subgroup'],
                'created_at' => now()
            ]);
        }

        


        // ^ Расписание

 $scheduleClasses = [
    // Понедельник
    ['groupId' => 1, 'teacherSubjectId' => 1, 'number' => 1, 'dayWeek' => 1], // Основы Frontend, Попов Денис Валентинович
    ['groupId' => 1, 'teacherSubjectId' => 2, 'number' => 2, 'dayWeek' => 1], // Разработка мобильных приложений, Попов Денис Валентинович
    ['groupId' => 1, 'teacherSubjectId' => 3, 'number' => 3, 'dayWeek' => 1], // Физика, Гульнар Нурхамитовна
    ['groupId' => 1, 'teacherSubjectId' => 4, 'number' => 4, 'dayWeek' => 1], // Математика, Иванов Иван Иванович
    ['groupId' => 1, 'teacherSubjectId' => 5, 'number' => 5, 'dayWeek' => 1], // Информатика, Иванов Иван Иванович

    // Вторник
    ['groupId' => 1, 'teacherSubjectId' => 1, 'number' => 1, 'dayWeek' => 2], // Основа права, Кузнецов Андрей Андреевич
    ['groupId' => 1, 'teacherSubjectId' => 4, 'number' => 2, 'dayWeek' => 2], // Основа предпринимательства, Кузнецов Андрей Андреевич
    ['groupId' => 1, 'teacherSubjectId' => 3, 'number' => 3, 'dayWeek' => 2], // Физкультура, Сидоров Петр Петрович
    ['groupId' => 1, 'teacherSubjectId' => 2, 'number' => 4, 'dayWeek' => 2], // География, Сидоров Петр Петрович
    ['groupId' => 1, 'teacherSubjectId' => 5, 'number' => 5, 'dayWeek' => 2], // Химия, Смирнова Анна Владимировна

    // Среда
    ['groupId' => 1, 'teacherSubjectId' => 4, 'number' => 1, 'dayWeek' => 3], // Биология, Смирнова Анна Владимировна
    ['groupId' => 1, 'teacherSubjectId' => 3, 'number' => 2, 'dayWeek' => 3], // Физика, Гульнар Нурхамитовна
    ['groupId' => 1, 'teacherSubjectId' => 5, 'number' => 3, 'dayWeek' => 3], // Основы Frontend, Попов Денис Валентинович
    ['groupId' => 1, 'teacherSubjectId' => 1, 'number' => 4, 'dayWeek' => 3], // Разработка мобильных приложений, Попов Денис Валентинович
    ['groupId' => 1, 'teacherSubjectId' => 2, 'number' => 5, 'dayWeek' => 3], // Математика, Иванов Иван Иванович

    // Четверг
    ['groupId' => 1, 'teacherSubjectId' => 1, 'number' => 1, 'dayWeek' => 4], // Информатика, Иванов Иван Иванович
    ['groupId' => 1, 'teacherSubjectId' => 2, 'number' => 2, 'dayWeek' => 4], // Основа права, Кузнецов Андрей Андреевич
    ['groupId' => 1, 'teacherSubjectId' => 3, 'number' => 3, 'dayWeek' => 4], // Основа предпринимательства, Кузнецов Андрей Андреевич
    ['groupId' => 1, 'teacherSubjectId' => 4, 'number' => 4, 'dayWeek' => 4], // Физкультура, Сидоров Петр Петрович
    ['groupId' => 1, 'teacherSubjectId' => 5, 'number' => 5, 'dayWeek' => 4], // География, Сидоров Петр Петрович

    // Пятница
    ['groupId' => 1, 'teacherSubjectId' => 2, 'number' => 2, 'dayWeek' => 5], // Биология, Смирнова Анна Владимировна
    ['groupId' => 1, 'teacherSubjectId' => 3, 'number' => 3, 'dayWeek' => 5], // Физика, Гульнар Нурхамитовна
    ['groupId' => 1, 'teacherSubjectId' => 3, 'number' => 4, 'dayWeek' => 5], // Основы Frontend, Попов Денис Валентинович
    ['groupId' => 1, 'teacherSubjectId' => 5, 'number' => 5, 'dayWeek' => 5], // Разработка мобильных приложений, Попов Денис Валентинович

    // Суббота
    ['groupId' => 1, 'teacherSubjectId' => 1, 'number' => 1, 'dayWeek' => 6], // Математика, Иванов Иван Иванович
    ['groupId' => 1, 'teacherSubjectId' => 1, 'number' => 2, 'dayWeek' => 6], // Информатика, Иванов Иван Иванович
    ['groupId' => 1, 'teacherSubjectId' => 2, 'number' => 3, 'dayWeek' => 6], // Основа права, Кузнецов Андрей Андреевич
    ['groupId' => 1, 'teacherSubjectId' => 4, 'number' => 4, 'dayWeek' => 6], // Основа предпринимательства, Кузнецов Андрей Андреевич
    ['groupId' => 1, 'teacherSubjectId' => 4, 'number' => 5, 'dayWeek' => 6], // Физкультура, Сидоров Петр Петрович

];

foreach ($scheduleClasses as $scheduleClass) {
    DB::table('group_schedule_classes')->insert([
        'groupId' => DB::table('groups')->where('title', 'П-21-57к')->value('id'),
        'subjectId' => $scheduleClass['teacherSubjectId'],
        'subgroup' => null,
        'number' => $scheduleClass['number'],
        'dayWeek' => $scheduleClass['dayWeek'],
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

        
        
        
        








    }
}
