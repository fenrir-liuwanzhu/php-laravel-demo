<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\support\Facades\Auth;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function hello(string $name)
    {
        $age = request('age', '未传');   // 拿 URL 的 ?age=25，没有就默认"未传"

        $output = "你好，{$name}！";            // $name 来自路由参数 /hello/小明
        $output .= "\nage 参数: {$age}";       // $age 来自 query 参数 ?age=25
        $output .= "\n请求方法: " . request()->method();  // GET

        return response($output)->header('Content-Type', 'text/plain');
    }

    public function greeting(string $name)
    {
        $age = request('age', '未传');
        $fruits = ['apple', 'banana', 'cherry'];

        return view('greeting', ['name' => $name, 'age' => $age, 'fruits' => $fruits]);
    }

    public function showRegister()
    {
        return view('register');            // GET：显示表单
    }

    public function doRegister(Request $request)
    {
        // 表单验证：用户名必填至少3位，密码必填至少6位
        $validated = $request->validate([
            'username' => 'required|min:3|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        // 创建用户
        $user = User::create([
            'name' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        // 这里先不存数据库（W4 学），先演示流程
        return redirect('/register')->with('success', "注册成功，用户名：{$validated['username']}");
    }

    public function showLogin()
    {
        return view('login');
    }

    public function doLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        
        if(Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect('/dashboard')->with('success', '登录成功');
        }

        return back()->withErrors([
            'email' => '邮箱或密码错误',
        ]);
        
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', '退出成功');
    }

    public function showDashboard()
    {
        return view('dashboard');
    }

}
