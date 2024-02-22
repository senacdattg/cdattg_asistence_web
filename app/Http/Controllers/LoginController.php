<?php
namespace App\Http\Controllers\Auth;
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\User;

class LoginController extends Controller
{
    public function mostrarLogin(){
    return view('user.login');
}
}
