@extends('layouts.app')

@section('title', '用户管理')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <div class="text-2xl font-bold">用户管理</div>
        <div class="flex items-center gap-2">
            <a href="/admin/users/export" class="btn btn-primary">导出用户</a>
            <form id="import-form" action="/admin/users/import" method="post" enctype="multipart/form-data">
                @csrf
                <label class="btn btn-primary cursor-pointer">
                    导入用户
                    <input id="csv-input" type="file" name="csv" accept=".csv,text/csv,.txt" class="hidden">
                </label>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('csv-input').addEventListener('change', async function () {
            if (!this.files.length) {
                return;
            }

            const form = document.getElementById('import-form');
            const formData = new FormData(form);

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                    credentials: 'same-origin',
                });

                const data = await res.json();

                if (!res.ok) {
                    const msg = data.message
                        || (data.errors ? Object.values(data.errors).flat().join('\n') : null)
                        || '导入失败';
                    alert(msg);
                    this.value = '';
                    return;
                }

                let tip = data.message || '导入完成';
                if (data.errors && data.errors.length) {
                    tip += '\n\n' + data.errors.join('\n');
                }
                alert(tip);
                location.reload();
            } catch (e) {
                alert('导入请求失败');
                this.value = '';
            }
        });
    </script>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('import_errors'))
        <div class="alert alert-danger">
            <ul>
                @foreach(session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
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
