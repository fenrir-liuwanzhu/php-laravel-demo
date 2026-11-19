@extends('layouts.app')

@section('title', '编辑用户')

@section('content')
    <h1>编辑用户：{{ $user->name }}</h1>

    <form method="POST" action="/admin/users/{{ $user->id }}">
        @csrf
        @method('PUT')

        <div style="margin-bottom:12px;">
            <label>姓名</label><br>
            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                   style="width:100%;padding:8px;box-sizing:border-box;">
        </div>
        <div style="margin-bottom:12px;">
            <label>邮箱</label><br>
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                   style="width:100%;padding:8px;box-sizing:border-box;">
        </div>

        <h3>分配角色</h3>
        @foreach ($roles as $role)
            <label style="display:block;margin-bottom:6px;">
                <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                    {{ $user->roles->contains($role->id) ? 'checked' : '' }}>
                {{ $role->label }} <span style="color:#999;">({{ $role->name }})</span>
            </label>
        @endforeach

        <button type="submit" style="margin-top:10px;padding:10px 20px;background:#2563eb;color:#fff;border:0;border-radius:6px;">
            保存
        </button>
    </form>
@endsection
