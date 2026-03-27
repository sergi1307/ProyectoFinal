<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\VerificationCodeMail;
use App\Http\Requests\RegistroRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\VerificarEmailRequest;
use App\Http\Requests\ReenviarCodigoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // ================================
    // REGISTRO
    // ================================
    public function registro(RegistroRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $this->enviarCodigoVerificacion($user);

        return response()->json([
            'mensaje' => 'Usuario registrado con éxito.'
        ], 201);
    }

    // ================================
    // LOGIN
    // ================================
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'Credenciales incorrectas.'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'mensaje' => 'Login exitoso',
            'token' => $token,
            'usuario' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
        ], 200);
    }

    // ================================
    // LOGOUT
    // ================================
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'mensaje' => 'Sesión cerrada con éxito.'
        ], 200);
    }

    // ================================
    // VERIFICAR EMAIL
    // ================================
    public function verificarEmail(VerificarEmailRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user->email_verified) {
            return response()->json([
                'mensaje' => 'El email ya estaba verificado.'
            ], 200);
        }

        if ($user->verification_code !== $request->code) {
            return response()->json([
                'error' => 'Código incorrecto.'
            ], 422);
        }

        if (now()->isAfter($user->verification_code_expires_at)) {
            return response()->json([
                'error' => 'El código ha expirado.'
            ], 422);
        }

        $user->update([
            'email_verified' => true,
            'verification_code' => null,
            'verification_code_expires_at' => null,
        ]);

        return response()->json([
            'mensaje' => 'Email verificado con éxito.'
        ], 200);
    }

    // ================================
    // REENVIAR CÓDIGO
    // ================================
    public function reenviarCodigo(ReenviarCodigoRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user->email_verified) {
            return response()->json([
                'mensaje' => 'El email ya estaba verificado.'
            ], 200);
        }

        $this->enviarCodigoVerificacion($user);

        return response()->json([
            'mensaje' => 'Código reenviado con éxito.'
        ], 200);
    }

    // ================================
    // GENERAR Y ENVIAR CÓDIGO
    // ================================
    public function enviarCodigoVerificacion(User $user): void
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $user->update([
            'verification_code' => $code,
            'verification_code_expires_at' => now()->addMinutes(15),
        ]);

        Mail::to($user->email)->send(new VerificationCodeMail($code));
    }
}