<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\ST\StudentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Dashboard\DashbordController;
use App\Http\Controllers\StudentClassController;
Route::get('/', function () {
    return view('welcome');
});
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// middleware group check.user
Route::middleware('check.user')->group(function () {

    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    // Route::get('/dashboard', [DashbordController::class, 'index'])->name('dashboard.index');


    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');



    // for show edit distroy student form
    // View student
Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.view');

// Edit student form
Route::get('/students/edit/{id}', [StudentController::class, 'edit'])->name('students.edit');

// Update student (form submission)
Route::put('/students/update/{id}', [StudentController::class, 'update'])->name('students.update');

// Delete student
Route::delete('/students/destroy/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
    // Store new student
   
Route::post('/students/store', [StudentController::class, 'store'])->name('students.store');



// Attendance routes


Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');
Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
Route::get('/attendance/search', [AttendanceController::class, 'search'])->name('attendance.search');

//     Route::get('/attendance', function () {
//         return view('dashboard.attendance');
//     })->name('attendance');


// notification route
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');

});

include_once 'addclass.php';