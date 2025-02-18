<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function register(Request $request) {
        $validated = $request->validate([
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required|confirmed|min:3'
        ]);

        $user = User::create($validated);

        echo "<pre>";
        print_r($user);
    }
}
