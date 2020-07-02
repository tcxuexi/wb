<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'name'     => 'required|unique:users|max:50',
                'email'    => 'required|email|unique:users|max:255',
                'password' => 'required|confirmed|min:6',
            ]
        );

        $user = User::create(
            [
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => bcrypt($request->password),
            ]
        );

        session()->flash('success', '欢迎您的加入');

        return redirect()->route('users.show', [$user]);
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
}