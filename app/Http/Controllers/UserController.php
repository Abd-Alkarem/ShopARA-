<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('users.index', compact('users'));
    }

    public function profile(User $user)
    {
        $cartItems = $user->cartItems()->with('product')->get();
        return view('users.profile', compact('user', 'cartItems'));
    }
}
