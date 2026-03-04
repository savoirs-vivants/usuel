<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackOfficeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\PassationController;
use App\Http\Controllers\QuestionnaireResultController;
use App\Models\Passation;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/inscription/{token}', [InscriptionController::class, 'show'])->name('inscription');
Route::post('/inscription/{token}', [InscriptionController::class, 'complete'])->name('inscription.complete');

Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
Route::post('/connexion', [AuthController::class, 'login'])->name('login.submit');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profil', function () {
        return view('profile-page');
    })->name('profile.edit');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/backoffice', [BackOfficeController::class, 'index'])->name('backoffice');
    Route::get('/backoffice/user/{user}/edit', function (User $user) {
        return view('edit-user-page', compact('user'));
    })->name('user.edit');
    Route::delete('/backoffice/users/{user}', [BackOfficeController::class, 'destroy'])->name('backoffice.destroy');

    Route::get('/passations', [PassationController::class, 'index'])->name('passations');

    Route::get('/passations/{passation}/certificat', [PassationController::class, 'certificat'])
        ->name('passation.certificat');

    Route::delete('/passations/{passation}', [PassationController::class, 'destroy'])->name('passation.destroy');


    Route::get('/questionnaire', function () {
        return view('questionnaire');
    })->name('questionnaire.index');

    Route::get('/questionnaire/run', function () {
        return view('questionnaire.run');
    })->name('questionnaire.run');

    Route::get('/questionnaire/result/{id}', [QuestionnaireResultController::class, 'show'])
        ->name('questionnaire.result');

    Route::get('/statistiques/public', function () {
        return view('statistiques');
    })->name('statistiques.index');

    Route::get('/statistiques/comportementale', function () {
        return view('statistiques-comportementale-page');
    })->name('statistiques.comportementale');
});
