<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CustomPasswordResetController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\DashboardController;

// ============================================
// RUTAS PRINCIPALES
// ============================================

// Página de inicio - disponible para todos (autenticados y no autenticados)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

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
// RUTAS DE PROYECTOS - CRUD COMPLETO
// ============================================
Route::middleware('auth')->group(function () {
    Route::resource('project', ProjectController::class);
    Route::get('/project-search-users', [ProjectController::class, 'searchUsers'])->name('project.search-users');
    
    // Rutas adicionales para gestión de miembros del proyecto
    Route::post('/project/{project}/members', [ProjectController::class, 'addMember'])->name('project.add-member');
    Route::delete('/project/{project}/members/{user}', [ProjectController::class, 'removeMember'])->name('project.remove-member');
    Route::get('/project/{project}/tasks/filter', [ProjectController::class, 'getFilteredTasks'])->name('project.filter-tasks');
});

// ============================================
// RUTAS DE EQUIPOS - DENTRO DE PROYECTOS
// ============================================
Route::middleware('auth')->group(function () {
    // Rutas anidadas para equipos dentro de proyectos
    Route::get('/project/{project}/team', [TeamController::class, 'index'])->name('team.index');
    Route::get('/project/{project}/team/create', [TeamController::class, 'create'])->name('team.create');
    Route::post('/project/{project}/team', [TeamController::class, 'store'])->name('team.store');
    Route::get('/project/{project}/team/{team}', [TeamController::class, 'show'])->name('team.show');
    Route::get('/project/{project}/team/{team}/edit', [TeamController::class, 'edit'])->name('team.edit');
    Route::put('/project/{project}/team/{team}', [TeamController::class, 'update'])->name('team.update');
    Route::delete('/project/{project}/team/{team}', [TeamController::class, 'destroy'])->name('team.destroy');
    
    // Rutas para gestión de miembros de equipos específicos
    Route::post('/project/{project}/team/{team}/members', [TeamController::class, 'addMember'])->name('team.add-member');
    Route::delete('/project/{project}/team/{team}/members/{user}', [TeamController::class, 'removeMember'])->name('team.remove-member');
    Route::patch('/project/{project}/team/{team}/members/{user}/role', [TeamController::class, 'updateMemberRole'])->name('team.update-member-role');
    
    // Rutas API para AJAX
    Route::get('/project/{project}/team/{team}/available-members', [TeamController::class, 'getAvailableMembers'])->name('team.available-members');

    // Gestión de módulos en equipos
    Route::post('/project/{project}/team/{team}/modules', [TeamController::class, 'assignModule'])->name('team.assign-module');
    Route::delete('/project/{project}/team/{team}/modules/{module}', [TeamController::class, 'removeModule'])->name('team.remove-module');
    Route::patch('/project/{project}/team/{team}/modules/{module}/status', [TeamController::class, 'updateModuleStatus'])->name('team.update-module-status');
});

// ============================================
// RUTAS DE MÓDULOS - DENTRO DE PROYECTOS
// ============================================
Route::middleware('auth')->group(function () {
    // Rutas anidadas para módulos dentro de proyectos
    Route::get('/project/{project}/modules', [ModuleController::class, 'index'])->name('module.index');
    Route::get('/project/{project}/modules/create', [ModuleController::class, 'create'])->name('module.create');
    Route::post('/project/{project}/modules', [ModuleController::class, 'store'])->name('module.store');
    Route::get('/project/{project}/modules/{module}', [ModuleController::class, 'show'])->name('module.show');
    Route::get('/project/{project}/modules/{module}/edit', [ModuleController::class, 'edit'])->name('module.edit');
    Route::put('/project/{project}/modules/{module}', [ModuleController::class, 'update'])->name('module.update');
    Route::delete('/project/{project}/modules/{module}', [ModuleController::class, 'destroy'])->name('module.destroy');
    
    // Rutas para gestión de equipos asignados a módulos
    Route::post('/project/{project}/modules/{module}/teams', [ModuleController::class, 'assignTeam'])->name('module.assign-team');
    Route::delete('/project/{project}/modules/{module}/teams/{team}', [ModuleController::class, 'removeTeam'])->name('module.remove-team');
    Route::get('/project/{project}/modules/{module}/available-teams', [ModuleController::class, 'getAvailableTeams'])->name('module.available-teams');
    
    // Rutas para gestión de tareas en módulos
    Route::post('/project/{project}/modules/{module}/tasks', [ModuleController::class, 'createTask'])->name('module.create-task');
    Route::delete('/project/{project}/modules/{module}/tasks/{task}', [ModuleController::class, 'removeTask'])->name('module.remove-task');
    Route::patch('/project/{project}/modules/{module}/tasks/{task}/status', [ModuleController::class, 'updateTaskStatus'])->name('module.update-task-status');
    Route::patch('/project/{project}/modules/{module}/tasks/{task}/priority', [ModuleController::class, 'updateTaskPriority'])->name('module.update-task-priority');
    Route::patch('/project/{project}/modules/{module}/tasks/{task}/assign', [ModuleController::class, 'assignTaskToUser'])->name('module.assign-task-user');
    Route::get('/project/{project}/modules/{module}/team-members', [ModuleController::class, 'getModuleTeamMembers'])->name('module.team-members');
});

