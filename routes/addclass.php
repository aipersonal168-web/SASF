<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentClassController; // <-- Add semicolon here


// Create class student
Route::get('/class/searchData', [StudentClassController::class, 'createClassStudent'])->name('class.searchData');
Route::post('/class/storeData', [StudentClassController::class, 'store'])->name('class.storeData');

//fkijhi