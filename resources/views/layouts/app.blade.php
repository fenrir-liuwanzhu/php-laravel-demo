<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title', '后台管理')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite(['resources/css/app.css'])
</head>
<body style="margin:0;font-family:sans-serif;">
    <main class="app-main">
        <div class="app-menu">
            <ul>
                <li><a href="/admin/users">用户管理</a></li>
                <li><a href="/user/avatar">头像管理</a></li>
                <li><a href="/user/pdf">pdf文件管理</a></li>
            </ul>
        </div>
        <div class="app-content">
            <header class="app-header">
                <div>我的后台管理系统</div>
                @auth
                    <span style="display:flex;align-items:center;gap:12px;">
                        <span style="font-size:13px;opacity:.85;">{{ auth()->user()->name }}</span>

                        <form method="POST" action="/logout" style="margin:0;">
                            @csrf
                            <button type="submit" style="background:#dc2626;color:#fff;border:0;border-radius:6px;padding:6px 14px;cursor:pointer;">
                                退出登录
                            </button>
                        </form>
                    </span>
                @endauth
            </header>
            <div class="app-content-inner">
            @yield('content')
            </div>
            <footer style="text-align:center;color:#999;padding:20px;font-size:12px;">
            © 2026 Laravel 学习项目
        </footer>
        </div>
    </main>

    
</body>
</html>
