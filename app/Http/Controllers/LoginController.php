<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index(){
        return view('login');
    }

    public function authenticate(Request $req){
        $credentials = $req->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);

        if (Auth::attempt($credentials)){
            $pengguna = DB::table('users as us')
                        ->join('users_level as lv', 'us.id_user_level', '=', 'lv.id_user_level')
                        ->where('us.username', $req->username)->first();
            Session::put('sesUsername', $pengguna->username);
            Session::put('sesName', $pengguna->name);
            Session::put('sesLevel', $pengguna->level);

            return redirect()->intended('kegiatan');
        }else{
            return redirect()->back()->withErrors(['message' => 'Username/password mismatch!']);
        }
    }

}