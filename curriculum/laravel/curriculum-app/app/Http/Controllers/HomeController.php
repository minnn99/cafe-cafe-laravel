<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * ホームページを表示
     */
    public function index()
    {
        return view('home.index');
    }
}
