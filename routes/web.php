<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('horario', function () {
    return view('horarios.index');
})->name('horario');
