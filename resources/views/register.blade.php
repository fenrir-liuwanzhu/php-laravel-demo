@extends('layouts.app')

@section('title', '用户注册')

@section('content')
    <h1>用户注册</h1>

    {{-- 验证错误提示 --}}
    @if ($errors->any())
        <div style="background:#fef2f2;border:1px solid #fca5a5;padding:10px;border-radius:6px;margin-bottom:12px;color:#b91c1c;">
            <ul style="margin:0;padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div style="background:#ecfdf5;border:1px solid #6ee7b7;padding:10px;border-radius:6px;margin-bottom:12px;color:#065f46;">
            {{ session('success') }}
        </div>
    @endif


    <form method="POST" action="/register">
        @csrf
        <div style="margin-bottom:12px;">
            <label>用户名</label><br>
            <input type="text" name="username" value="{{ old('username') }}"
                   style="width:100%;padding:8px;box-sizing:border-box;">
        </div>
        <div style="margin-bottom:12px;">
            <label>密码</label><br>
            <input type="password" name="password"
                   style="width:100%;padding:8px;box-sizing:border-box;">
        </div>
        <button type="submit" style="padding:10px 20px;background:#2563eb;color:#fff;border:0;border-radius:6px;">
            注册
        </button>
    </form>
@endsection
