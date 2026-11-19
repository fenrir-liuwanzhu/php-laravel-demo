<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        // with('roles') 预加载角色，避免 N+1 查询
        $users = User::with('roles')->get();
        return view('admin.users', compact('users'));
    }

    // 编辑页：显示信息 + 所有可选角色
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users-edit', compact('user', 'roles'));
    }

    // 保存修改 + 分配角色
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'   => 'required|min:3|max:50',
            // 编辑时 unique 要排除自己，否则邮箱没变也会报"已存在"
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'roles'  => 'array',
            'roles.*'=> 'exists:roles,id',
        ]);

        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ]);

        // sync：把用户角色设成表单勾选的那些（覆盖式）
        $user->roles()->sync($request->input('roles', []));

        return redirect('/admin/users')->with('success', '用户已更新');
    }

    // 删除
    public function destroy(User $user)
    {
        $user->delete();
        return redirect('/admin/users')->with('success', '用户已删除');
    }
}
