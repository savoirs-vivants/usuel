<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\BackOfficeController;
use App\Http\Controllers\InscriptionController;

Route::get('/', function () { return view('welcome'); })->name('welcome');

Route::get('/inscription/{token}', [InscriptionController::class, 'show'])->name('inscription');
Route::post('/inscription/{token}', [InscriptionController::class, 'complete'])->name('inscription.complete');

Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
Route::post('/connexion', [AuthController::class, 'login'])->name('login.submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/admin/backoffice', [BackOfficeController::class, 'index'])
    ->name('admin.backoffice')
    ->middleware('auth');

Route::get('/questionnaire', function () { return view('questionnaire'); })
    ->name('questionnaire.index');

Route::get('/questionnaire/run', function () {
    return view('questionnaire.run');
})->name('questionnaire.run');
