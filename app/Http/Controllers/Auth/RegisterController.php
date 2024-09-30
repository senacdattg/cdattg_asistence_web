<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\User;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }
    
    public function create(Request $request)
    {
        $data = $request->validate([
            'primer_nombre' => 'required|string|max:255',
            'segundo_nombre' => 'string|max:255',
            'primer_apellido' => 'required|string|max:255',
            'segundo_apellido' => 'string|max:255',
            'documento' => 'required|string|unique:users|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        // Convertir a mayúsculas los campos
        $data['primer_nombre'] = strtoupper($data['primer_nombre']);
        $data['segundo_nombre'] = strtoupper($data['segundo_nombre']);
        $data['primer_apellido'] = strtoupper($data['primer_apellido']);
        $data['segundo_apellido'] = strtoupper($data['segundo_apellido']);
        $data['password'] = bcrypt($data['documento']);

        $user = User::create($data);

        // Autenticar al usuario automáticamente si lo deseas

        return redirect('/login')->with('success', '¡Registro Exitoso!'); // Puedes redirigir a donde desees
    }

    public function mostrarFormulario()
    {
        return view('user.registro');
    }
}
