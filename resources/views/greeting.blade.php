<!DOCTYPE html>
<html>
<head>
    <title>Blade 学习</title>
    <meta charset="utf-8">
</head>
<body>
    <h1>你好，{{ $name }}！</h1>
    <p>年龄：{{ $age }}</p>

    @if ($age !== '未知' && $age >= 18)
        <p>✅ 已成年</p>
    @else
        <p>⚠️ 未成年或未知</p>
    @endif

    <h2>水果列表</h2>
    <ul>
        @foreach ($fruits as $fruit)
            <li>{{ $fruit }}</li>
        @endforeach
    </ul>
</body>
</html>
