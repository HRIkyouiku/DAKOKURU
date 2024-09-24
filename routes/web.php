<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\WorkPlaceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TimestampController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'],function(){
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::controller(ProfileController::class)->prefix('profile')->group(function () {
        Route::get('', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::controller(DepartmentController::class)->prefix('department')->group(function () {
        Route::get('/index', 'index')->name('department.index');
        Route::get('/create', 'create')->name('department.create');
        Route::post('/store', 'store')->name('department.store');
        Route::get('/show/{department}', 'show')->name('department.show');
        Route::get('/edit/{department}', 'edit')->name('department.edit');
        Route::patch('/update/{department}', 'update')->name('department.update');
        Route::delete('/destroy/{department}', 'destroy')->name('department.destroy');
    });

    Route::controller(UserController::class)->prefix('user')->group(function () {
        Route::get('/index', 'index')->name('user.index');
        Route::get('/create', 'create')->name('user.create');
        Route::post('/store', 'store')->name('user.store');
        Route::get('/show/{department}', 'show')->name('user.show');
        Route::get('/edit/{department}', 'edit')->name('user.edit');
        Route::patch('/update/{department}', 'update')->name('user.update');
        Route::delete('/destroy/{department}', 'destroy')->name('user.destroy');
    });

    Route::controller(UserController::class)->prefix('work_places')->group(function () {
        Route::get('/index', [WorkPlaceController::class, 'index'])->name('work_places.index');
        Route::get('/create', [WorkPlaceController::class, 'create'])->name('work_places.create');
        Route::post('/store', [WorkPlaceController::class, 'store'])->name('work_places.store');
        Route::get('/edit/{id}', [WorkPlaceController::class, 'edit'])->name('work_places.edit');
        Route::patch('/update', [WorkPlaceController::class, 'update'])->name('work_places.update');
        Route::delete('/destroy', [WorkPlaceController::class, 'destroy'])->name('work_places.destroy');
    });

    Route::controller(GroupController::class)->prefix('group')->group(function () {
        Route::get('/index', 'index')->name('group.index');
        Route::get('/create', 'create')->name('group.create');
        Route::post('/store', 'store')->name('group.store');
        Route::get('/show/{group}', 'show')->name('group.show');
        Route::get('/edit/{group}', 'edit')->name('group.edit');
        Route::patch('/update/{group}', 'update')->name('group.update');
        Route::delete('/destroy/{group}', 'destroy')->name('group.destroy');
    });

    Route::controller(TimestampController::class)->prefix('timestamp')->group(function () {
        Route::get('/create', 'create')->name('timestamp.create');
        Route::post('/store', 'store')->name('timestamp.store');
        Route::get('/users_daily_timestamps/{period}', 'getDailyTimestampByUsers')->name('timestamp.users_daily_timestamps');
        Route::get('/users_daily_attendances/{period}', 'getDailyAttendanceTotallingByUsers')->name('timestamp.users_daily_attendance_totalling');
    });
});
require __DIR__.'/auth.php';
