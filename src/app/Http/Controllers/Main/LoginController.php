<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    /**
     * Show the application dashboard.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('main/login');
    }
}
