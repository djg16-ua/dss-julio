<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CustomPasswordResetController extends Controller
{
    private $brevoApiKey; // API Key de Brevo, envío de emails

    public function __construct() { 
    $this->brevoApiKey = env('BREVO_API_KEY'); 
    }

    private $brevoApiUrl = 'https://api.brevo.com/v3/smtp/email';

    public function create()
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            // Por seguridad, siempre mostrar mensaje de éxito
            return back()->with('status', '¡Te hemos enviado un enlace para restablecer tu contraseña!');
        }

        // Generar token personalizado
        $token = Str::random(64);
        
        // Guardar token en base de datos (usar la tabla correcta)
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => hash('sha256', $token),
                'created_at' => now()
            ]
        );

        // Enviar email usando Brevo
        $emailSent = $this->sendPasswordResetEmail($request->email, $token);
        
        if ($emailSent) {
            return back()->with('status', '¡Email enviado exitosamente! Revisa tu bandeja de entrada.');
        } else {
            return back()->with('status', '¡Enlace de recuperación generado! Revisa tu email.');
        }
    }

    private function sendPasswordResetEmail($email, $resetToken)
    {
        $resetUrl = url("/reset-password/{$resetToken}?email=" . urlencode($email));
        
        $emailData = [
            'sender' => [
                'name' => 'TaskFlow',
                'email' => 'taskflow.dss@gmail.com' // Brevo requiere un dominio válido
            ],
            'to' => [
                [
                    'email' => $email,
                    'name' => explode('@', $email)[0]
                ]
            ],
            'subject' => 'Restablecer contraseña - TaskFlow',
            'htmlContent' => $this->getResetEmailTemplate($resetUrl, $email)
        ];

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'api-key' => $this->brevoApiKey
            ])->post($this->brevoApiUrl, $emailData);

            if ($response->successful()) {
                Log::info("Email de reset enviado exitosamente a: {$email}");
                return true;
            } else {
                Log::error("Error enviando email: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Excepción enviando email: " . $e->getMessage());
            return false;
        }
    }

    private function getResetEmailTemplate($resetUrl, $email)
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1'>
            <style>
                body { 
                    font-family: 'Inter', Arial, sans-serif; 
                    line-height: 1.6; 
                    color: #374151; 
                    margin: 0; 
                    padding: 0; 
                    background-color: #f8fafc;
                }
                .container { 
                    max-width: 600px; 
                    margin: 0 auto; 
                    background-color: white;
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
                }
                .header { 
                    background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%); 
                    color: white; 
                    padding: 40px 30px; 
                    text-align: center; 
                }
                .header h1 {
                    margin: 0;
                    font-size: 2rem;
                    font-weight: 700;
                }
                .header h2 {
                    margin: 10px 0 0 0;
                    font-size: 1.25rem;
                    font-weight: 500;
                    opacity: 0.9;
                }
                .content { 
                    padding: 40px 30px; 
                }
                .content p {
                    margin-bottom: 20px;
                    font-size: 16px;
                }
                .button { 
                    display: inline-block; 
                    background: #4f46e5; 
                    color: white; 
                    padding: 15px 30px; 
                    text-decoration: none; 
                    border-radius: 8px; 
                    font-weight: 600;
                    font-size: 16px;
                    transition: background-color 0.2s;
                }
                .button:hover {
                    background: #3730a3;
                }
                .footer { 
                    background: #1f2937; 
                    color: white; 
                    padding: 30px; 
                    text-align: center; 
                    font-size: 14px; 
                }
                .footer p {
                    margin: 0;
                    opacity: 0.8;
                }
                .warning {
                    background: #fef3c7;
                    border-left: 4px solid #f59e0b;
                    padding: 15px;
                    margin: 25px 0;
                    border-radius: 4px;
                }
                .link-backup {
                    background: #f8fafc;
                    padding: 20px;
                    border-radius: 6px;
                    margin: 25px 0;
                    font-size: 14px;
                    color: #6b7280;
                    word-break: break-all;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🔐 TaskFlow</h1>
                    <h2>Restablecer Contraseña</h2>
                </div>
                <div class='content'>
                    <p><strong>Hola,</strong></p>
                    <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta en <strong>TaskFlow</strong>.</p>
                    <p>Haz clic en el siguiente botón para crear una nueva contraseña:</p>
                    <p style='text-align: center; margin: 30px 0;'>
                        <a href='{$resetUrl}' class='button'>🔑 Restablecer Contraseña</a>
                    </p>
                    
                    <div class='warning'>
                        <p style='margin: 0;'><strong>⚠️ Importante:</strong> Este enlace expirará en <strong>60 minutos</strong>.</p>
                    </div>
                    
                    <p>Si no solicitaste este cambio, puedes ignorar este email de forma segura.</p>
                    
                    <div class='link-backup'>
                        <p style='margin: 0 0 10px 0;'><strong>Si el botón no funciona, copia y pega este enlace:</strong></p>
                        <a href='{$resetUrl}' style='color: #4f46e5;'>{$resetUrl}</a>
                    </div>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " TaskFlow - Gestión de Proyectos</p>
                    <p>Este email fue enviado automáticamente, por favor no respondas.</p>
                </div>
            </div>
        </body>
        </html>";
    }
}