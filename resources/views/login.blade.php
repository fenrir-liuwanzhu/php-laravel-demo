<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title', '后台管理')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body style="margin:0;font-family:sans-serif;">
    <div style="width: 1000px;margin: 0 auto;padding: 40px;">
        <h1 style="text-align:center;">用户登录</h1>

        @if ($errors->any())
            <div style="background:#fef2f2;border:1px solid #fca5a5;padding:10px;border-radius:6px;margin-bottom:12px;color:#b91c1c;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf
            <div style="margin-bottom:12px;">
                <label>邮箱</label><br>
                <input type="email" name="email" value="{{ old('email') }}"
                    style="width:100%;padding:8px;box-sizing:border-box;">
            </div>
            <div style="margin-bottom:12px;">
                <label>密码</label><br>
                <input type="password" name="password"
                    style="width:100%;padding:8px;box-sizing:border-box;">
            </div>
            <label style="font-size:13px;">
                <input type="checkbox" name="remember"> 记住我
            </label>
            <button type="submit" style="display:block;margin:10px auto;padding:10px 20px;width:100px;background:#2563eb;color:#fff;border:0;border-radius:6px;">
                登录
            </button>
        </form>
    </div>
</body>
</html>