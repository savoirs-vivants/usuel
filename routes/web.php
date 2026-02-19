<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/inscription', function () { 
    return view('auth.register');
})->name('register');

Route::get('/connexion', function () {
    return view('auth.login');
})->name('login');
