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
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    // ================================
    // REGISTRO
    // ================================
    public function registro(RegistroRequest $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed', 
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
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
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'Credenciales incorrectas.'
            ], 401);
        }

        if (!$user->active) {
            return response()->json([
                'error' => 'Tu cuenta ha sido baneada. Contacta con el administrador.'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'mensaje' => 'Login exitoso',
            'token'   => $token,
            'usuario' => [
                'id'    => $user->id_user, 
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
                'avatar' => $user->avatar,
                'plan'   => $user->plan
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

    // ================================
    // ACTUALIZAR SUSCRIPCIÓN
    // ================================
    public function updateSubscription(Request $request)
    {
        $request->validate([
            'plan' => 'required|string|in:Básico,Pro,Ultra'
        ]);

        $user = $request->user();
        $user->plan = $request->plan;
        $user->save();

        return response()->json([
            'mensaje' => 'Suscripción actualizada correctamente',
            'plan' => $user->plan
        ]);
    }

    // ================================
    // TODOS LOS USUARIOS
    // ================================
    public function index()
    {
        $users = User::all();
        return response()->json($users, 200);
    }

    // ================================
    // PERFIL DE USUARIO
    // ================================
    public function perfilStats(Request $request)
    {
        $user = $request->user()->fresh();;
        
        return response()->json([
            'favoritos' => $user->favorites()->count(),
            'pedidos' => $user->cart()->count(),
            'plan' => $user->plan,
        ]);
    }

    // ================================
    // CAMBIAR ESTADO USUARIO
    // ================================
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin' && User::id() == $user->id) {
            return response()->json(['message' => 'No puedes banearte a ti mismo'], 403);
        }

        $user->active = !$user->active;
        $user->save();

        return response()->json([
            'message' => $user->active ? 'Usuario activado' : 'Usuario baneado',
            'active' => $user->active
        ]);
    }


    // ================================
    // ACTUALIZAR PERFIL
    // ================================
    public function updatePerfil(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'nombre' => 'required|string|max:255',
            'email'  => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user->name = $request->nombre;
        $user->email = $request->email;

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');

            $user->avatar = $path;
        }

        $user->save();
        
        $user->refresh();

        return response()->json([
            'mensaje' => 'Perfil actualizado con éxito',
            'user'    => [
                'id'     => $user->id,
                'name'   => $user->name,
                'role' => $user->role,
                'email'  => $user->email,
                'avatar' => $user->avatar,
                'plan'  => $user->plan
            ]
        ], 200);
    }

    public function deletePerfil(Request $request)
    {
        $user = $request->user();

        if ($user->avatar && $user->avatar !== 'default.png') {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return response()->json(['message' => 'Cuenta eliminada'], 200);
    }
}