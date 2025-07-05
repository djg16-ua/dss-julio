<?php

// Crear: app/Services/EmailService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmailService
{
    private $apiKey = 'xkeysib-tu-api-key-aqui'; // API Key de Brevo (gratuita)
    private $apiUrl = 'https://api.brevo.com/v3/smtp/email';

    public function sendPasswordReset($email, $resetToken)
    {
        $resetUrl = url("/reset-password/{$resetToken}?email=" . urlencode($email));
        
        $emailData = [
            'sender' => [
                'name' => 'TaskFlow',
                'email' => 'noreply@taskflow.local'
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
                'api-key' => $this->apiKey
            ])->post($this->apiUrl, $emailData);

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
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%); color: white; padding: 30px; text-align: center; }
                .content { background: #f8f9fa; padding: 30px; }
                .button { display: inline-block; background: #4f46e5; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; }
                .footer { background: #1f2937; color: white; padding: 20px; text-align: center; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🔐 TaskFlow</h1>
                    <h2>Restablecer Contraseña</h2>
                </div>
                <div class='content'>
                    <p>Hola,</p>
                    <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta en TaskFlow.</p>
                    <p>Haz clic en el siguiente botón para crear una nueva contraseña:</p>
                    <p style='text-align: center; margin: 30px 0;'>
                        <a href='{$resetUrl}' class='button'>Restablecer Contraseña</a>
                    </p>
                    <p>Si no solicitaste este cambio, puedes ignorar este email.</p>
                    <p><strong>Este enlace expirará en 60 minutos.</strong></p>
                    <hr>
                    <p style='font-size: 12px; color: #666;'>
                        Si el botón no funciona, copia y pega este enlace en tu navegador:<br>
                        <a href='{$resetUrl}'>{$resetUrl}</a>
                    </p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " TaskFlow. Gestión de Proyectos.</p>
                </div>
            </div>
        </body>
        </html>";
    }
}