// ============================================
// RUTAS DE TAREAS - DENTRO DE MÓDULOS/PROYECTOS
// ============================================
Route::middleware('auth')->group(function () {
    // Rutas anidadas para tareas dentro de proyectos
    Route::get('/project/{project}/tasks', [TaskController::class, 'index'])->name('task.index');
    Route::get('/project/{project}/tasks/create', [TaskController::class, 'create'])->name('task.create');
    Route::post('/project/{project}/tasks', [TaskController::class, 'store'])->name('task.store');
    Route::get('/project/{project}/tasks/{task}', [TaskController::class, 'show'])->name('task.show');
    Route::get('/project/{project}/tasks/{task}/edit', [TaskController::class, 'edit'])->name('task.edit');
    Route::put('/project/{project}/tasks/{task}', [TaskController::class, 'update'])->name('task.update');
    Route::delete('/project/{project}/tasks/{task}', [TaskController::class, 'destroy'])->name('task.destroy');
    
    // Rutas para gestión de usuarios asignados a tareas
    Route::post('/project/{project}/tasks/{task}/users', [TaskController::class, 'assignUser'])->name('task.assign-user');
    Route::delete('/project/{project}/tasks/{task}/users/{user}', [TaskController::class, 'removeUser'])->name('task.remove-user');
    
    // Rutas para comentarios en tareas
    Route::post('/project/{project}/tasks/{task}/comments', [TaskController::class, 'addComment'])->name('task.add-comment');
    Route::delete('/project/{project}/tasks/{task}/comments/{comment}', [TaskController::class, 'deleteComment'])->name('task.delete-comment');
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

    // ============================================
    // GESTIÓN DE EQUIPOS (agregar después de la línea existente de teams)
    // ============================================

    // Mostrar formulario de edición del equipo
    Route::get('/teams/{team}/edit', [App\Http\Controllers\AdminController::class, 'editTeam'])->name('teams.edit');

    // Actualizar información básica del equipo
    Route::patch('/teams/{team}', [App\Http\Controllers\AdminController::class, 'updateTeam'])->name('teams.update');

    // Gestión de miembros del equipo
    Route::post('/teams/{team}/members', [App\Http\Controllers\AdminController::class, 'addTeamMember'])->name('teams.add-member');
    Route::patch('/teams/{team}/members/{user}/role', [App\Http\Controllers\AdminController::class, 'updateTeamMemberRole'])->name('teams.update-member-role');
    Route::delete('/teams/{team}/members/{user}', [App\Http\Controllers\AdminController::class, 'removeTeamMember'])->name('teams.remove-member');

    // Gestión de proyectos del equipo
    Route::post('/teams/{team}/projects', [App\Http\Controllers\AdminController::class, 'assignTeamProject'])->name('teams.assign-project');
    Route::delete('/teams/{team}/projects/{project}', [App\Http\Controllers\AdminController::class, 'unassignTeamProject'])->name('teams.unassign-project');

    // ============================================
    // GESTIÓN DE PROYECTOS (agregar después de las rutas existentes)
    // ============================================

    // Mostrar formulario de edición del proyecto
    Route::get('/projects/{project}/edit', [App\Http\Controllers\AdminController::class, 'editProject'])->name('projects.edit');

    // Actualizar información básica del proyecto
    Route::patch('/projects/{project}', [App\Http\Controllers\AdminController::class, 'updateProject'])->name('projects.update');

    // Gestión de equipos del proyecto
    Route::post('/projects/{project}/teams', [App\Http\Controllers\AdminController::class, 'assignProjectTeam'])->name('projects.assign-team');
    Route::delete('/projects/{project}/teams/{team}', [App\Http\Controllers\AdminController::class, 'unassignProjectTeam'])->name('projects.unassign-team');

    // Gestión de módulos del proyecto
    Route::post('/projects/{project}/modules', [App\Http\Controllers\AdminController::class, 'createProjectModule'])->name('projects.create-module');
    Route::patch('/projects/{project}/modules/{module}', [App\Http\Controllers\AdminController::class, 'updateProjectModule'])->name('projects.update-module');
    Route::delete('/projects/{project}/modules/{module}', [App\Http\Controllers\AdminController::class, 'deleteProjectModule'])->name('projects.delete-module');

    // Crear equipo (agregar después de las rutas de teams existentes)
    Route::get('/teams/create', [App\Http\Controllers\AdminController::class, 'createTeam'])->name('teams.create');
    Route::post('/teams', [App\Http\Controllers\AdminController::class, 'storeTeam'])->name('teams.store');

    // Crear proyecto (agregar después de las rutas existentes de projects)
    Route::get('/projects/create', [App\Http\Controllers\AdminController::class, 'createProject'])->name('projects.create');
    Route::post('/projects', [App\Http\Controllers\AdminController::class, 'storeProject'])->name('projects.store');

    // Crear usuario (agregar después de las rutas existentes de users)
    Route::get('/users/create', [App\Http\Controllers\AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [App\Http\Controllers\AdminController::class, 'storeUser'])->name('users.store');

    // ============================================
    // GESTIÓN DE MÓDULOS (agregar después de las rutas de teams existentes)
    // ============================================

    // Página principal de módulos
    Route::get('/modules', [App\Http\Controllers\AdminController::class, 'modules'])->name('modules');

    // Crear módulo
    Route::get('/modules/create', [App\Http\Controllers\AdminController::class, 'createModule'])->name('modules.create');
    Route::post('/modules', [App\Http\Controllers\AdminController::class, 'storeModule'])->name('modules.store');

    // Editar módulo
    Route::get('/modules/{module}/edit', [App\Http\Controllers\AdminController::class, 'editModule'])->name('modules.edit');
    Route::patch('/modules/{module}', [App\Http\Controllers\AdminController::class, 'updateModule'])->name('modules.update');

    // Actualizar estado del módulo
    Route::patch('/modules/{module}/status', [App\Http\Controllers\AdminController::class, 'updateModuleStatus'])->name('modules.update-status');

    // Eliminar módulo
    Route::delete('/modules/{module}', [App\Http\Controllers\AdminController::class, 'deleteModule'])->name('modules.delete');

    // Gestión de equipos del módulo
    Route::post('/modules/{module}/teams', [App\Http\Controllers\AdminController::class, 'assignModuleTeam'])->name('modules.assign-team');
    Route::delete('/modules/{module}/teams/{team}', [App\Http\Controllers\AdminController::class, 'unassignModuleTeam'])->name('modules.unassign-team');

    // API endpoint para obtener módulos de un proyecto (para dependencias)
    Route::get('/projects/{project}/modules', [App\Http\Controllers\AdminController::class, 'getProjectModules'])->name('projects.modules');

    // Gestión de tareas del módulo (agregar después de las rutas de modules existentes)
    Route::post('/modules/{module}/tasks', [App\Http\Controllers\AdminController::class, 'assignModuleTask'])->name('modules.assign-task');
    Route::delete('/modules/{module}/tasks/{task}', [App\Http\Controllers\AdminController::class, 'unassignModuleTask'])->name('modules.unassign-task');

    // Gestión de módulos del proyecto (agregar después de las rutas de projects existentes)
    Route::post('/projects/{project}/modules', [App\Http\Controllers\AdminController::class, 'assignProjectModule'])->name('projects.assign-module');
    Route::delete('/projects/{project}/modules/{module}', [App\Http\Controllers\AdminController::class, 'unassignProjectModule'])->name('projects.unassign-module');

    // ============================================
    // GESTIÓN DE TAREAS (agregar después de las rutas de modules existentes)
    // ============================================

    // Página principal de tareas
    Route::get('/tasks', [App\Http\Controllers\AdminController::class, 'tasks'])->name('tasks');

    // Crear tarea
    Route::get('/tasks/create', [App\Http\Controllers\AdminController::class, 'createTask'])->name('tasks.create');
    Route::post('/tasks', [App\Http\Controllers\AdminController::class, 'storeTask'])->name('tasks.store');

    // Editar tarea
    Route::get('/tasks/{task}/edit', [App\Http\Controllers\AdminController::class, 'editTask'])->name('tasks.edit');
    Route::patch('/tasks/{task}', [App\Http\Controllers\AdminController::class, 'updateTask'])->name('tasks.update');

    // Actualizar estado de la tarea
    Route::patch('/tasks/{task}/status', [App\Http\Controllers\AdminController::class, 'updateTaskStatus'])->name('tasks.update-status');

    // Eliminar tarea
    Route::delete('/tasks/{task}', [App\Http\Controllers\AdminController::class, 'deleteTask'])->name('tasks.delete');

    // Gestión de comentarios de la tarea
    Route::post('/tasks/{task}/comments', [App\Http\Controllers\AdminController::class, 'addTaskComment'])->name('tasks.add-comment');
    Route::delete('/tasks/{task}/comments/{comment}', [App\Http\Controllers\AdminController::class, 'deleteTaskComment'])->name('tasks.delete-comment');

    // Gestión de módulos del equipo (agregar después de las rutas de teams existentes)
    Route::post('/teams/{team}/modules', [App\Http\Controllers\AdminController::class, 'assignTeamModule'])->name('teams.assign-module');
    Route::delete('/teams/{team}/modules/{module}', [App\Http\Controllers\AdminController::class, 'unassignTeamModule'])->name('teams.unassign-module');
});