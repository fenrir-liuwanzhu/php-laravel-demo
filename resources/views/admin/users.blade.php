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
        </tr>
        @foreach ($users as $user)
            <tr>
                <td style="border:1px solid #ddd;padding:8px;">{{ $user->id }}</td>
                <td style="border:1px solid #ddd;padding:8px;">{{ $user->name }}</td>
                <td style="border:1px solid #ddd;padding:8px;">{{ $user->email }}</td>
                <td style="border:1px solid #ddd;padding:8px;">
                    {{ $user->roles->pluck('label')->join('、') ?: '无' }}
                </td>
            </tr>
        @endforeach
    </table>
@endsection
