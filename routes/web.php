<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HostelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventTypeController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\FinanceRecordController;
use App\Http\Controllers\FinanceCategoryController;

Auth::routes();

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('profile', ProfileController::class);
    Route::get('finance-categories/by-type', [FinanceCategoryController::class, 'getCategoriesByType'])->name('finance-categories.by-type');
    Route::resource('finance-categories', FinanceCategoryController::class);
    Route::resource('finance-records', FinanceRecordController::class);

});

Route::controller(SocialiteController::class)->group(function () {
    Route::get('auth/{driver}', 'redirectToSocialiteDriver')->name('auth.login-page');
    Route::get('auth/{driver}/callback', 'handleSocialiteDriverCallback');
});
