<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class gpsController extends Controller
{
    public function gps()
    {
        $iddev = session()->get('iddev');
        return view('gps', compact('iddev'));
    }
}
