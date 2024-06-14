<?php

namespace App\Http\Controllers\Auth;

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(){

        return view('user.login');
    }
    public function mostrarLogin()
    {
    }
    public function iniciarSesion(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|string',
                'password' => 'required|string',
            ]);

            if (Auth::attempt($credentials)) {
                // La autenticación fue exitosa
                return redirect()->route('home.index')->with('success', '¡Sesión Iniciada!');
            } else {
                // La autenticación falló
                return back()->withInput()->withErrors(['error' => 'Correo o contraseña inválido']);
            }
        } catch (\Exception $e) {
            // Capturar y manejar cualquier excepción que ocurra durante el proceso
            return back()->withInput()->withErrors(['error' => 'Error al iniciar sesión: ' . $e->getMessage()]);
        }
    }

    public function verificarLogin()
    {

        if (auth()->check()) {
            return redirect('home');
        } else {
            return redirect('login');
        }
    }
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('Token Name')->plainTextToken; // Generar el token

            $personaD = Persona::find($user->persona_id);
            $persona = [
                "id" => $personaD->id,
                "tipo_documento" => $personaD->tipoDocumento->name,
                "numero_documento" => $personaD->numero_documento,
                "primer_nombre" => $personaD->primer_nombre,
                "segundo_nombre" => $personaD->segundo_nombre,
                "primer_apellido" => $personaD->primer_apellido,
                "segundo_apellido" => $personaD->segundo_apellido,
                "fecha_de_nacimiento" => $personaD->fecha_de_nacimiento,
                "genero" => $personaD->tipoGenero->name,
                "email" => $personaD->email,
                "created_at" => $personaD->created_at,
                "updated_at" => $personaD->updated_at,
                "instructor_id" => $personaD->instructor->id,
                "regional_id" => $personaD->instructor->regional->id,
            ];
            // Retornar la respuesta JSON incluyendo el token
            return response()->json(['user' => $user, 'persona' => $persona, 'token' => $token], 200);
        }
    return response()->json(['error' => 'Credenciales incorrectas'], 401);
        //  back()->withErrors([
        //     'email' => 'The provided credentials do not match our records.',
        // ])->onlyInput('email');
    }
}
