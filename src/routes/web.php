<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VisitorController;

// Redirigir la raíz al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Ruta para mostrar el formulario de registro (GET)
Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware(['guest'])
    ->name('register');

// Ruta para procesar el registro (POST)
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware(['guest']);

// Rutas protegidas
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
}); // <-- Asegúrate de que esta línea tenga el }); al final

// Ruta exclusiva para visitantes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [VisitorController::class, 'index'])->name('dashboard');
});

//Admin
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // ── Admin - Usuarios ──
    Route::get('/usuarios',           [AdminController::class, 'usuarios'])->name('admin.usuarios');
    Route::post('/usuarios',          [AdminController::class, 'usuariosStore'])->name('admin.usuarios.store');
    Route::delete('/usuarios/{id}',   [AdminController::class, 'usuariosDestroy']);

    // ── Admin - Exposiciones y Horarios ──
    Route::get('/exposiciones',              [AdminController::class, 'exposiciones'])->name('admin.exposiciones');
    Route::post('/exposiciones',             [AdminController::class, 'exposicionesStore'])->name('admin.exposiciones.store');
    Route::put('/exposiciones/{id}',         [AdminController::class, 'exposicionesUpdate']);
    Route::delete('/exposiciones/{id}',      [AdminController::class, 'exposicionesDestroy']);

    Route::post('/horarios',           [AdminController::class, 'horariosStore'])->name('admin.horarios.store');
    Route::put('/horarios/{id}',       [AdminController::class, 'horariosUpdate']);
    Route::delete('/horarios/{id}',    [AdminController::class, 'horariosDestroy']);

    // ── Admin - Ventas y Reservas ──
    Route::get('/ventas',                        [AdminController::class, 'ventas'])->name('admin.ventas');
    Route::patch('/reservas/{codigo}/cancelar',  [AdminController::class, 'reservasCancelar']);
    Route::get('/ventas/exportar',               [AdminController::class, 'ventasExportar'])->name('admin.ventas.exportar');
});