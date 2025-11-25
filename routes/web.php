<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Models\Estudiante;
use App\Models\Profesor;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    // ADMIN (Usamos 'rol:Admin')
    Route::prefix('admin')->middleware('rol:Admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('layout.admin');
        })->name('admin.dashboard');
    });

    // ESTUDIANTE (Usamos 'rol:Estudiante')
    Route::prefix('estudiante')->middleware('rol:Estudiante')->group(function () {
        Route::get('/dashboard', function () {
            $estudiante = Estudiante::where('usuario_id', Auth::id())->first();
            if (!$estudiante) return "Error: No tienes perfil de estudiante.";
            return view('estudiante.dashboard', compact('estudiante'));
        })->name('estudiante.dashboard');
    });

    // PROFESOR (Usamos 'rol:Profesor')
    Route::prefix('profesor')->middleware('rol:Profesor')->group(function () {
        Route::get('/dashboard', function () {
            $profesor = Profesor::where('usuario_id', Auth::id())->first();
            if (!$profesor) return "Error: No tienes perfil de profesor.";
            return view('profesor.dashboard', compact('profesor'));
        })->name('profesor.dashboard');
    });

});