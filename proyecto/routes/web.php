<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Página sobre nosotros
Route::get('/about', function () {
    return view('about');
})->name('about');

// Página de contacto (mostrar formulario)
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/contact', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'subject' => 'required|in:general,support,sales,partnership,feedback',
        'phone' => 'nullable|string|max:20',
        'company' => 'nullable|string|max:255',
        'message' => 'required|string|min:10|max:2000',
        'privacy' => 'required|accepted'
    ], [
        'name.required' => 'El nombre es obligatorio.',
        'email.required' => 'El email es obligatorio.',
        'email.email' => 'Debe ser un email válido.',
        'subject.required' => 'Debe seleccionar un asunto.',
        'message.required' => 'El mensaje es obligatorio.',
        'message.min' => 'El mensaje debe tener al menos 10 caracteres.',
        'privacy.required' => 'Debe aceptar la política de privacidad.'
    ]);

    // TODO: Enviar email o guardar en BD
    
    return redirect()->route('contact')->with('success', 
        '¡Gracias por contactarnos! Hemos recibido tu mensaje y te responderemos pronto.'
    );
})->name('contact.store');

require __DIR__.'/auth.php';
