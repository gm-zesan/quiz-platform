<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AssignRoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\QuizController as AdminQuizController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\QuizPlatformController;
use Illuminate\Support\Facades\Route;


Route::get('/', [QuizPlatformController::class, 'index'])->name('frontend.home');
Route::get('/participant/{quiz}', [QuizPlatformController::class, 'participate'])->name('frontend.quizzes.participate');
Route::post('/participant/{quiz}/submit', [QuizPlatformController::class, 'storePatricipant'])->name('frontend.participant.submit');
Route::post('/quizzes/{quiz}', [QuizPlatformController::class, 'storeQuiz'])->name('frontend.quizzes.submit');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/password-change', [ProfileController::class, 'changePassword'])->name('password-change.profile');
    Route::patch('/profile', [ProfileController::class, 'myProfileUpdate'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/cache-clear', [ProfileController::class,'cacheClear'])->name('cache-clear');


    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    // Route::get('/quizzes/{id}/share', [AdminQuizController::class, 'showSharedQuiz'])->name('quizzes.share');
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('/roles', RoleController::class)->except(['show']);
        Route::resource('/assign-roles', AssignRoleController::class)->only(['index', 'store']);
        Route::resource('quizzes', AdminQuizController::class);
        Route::get('/quizzes/{quiz}/participants', [AdminQuizController::class, 'showParticipants'])->name('quizzes.participants');
    });

});

require __DIR__.'/auth.php';
