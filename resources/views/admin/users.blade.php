@extends('layouts.app')

@section('title', '用户管理')

@section('content')
    <h1>用户管理</h1>
    <table style="width:100%;border-collapse:collapse;">
        <tr style="background:#f3f4f6;">
            <th style="border:1px solid #ddd;padding:8px;">ID</th>
            <th style="border:1px solid #ddd;padding:8px;">姓名</th>
            <th style="border:1px solid #ddd;padding:8px;">邮箱</th>
            <th style="border:1px solid #ddd;padding:8px;">角色</th>
            <th style="border:1px solid #ddd;padding:8px;">操作</th>
        </tr>
        @foreach ($users as $user)
            <tr>
                <td style="border:1px solid #ddd;padding:8px;">{{ $user->id }}</td>
                <td style="border:1px solid #ddd;padding:8px;">{{ $user->name }}</td>
                <td style="border:1px solid #ddd;padding:8px;">{{ $user->email }}</td>
                <td style="border:1px solid #ddd;padding:8px;">
                    {{ $user->roles->pluck('label')->join('、') ?: '无' }}
                </td>
                <td style="border:1px solid #ddd;padding:8px;white-space:nowrap;">
                    <a href="/admin/users/{{ $user->id }}/edit" style="color:#2563eb;">编辑</a>

                    <form method="POST" action="/admin/users/{{ $user->id }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('确定删除该用户？')"
                                style="color:#dc2626;background:none;border:0;cursor:pointer;">删除</button>
                    </form>
                </td>

            </tr>
        @endforeach
    </table>
@endsection
