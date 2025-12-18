<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PerpustakaanController extends Controller
{
    public function index()
    {
        return view('tampilan.index');
    }

}
