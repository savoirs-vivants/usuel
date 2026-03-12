<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackOfficeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\PassationController;
use App\Http\Controllers\QuestionnaireResultController;
use App\Http\Controllers\PasswordResetController;
use App\Models\Passation;
use App\Models\User;
use App\Livewire\StatistiquesPublic;
use App\Livewire\StatistiquesComportementale;
use App\Livewire\GestionQuestions;
use App\Livewire\EditUser;
use App\Livewire\QuestionnaireRun;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/inscription/{token}', [InscriptionController::class, 'show'])->name('inscription');
Route::post('/inscription/{token}', [InscriptionController::class, 'complete'])->name('inscription.complete');

Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
Route::post('/connexion', [AuthController::class, 'login'])->name('login.submit');

Route::get('/mot-de-passe-oublie', [PasswordResetController::class, 'showForgot'])->name('password.forgot');
Route::post('/mot-de-passe-oublie', [PasswordResetController::class, 'sendReset'])->name('password.send');
Route::get('/reinitialiser/{token}/{email}', [PasswordResetController::class, 'showReset'])->name('password.reset');
Route::post('/reinitialiser', [PasswordResetController::class, 'reset'])->name('password.update');

Route::get('/mentions-legales', function () {
    return view('mentions');
})->name('mentions');

Route::get('/politique-confidentialite', function () {
    return view('confidentialite');
})->name('confidentialite');


Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profil', function () {
        return view('profile-page');
    })->name('profile.edit');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/backoffice', [BackOfficeController::class, 'index'])->name('backoffice');
    Route::get('/backoffice/user/{user}/edit', EditUser::class)->name('user.edit');
    Route::delete('backoffice/bulk', [BackofficeController::class, 'destroyMultiple'])->name('backoffice.destroyMultiple');
    Route::delete('/backoffice/users/{user}', [BackOfficeController::class, 'destroy'])->name('backoffice.destroy');

    Route::get('/passations', [PassationController::class, 'index'])->name('passations');

    Route::get('/passations/{passation}/certificat', [PassationController::class, 'certificat'])
        ->name('passation.certificat');

    Route::delete('passations/bulk', [PassationController::class, 'destroyMultiple'])->name('passation.destroyMultiple');
    Route::delete('/passations/{passation}', [PassationController::class, 'destroy'])->name('passation.destroy');

    Route::get('/questionnaire', function () {
        return view('questionnaire');
    })->name('questionnaire.index');

    Route::get('/questions/gestion', GestionQuestions::class)
    ->name('questions.gestion')
    ->can('viewAny', App\Models\Question::class);

    Route::get('/questionnaire/run', QuestionnaireRun::class)->name('questionnaire.run');

    Route::get('/questionnaire/result/{id}', [QuestionnaireResultController::class, 'show'])
        ->name('questionnaire.result');

    Route::get('/statistiques/public', StatistiquesPublic::class)->name('statistiques.index');

    Route::get('/statistiques/comportementale', StatistiquesComportementale::class)->name('statistiques.comportementale');
});
