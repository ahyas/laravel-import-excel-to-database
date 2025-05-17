<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PhpInfoController extends Controller
{
    public function index(){
        return view('phpinfo.index');
    }
}
