<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct()
    {
        //不需要登录进行的操作
        $this->middleware(
            'auth',
            [
                'except' => ['show', 'create', 'store', 'index'],
            ]
        );

        //未登录用户
        $this->middleware(
            'guest',
            [
                'only' => ['create'],
            ]
        );
    }

    public function index()
    {
        $users = User::paginate(10);

        return view('users.index', compact('users'));
    }

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

        \Auth::login($user);
        session()->flash('success', '欢迎您的加入');

        return redirect()->route('users.show', [$user]);
    }

    public function show(User $user)
    {
        $statuses = $user->statuses()->orderBy('created_at', 'desc')->paginate(10);

        return view('users.show', compact('user', 'statuses'));
    }

    //关注列表
    public function followers(User $user)
    {
        $users = $user->followers()->paginate(10);
        $title     = $user->name.'关注的人';

        return view('users._show_follow', compact('users', 'title'));
    }

    //粉丝列表
    public function followings(User $user)
    {
        $users = $user->followings()->paginate(10);
        $title     = $user->name.'的粉丝';

        return view('users._show_follow', compact('users', 'title'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        $this->validate(
            $request,
            [
                'name'     => 'required|max:50',
                'password' => 'nullable|confirmed|min:6',
            ]
        );

        $data         = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success', '个人资料更新成功');

        return redirect()->route('users.show', $user);
    }

    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '删除成功');

        return back();
    }
}
