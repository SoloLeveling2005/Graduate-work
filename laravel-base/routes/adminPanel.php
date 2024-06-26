<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminPanel\Auth\AdminAuthController;
use App\Http\Controllers\AdminPanel\SubjectController;
use App\Http\Controllers\AdminPanel\GroupController;
use App\Http\Controllers\AdminPanel\SpecializationController;
use App\Http\Controllers\AdminPanel\AuditoriaController;
use App\Http\Controllers\AdminPanel\TeacherController;
use App\Http\Controllers\AdminPanel\StudentController;
use App\Http\Controllers\AdminPanel\AdminController;
use App\Http\Controllers\AdminPanel\GroupSubjectController;
use App\Http\Controllers\AdminPanel\ScheduleController;
use App\Http\Controllers\AdminPanel\UserController;
use App\Http\Controllers\AdminPanel\ReplacementController;
use App\Http\Controllers\AdminPanel\GroupStudentController;

use App\Http\Middleware\AdminPanel\AdminAuthMiddleware;

// Route::prefix('adminPanel')->group(function () {

    // TODO - Aвторизации админа. Выдаем токен.
    Route::post('signin', [AdminAuthController::class, 'signin']);

    // TODO - Выход админа. Удаляем токен.
    Route::post('signout', [AdminAuthController::class, 'signout']);

    // TODO - Middleware аутентификации админа (тут же получение и встройка в $request информация об админе)
    Route::middleware(AdminAuthMiddleware::class)->group(function () {

        // TODO - Получение списка предметов.
        Route::get('subjects/getList', [SubjectController::class, 'getList']);
        // Необходио вернуть: id предмета, название.

        // TODO - Получение всех групп (есть фильтрация по специальности, оно в преоритете) (есть возможность поиска. Как ведется поиск. В поле search подается текст и мы его изем по трем полям одновременно по литере, специальности, куратору)
        Route::get('groupList', [GroupController::class, 'getList']); 
        // Необходио вернуть: id группы, литера группы, кол-во студентов, ФИО куратора  

        // TODO - Получение всех специальностей.
        Route::get('specializationsList', [SpecializationController::class, 'getList']); 
        // Необходио вернуть: id специальности, название специальности.

        // TODO - ПОлучение всех аудиторий.
        Route::get('auditoriaList', [AuditoriaController::class, 'getList']);
        // Необходио вернуть: id аудитории, номер аудитории (number).

        Route::prefix('teachers')->group(function () {

            // TODO - Создание преподавателя (ФИО, логин (Генерируется из ФИО), пароль (по умолчанию, 123456780), список предметов за которые он отвечает).
            Route::post('create', [TeacherController::class, 'create']);

            // TODO - Получение всех преподавателей (по умолчанию нет сортировка, если указана в get праметре => сортировка по количеству прикрепленных к ним группам).
            Route::get('getList', [TeacherController::class, 'getList']); 
            // Необходио вернуть: id преподавателя, ФИО преподавателя.

            Route::prefix('{teacherId}')->group(function () {
            
                // TODO - Получение информации о преподавателе
                Route::get('info', [TeacherController::class, 'info']);
                // Полная информация о преподавателе. Своя (кроме пароля) + аудитория. Добавить обработку параметров (добавление информации если параметр указали): какие группы курирует(curator), в каких преподает(teacher), какие ведет предметы(subjects).

                // TODO - Редактирование преподавателя. Редактирование ФИО.
                Route::put('changeFIO', [TeacherController::class, 'changeFIO']);

                Route::prefix('subjects')->group(function () {
                
                    // TODO - Редактирование преподавателя. Удаление предмета.
                    Route::put('delete', [TeacherController::class, 'deleteSubject']);
                    
                    // TODO - Редактирование преподавателя. Добавление предмета.
                    Route::put('add', [TeacherController::class, 'addSubject']);

                });

            });

        });

        Route::prefix('students')->group(function () {

            // TODO - Создание студента (ФИО, логин (Генерируется из ФИО), пароль (по умолчанию, 123456780)).
            Route::post('create', [StudentController::class, 'create']);

            Route::prefix('{studentId}')->group(function () {

                // TODO - Получение информации о студенте
                Route::get('info', [StudentController::class, 'info']);
                // Полная информация о стденте. Своя (кроме пароля) + группа + куратор (id, ФИО) + специальность (id, название). Добавить обработку параметров (добавление информации если параметр указали): какие предметы ведутся в его группе.

                // TODO - Редактирование студента. Смена ФИО. 
                Route::put('changeFIO', [StudentController::class, 'changeFIO']);

            });

        });

        Route::prefix('admins')->group(function () {

            // TODO - Создание администратор (ФИО, логин (Генерируется из ФИО), пароль (по умолчанию, 123456780), список подролей (привелегий: Суперадмин, Оператор, Менеджер групп, Координатор расписания)).
            Route::post('create', [AdminController::class, 'create']);

            Route::prefix('{adminId}')->group(function () {

                // TODO - Получение информации о администраторе
                Route::get('info', [AdminController::class, 'info']);
                // Полная информация о стденте. Своя (кроме пароля)

            });

        });


        Route::prefix('group')->group(function () {

            // TODO - Проверка на сущестование литеры группы.
            Route::get('checkName', [GroupController::class, 'checkName']);
            // Возвращает 200 код если название доступно и 409 если не дсступен.

            // TODO - Создание группы (Указывается литера (например П-21-57к), Специальность, преподавателея (теперь куратора группы), Цвет группы(rgb))
            Route::post('create', [GroupController::class, 'create']);

            Route::prefix('{groupId}')->group(function () {

                // TODO - Обновление специальности группы на другое
                Route::put('updateSpecialization', [GroupController::class, 'updateSpecialization']);

                // TODO - Обновление куратора группы на другого
                Route::put('updateTeacher', [GroupController::class, 'updateTeacher']);

                // TODO - Получение информации о группе (Литера, Специальность, Куратор)
                Route::get('getInfo', [GroupController::class, 'getInfo']);  
                // Необходио вернуть: id группы, литера группы, специальность(id, название), куратор(id преподавателя, ФИО преподавателя).

                // TODO - Получение списка предметов группы (с их преподавателями).
                Route::get('subjectList', [GroupSubjectController::class, 'getList']); 
                // Необходио вернуть: id группы, предмет(id, название, преподаватель(id, ФИО))

                Route::prefix('schedule')->group(function () {

                    // TODO - Получение расписания группы.
                    Route::get('list', [ScheduleController::class, 'list']);

                    // TODO Изменить предмет в расписании за определенный день.
                    Route::put('change', [ScheduleController::class, 'changeSubject']);

                });

                Route::prefix('subjects')->group(function () {

                    // TODO - Получить все предметы этой группы.
                    Route::get('list', [GroupSubjectController::class, 'list']);

                    // TODO - Добавление предмета в группу (подаем преподавателя и предмет).
                    Route::post('add', [GroupSubjectController::class, 'add']); 

                    // TODO - Удаление предмета из группы.
                    Route::delete('remove/{subjectId}', [GroupSubjectController::class, 'remove']);

                });

                Route::prefix('students')->group(function () {

                    // TODO - Получение всех студентов группы.
                    Route::get('list', [GroupStudentController::class, 'list']);

                    // TODO - Получение всех свободных студентов (которые находятся вне группы).
                    Route::get('freeList', [StudentController::class, 'freeStudentList']);

                    // TODO - Добавление студента в группу.
                    Route::post('add/{studentId}', [GroupStudentController::class, 'add']);

                    // TODO - Открпить студента из группы.
                    Route::post('remove/{studentId}', [GroupStudentController::class, 'remove']);

                    // TODO - Изменение подгруппы студента.
                    Route::post('changeSubgroup/{studentId}', [GroupStudentController::class, 'changeSubgroup']);

                });

            });

        });

        Route::prefix('users')->group(function () {

            // TODO - Получение списка пользователей (по умолчанию сортировка по роли (подроли)) (так же есть фильтр по фио, роли, группе)
            Route::get('list', [UserController::class, 'list']);

            // TODO - Удаление пользователя. Админа с подролью суперадмин удалить нельзя. Будет предусмотрено только через базу.
            Route::delete('{userId}/remove', [UserController::class, 'remove']); 

        });

        Route::prefix('replacements')->group(function () {

            Route::prefix('requests')->group(function () {

                // TODO - Получение запросов на замену.
                Route::get('list', [ReplacementController::class, 'requestsList']);

                // TODO - Получение запросов на замену (текущий месяц по умолчанию или указанный в параметре).
                Route::get('monthList', [ReplacementController::class, 'requestsListMonth']);

                Route::prefix('{replacementId}')->group(function () {

                    // TODO - Отказать в замене.
                    Route::get('reject', [ReplacementController::class, 'rejectReplacement']);

                    // TODO - Одобрить замену.
                    Route::get('confirm', [ReplacementController::class, 'confirmReplacement']);

                });

            });

            // TODO - Получение замен
            Route::get('list', [ReplacementController::class, 'list']);
            
            Route::prefix('{groupId}')->group(function () {

                // TODO - Получение расписания за дату и группу. (Все, А, Б подгруппы) (дата (год, месяц) в параметр передать)
                Route::get('schedule', [GroupController::class, 'getScheduleByDate']);

            });

        });

    });
    
// });




