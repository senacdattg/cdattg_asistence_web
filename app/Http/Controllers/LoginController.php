<?php

namespace App\Http\Controllers\Auth;

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\User;
use Exception;
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
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            // La autenticación fue exitosa
            return redirect()->route('home.index')->with('success', '¡Sesión Iniciada!');; // Puedes redirigir a donde desees
        } else {
            // La autenticación falló
            return back()->withInput()->withErrors(['error' => 'Correo o contraseña invalido']);
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
