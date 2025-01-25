<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AssignRoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicQuizController;
use App\Http\Controllers\User\ParticipantController;
use App\Http\Controllers\User\QuestionController;
use App\Http\Controllers\User\QuizController as UserQuizController;
use App\Http\Controllers\Admin\QuizController as AdminQuizController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\QuizPlatformController;
use App\Http\Controllers\User\ResponseController;
use App\Http\Controllers\User\UserDashboardController;
use Illuminate\Support\Facades\Route;

// Default Home Route
Route::get('/', [QuizPlatformController::class, 'index'])->name('frontend.home');
Route::get('/public-quizzes/{quiz}', [QuizPlatformController::class, 'participate'])->name('frontend.public-quizzes.participate');
Route::post('/public-quizzes/{quiz}', [QuizPlatformController::class, 'submit'])->name('frontend.public-quizzes.submit');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/password-change', [ProfileController::class, 'changePassword'])->name('password-change.profile');
    Route::patch('/profile', [ProfileController::class, 'myProfileUpdate'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/cache-clear', [ProfileController::class,'cacheClear'])->name('cache-clear');

    Route::middleware('role:user')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
        Route::resource('quizzes', UserQuizController::class);
        Route::resource('quizzes.questions', QuestionController::class)->shallow();
        Route::resource('quizzes.participants', ParticipantController::class)->only(['index', 'show']);
        Route::post('responses', [ResponseController::class, 'store'])->name('responses.store');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', UserController::class);
        Route::resource('/roles', RoleController::class)->except(['show']);
        Route::resource('/assign-roles', AssignRoleController::class)->only(['index', 'store']);
        Route::resource('quizzes', AdminQuizController::class);
    });
});

require __DIR__.'/auth.php';
