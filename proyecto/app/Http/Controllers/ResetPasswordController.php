<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    /**
     * Mostrar formulario de reset password
     */
    public function create($token, Request $request)
    {
        // Verificar que el token sea válido
        $resetRecord = DB::table('password_resets')
            ->where('token', hash('sha256', $token))
            ->first();

        if (!$resetRecord) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'El enlace de restablecimiento es inválido o ha expirado.']);
        }

        // Verificar que no haya expirado (60 minutos)
        $createdAt = Carbon::parse($resetRecord->created_at);
        if ($createdAt->addMinutes(60)->isPast()) {
            // Eliminar token expirado
            DB::table('password_resets')->where('token', hash('sha256', $token))->delete();
            
            return redirect()->route('password.request')
                ->withErrors(['email' => 'El enlace de restablecimiento ha expirado.']);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email') // Obtener email del query string
        ]);
    }

    /**
     * Procesar el cambio de contraseña
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Verificar que el token sea válido
        $resetRecord = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', hash('sha256', $request->token))
            ->first();

        if (!$resetRecord) {
            return back()->withErrors(['email' => 'El enlace de restablecimiento es inválido.']);
        }

        // Verificar que no haya expirado
        $createdAt = Carbon::parse($resetRecord->created_at);
        if ($createdAt->addMinutes(60)->isPast()) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'El enlace de restablecimiento ha expirado.']);
        }

        // Buscar usuario
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors(['email' => 'No encontramos un usuario con ese email.']);
        }

        // Actualizar contraseña
        $user->password = $request->password; // Se hasheará automáticamente con tu mutador
        $user->save();

        // Eliminar token usado
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', '¡Contraseña restablecida exitosamente! Ya puedes iniciar sesión.');
    }
}