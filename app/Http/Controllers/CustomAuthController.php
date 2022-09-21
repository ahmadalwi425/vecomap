<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use Illuminate\Support\Facades\Auth;

class CustomAuthController extends Controller
{
    public function tesLogin(Request $request)
    {
        $id = $request['id'];
        $pass = $request['password'];
        $device = Device::where('id', $request->get('id'))->where('password', md5($pass))->count();
        if($device==0){
            return redirect('login')->with('failed','Login gagal');
        } else{
            session(['iddev' => $id, 'login' => true]);
            // Session::put('login', 'Selamat anda berhasil login');
            return redirect('gps');
        }
    }

    public function openAuth(){
        dd(session()->exists('login') && session()->get('login'));
        // return view('gps');
    }
    public function logout(){
        session()->flush();
        return redirect('login');
    }
}
