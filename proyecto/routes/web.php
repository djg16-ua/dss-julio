<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CustomPasswordResetController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\DashboardController;

// ============================================
// RUTAS PRINCIPALES
// ============================================

// Para usuarios autenticados - redirigir a dashboard (PRIMERO)
Route::get('/', function () {
    return redirect()->route('dashboard');
})->middleware('auth');

// Para usuarios NO autenticados - mostrar welcome (SEGUNDO)
Route::get('/', function () {
    return view('welcome');
})->middleware('guest')->name('welcome');

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});

// ============================================
// DASHBOARD
// ============================================
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// ============================================
// RUTAS DE AUTENTICACIÓN COMPLETAS
// ============================================

// Login
Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'email' => 'Las credenciales no coinciden con nuestros registros.',
    ])->onlyInput('email');
})->middleware('guest')->name('login.store');

// Register
Route::get('/register', function () {
    return view('auth.register');
})->middleware('guest')->name('register');

Route::post('/register', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->password, // Se hasheará automáticamente con tu mutador
        'role' => 'USER',
    ]);

    Auth::login($user);

    return redirect('/dashboard');
})->middleware('guest')->name('register.store');

// Logout
Route::post('/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');

// ============================================
// RUTAS PERSONALIZADAS PARA PASSWORD RESET
// ============================================
Route::get('/forgot-password', [CustomPasswordResetController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [CustomPasswordResetController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [ResetPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');

// ============================================
// RUTAS DEL PERFIL
// ============================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::put('/password', [\App\Http\Controllers\Auth\PasswordController::class, 'update'])
        ->name('password.update');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

// ============================================
// RUTAS DE ADMINISTRACIÓN
// ============================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard y páginas principales
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users');
    Route::get('/projects', [App\Http\Controllers\AdminController::class, 'projects'])->name('projects');
    Route::get('/teams', [App\Http\Controllers\AdminController::class, 'teams'])->name('teams');
    
    // ============================================
    // GESTIÓN DE USUARIOS
    // ============================================
    
    // Editar usuario (mostrar formulario)
    Route::get('/users/{user}/edit', [App\Http\Controllers\AdminController::class, 'editUser'])->name('users.edit');
    
    // Actualizar información básica del usuario
    Route::patch('/users/{user}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('users.update');
    
    // Cambiar rol del usuario
    Route::patch('/users/{user}/role', [App\Http\Controllers\AdminController::class, 'updateUserRole'])->name('users.update-role');
    
    // Actualizar contraseña del usuario
    Route::patch('/users/{user}/password', [App\Http\Controllers\AdminController::class, 'updateUserPassword'])->name('users.update-password');
    
    // Resetear contraseña del usuario (generar temporal)
    Route::patch('/users/{user}/reset-password', [App\Http\Controllers\AdminController::class, 'resetUserPassword'])->name('users.reset-password');
    
    // Eliminar usuario
    Route::delete('/users/{user}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.delete');
    
    // Gestión de equipos del usuario (si las tienes implementadas)
    Route::patch('/users/{user}/teams/{team}/role', [App\Http\Controllers\AdminController::class, 'updateUserTeamRole'])->name('users.update-team-role');
    Route::delete('/users/{user}/teams/{team}', [App\Http\Controllers\AdminController::class, 'removeUserFromTeam'])->name('users.remove-from-team');
    
    // ============================================
    // GESTIÓN DE PROYECTOS
    // ============================================
    
    // Actualizar estado del proyecto
    Route::patch('/projects/{project}/status', [App\Http\Controllers\AdminController::class, 'updateProjectStatus'])->name('projects.update-status');
    
    // Eliminar proyecto
    Route::delete('/projects/{project}', [App\Http\Controllers\AdminController::class, 'deleteProject'])->name('projects.delete');

    // Eliminar equipo
    Route::delete('/teams/{team}', [App\Http\Controllers\AdminController::class, 'deleteTeam'])->name('teams.delete');

    // Estadísticas del sistema
    Route::get('/statistics', [App\Http\Controllers\AdminController::class, 'statistics'])->name('statistics');
});

