<?php

namespace App\Http\Controllers;

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
            'username' => 'required|min:3',
            'password' => 'required|min:6',
        ]);

        // 这里先不存数据库（W4 学），先演示流程
        return redirect('/register')->with('success', "注册成功，用户名：{$validated['username']}");
    }

}
