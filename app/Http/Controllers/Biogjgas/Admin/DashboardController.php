<?php

namespace App\Http\Controllers\Biogjgas\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:VER BIOGJGAS ADMIN');
    }

    public function index(): View
    {
        return view('biogjgas.admin.dashboard');
    }
}
