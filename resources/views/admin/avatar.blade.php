@extends('layouts.app')

@section('title', '上传图片')

@section('content')
<div>
    <h1>上传图片</h1>

    @if (session('success'))
        <div style="background:#ecfdf5;border:1px solid #6ee7b7;padding:10px;border-radius:6px;margin-bottom:12px;color:#065f46;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="background:#fef2f2;border:1px solid #fca5a5;padding:10px;border-radius:6px;margin-bottom:12px;color:#b91c1c;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="/user/avatar" enctype="multipart/form-data">
        @csrf
        <input type="file" name="image">
        <button type="submit" style="padding:8px 16px;background:#2563eb;color:#fff;border:0;border-radius:6px;">
            上传
        </button>
    </form>
    @if (auth()->user()->avatar)
        <img src="{{ \Illuminate\Support\Facades\Storage::url(auth()->user()->avatar) }}"
            style="width:80px;height:80px;border-radius:50%;object-fit:cover;">
    @else
        <p>暂无头像</p>
    @endif

    <h4 style="margin-top: 20px;">
        <a href="/user/avatar/download" style="background:#2563eb;color:#fff;border:0;border-radius:6px;padding:8px 16px;text-decoration:none;">下载头像</a>
    </h4>
</div>
@endsection
