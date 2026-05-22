<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Redirect root to dashboard
Route::get('/', fn () => redirect()->route('dashboard'));

// ── Authenticated Routes ──────────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Students (CRUD)
    Route::resource('students', StudentController::class)->except(['show']);


    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Classes
    Route::get('/classes', 'App\Http\Controllers\SchoolClassController@index')->name('classes.index');
    Route::post('/classes', 'App\Http\Controllers\SchoolClassController@store')->name('classes.store');
    Route::get('/classes/{class}', 'App\Http\Controllers\SchoolClassController@show')->name('classes.show');
    Route::put('/classes/{class}', 'App\Http\Controllers\SchoolClassController@update')->name('classes.update');
    Route::delete('/classes/{class}', 'App\Http\Controllers\SchoolClassController@destroy')->name('classes.destroy');
    Route::post('/classes/{class}/enroll', 'App\Http\Controllers\SchoolClassController@enroll')->name('classes.enroll');
    Route::post('/classes/{class}/unenroll/{student}', 'App\Http\Controllers\SchoolClassController@unenroll')->name('classes.unenroll');
    Route::post('/classes/{class}/bulk-unenroll', 'App\Http\Controllers\SchoolClassController@bulkUnenroll')->name('classes.bulkUnenroll');
    Route::post('/classes/{class}/attendance', 'App\Http\Controllers\SchoolClassController@saveAttendance')->name('classes.attendance.store');

    // Profile (from Breeze)
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
