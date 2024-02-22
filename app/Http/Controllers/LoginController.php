<?php

namespace App\Http\Controllers\Auth;

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function mostrarLogin()
    {
        return view('user.login');
    }
    public function iniciarSesion(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            // La autenticación fue exitosa
            return redirect('/home')->with('success', '¡Sesión Iniciada!');; // Puedes redirigir a donde desees
        } else {
            // La autenticación falló
            return back()->withErrors(['danger' => 'Correo o contraseña invalido']);
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
}
