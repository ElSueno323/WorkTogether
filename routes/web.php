<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\MapsController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VerifyUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/* LARAVEL BREEZE */

/* // We no longer need it.
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
*/

Route::middleware(['auth', 'verified'])->group(function () {
    /* LARAVEL BREEZE */

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* TASK LIST */

    Route::get('/tasks', [TaskController::class, 'getTasks'])->name('tasks.index');
    Route::get('/create', [TaskController::class, 'createTask'])->name('tasks.create');
    Route::post('/tasks/store', [TaskController::class, 'storeTask'])->name('tasks.store');
    Route::post('/tasks', [TaskController::class, 'registerTask'])->name('signInTask');
    Route::post('/tasks/modify', [TaskController::class, 'modifyConfirmTask'])->name('tasks.modify.confirm');
    Route::post('/tasks/register', [TaskController::class, 'registerTask'])->name('tasks.register');
    Route::post('/tasks/unregister', [TaskController::class, 'unregisterTask'])->name('tasks.unregister');
    Route::post('/tasks/sort', [TaskController::class, 'sortTask'])->name('tasks.sort');

    Route::get('/tasks/{id}', [TaskController::class, 'modifyFormTask'])->name('tasks.modify');

    Route::post('/tasks/modify', [TaskController::class, 'modifyConfirmTask'])->name('tasks.modify.confirm');

    Route::post('/tasks/increment-people-count', [TaskController::class, 'increment'])->name('tasks.increment.people.count');

    /* CALENDAR */

    Route::get('/calendar', CalendarController::class)->name('calendar');

    /* ADMIN */

    Route::middleware('admin')->group(function () {
        Route::get('/verify', [VerifyUserController::class, 'getUnverifiedUsers'])->middleware('admin')->name('verify');
        Route::patch('/verify', [VerifyUserController::class, 'verifyUser'])->name('verify.add');
        Route::delete('/verify', [VerifyUserController::class, 'refuseUser'])->name('verify.delete');
        Route::get('/report', [ReportController::class, 'reports'])->name('report');
        Route::get('/report/csv', [ReportController::class, 'exportCSV'])->name('report.csv');
    });


    /* GROUPS */

    Route::get('/groups', [GroupController::class, 'getGroup'])->name('groups.index');


    // TODO: /!\ pour admin
    Route::middleware('admin')->group(function () {

    Route::get('/add_group', function () {
        return view('groups.creation_group');
    })->name('groups.add_group');

    Route::post('/add_group', [GroupController::class, 'add_group'])->name('groups.insert');
    Route::get('/groups/participants', function () {
        return view('groups.show_group_participants');
    })->name('groups.participants');
    
    });


    /* MAPS INTEGRATION */

    Route::get('/maps/{type}/{address}', [MapsController::class, 'redirectToMaps'])->name('maps');

    /* USERS DETAILS */
    Route::get('/user/{id}', [UserController::class, 'getUser'])->name('user.detail')->middleware('admin');
});

Route::get('/calendar/download', [CalendarController::class, 'download'])->name('calendar.download');

Route::get('/', function () {
    return view('home');
})->name('home');

require __DIR__ . '/sso.php';
require __DIR__ . '/auth.php';
