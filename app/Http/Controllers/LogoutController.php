<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function cerrarSesion()
    {
        Auth::logout();
        return redirect('/login')->with('success', '¡Sesión cerrada exitosamente!');
    }
}
