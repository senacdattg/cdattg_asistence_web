<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('user.login');
    }

    public function iniciarSesion(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);

            if (Auth::attempt($credentials)) {
                return redirect()->route('home.index')->with('success', '¡Sesión Iniciada!');
            } else {
                return back()->withInput()->withErrors(['error' => 'Correo o contraseña inválidos']);
            }
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Error al iniciar sesión: ' . $e->getMessage()]);
        }
    }

    public function verificarLogin()
    {
        return auth()->check() ? redirect('home') : redirect('login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('Token Name')->plainTextToken;

            // Buscar la persona asociada
            $personaD = Persona::find($user->persona_id);
            if (!$personaD) {
                return response()->json(['error' => 'No se encontró la persona asociada'], 404);
            }

            // Construcción de datos de la persona
            $persona = [
                "id" => $personaD->id,
                "tipo_documento" => optional($personaD->tipoDocumento)->name,
                "numero_documento" => $personaD->numero_documento,
                "primer_nombre" => $personaD->primer_nombre,
                "segundo_nombre" => $personaD->segundo_nombre,
                "primer_apellido" => $personaD->primer_apellido,
                "segundo_apellido" => $personaD->segundo_apellido,
                "fecha_de_nacimiento" => $personaD->fecha_de_nacimiento,
                "genero" => optional($personaD->tipoGenero)->name,
                "email" => $personaD->email,
                "created_at" => $personaD->created_at,
                "updated_at" => $personaD->updated_at,
                "instructor_id" => optional($personaD->instructor)->id,
                "regional_id" => optional(optional($personaD->instructor)->regional)->id,
            ];

            return response()->json(['user' => $user, 'persona' => $persona, 'token' => $token], 200);
        }

        return response()->json(['error' => 'Credenciales incorrectas'], 401);
    }
}
