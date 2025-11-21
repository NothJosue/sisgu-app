<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/home', function () {
    return view('layout.admin');
});

Route::get('/', [LoginController::class, 'index']);