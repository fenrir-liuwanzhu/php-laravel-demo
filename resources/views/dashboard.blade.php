@extends('layouts.app')

@section('title', '仪表盘')

@section('content')
    <h1>欢迎，{{ auth()->user()->name }}！</h1>
    <p>这是登录后的控制台页面。</p>
    <form method="POST" action="/logout">
        @csrf
        <button type="submit" style="padding:8px 16px;background:#dc2626;color:#fff;border:0;border-radius:6px;">退出登录</button>
    </form>
@endsection