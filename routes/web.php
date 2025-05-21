<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Definición de rutas públicas y protegidas por autenticación.
|
*/

// Página de bienvenida pública
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),  // Indica si existen rutas de login/registro
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Dashboard protegido (solo usuarios autenticados y verificados)
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas protegidas por middleware auth para perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas protegidas por middleware auth para el CRUD de tareas
Route::middleware('auth')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index'); // Listar tareas
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store'); // Crear tarea
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update'); // Actualizar tarea
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy'); // Eliminar tarea
});

require __DIR__.'/auth.php';  // Rutas de autenticación estándar
