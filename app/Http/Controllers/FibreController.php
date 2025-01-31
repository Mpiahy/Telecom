<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FibreController extends Controller
{

    // Load utilisateur View
    public function fibreView()
    {
        $login = Session::get('login');
        return view('ref.fibre', compact('login'));
    }
}
