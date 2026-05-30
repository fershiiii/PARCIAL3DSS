<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});

// Cambia la ruta vieja del dashboard por esta:
Route::get('/dashboard', function () {
    // Jalamos las tareas que pertenecen únicamente al usuario que tiene la sesión abierta
    $tasks = \App\Models\Task::where('user_id', auth()->id())
                             ->orderBy('created_at', 'desc')
                             ->take(5) // Limitamos a las últimas 5 para que se vea ordenado
                             ->get();

    // Le inyectamos la variable $tasks a la vista del dashboard
    return view('dashboard', compact('tasks'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::resource('tasks', TaskController::class);
});