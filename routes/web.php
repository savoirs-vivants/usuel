<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackOfficeController;
use App\Http\Controllers\InscriptionController;
use App\Models\Passation;

Route::get('/', function () { return view('welcome'); })->name('welcome');

Route::get('/inscription/{token}', [InscriptionController::class, 'show'])->name('inscription');
Route::post('/inscription/{token}', [InscriptionController::class, 'complete'])->name('inscription.complete');

Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
Route::post('/connexion', [AuthController::class, 'login'])->name('login.submit');

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/backoffice', [BackOfficeController::class, 'index'])->name('backoffice');

    Route::get('/questionnaire', function () {
        return view('questionnaire');
    })->name('questionnaire.index');

    Route::get('/questionnaire/run', function () {
        return view('questionnaire.run');
    })->name('questionnaire.run');

    Route::get('/questionnaire/result/{id}', function ($id) {
        $passation = Passation::with('beneficiaire')->findOrFail($id);
        return view('questionnaire.result', compact('passation'));
    })->name('questionnaire.result');

});
