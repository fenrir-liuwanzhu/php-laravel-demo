<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title', '后台管理')</title>
</head>
<body style="margin:0;font-family:sans-serif;">
    <header style="background:#1f2937;color:#fff;padding:14px 20px;font-weight:700;">
        我的后台管理系统
    </header>

    <main style="max-width:600px;margin:24px auto;padding:0 16px;">
        @yield('content')
    </main>

    <footer style="text-align:center;color:#999;padding:20px;font-size:12px;">
        © 2026 Laravel 学习项目
    </footer>
</body>
</html>
