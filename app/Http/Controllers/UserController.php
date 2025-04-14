<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    
    public function login()
    {

        return view('login');

    }

    public function AuthLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->intended('/')->with('success', 'Login successful');
        }

        return redirect('/login')->with('error', 'Invalid credentials');
    }



    public function register()
    {

        $roles = role::all()->where('name', '!=', 'admin');

        return view('register', ['roles' => $roles]);

    }


    public function store(Request $request)
    {


        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4',
            'role_id' => 'required'
        ]);

        User::create([

            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role_id' => $request->role_id

        ]);

        return redirect('/login');
        
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Successfully logged out');
    }


}
