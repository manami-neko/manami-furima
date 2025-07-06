<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class UserController extends Controller
{
    // public function login(LoginRequest $request)
    // {

    //     return view('user.login');
    // }

    public function create(RegisterRequest $request)
    {
        return view('users.login');
    }
}
