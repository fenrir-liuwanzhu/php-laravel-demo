<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'name' => 'required|min:3|max:50',
            // 编辑时 unique 要排除自己，否则邮箱没变也会报"已存在"
            'email' => 'required|email|unique:users,email,'.$user->id,
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user->update([
            'name' => $validated['name'],
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

    public function showAvatar()
    {
        return view('admin.avatar');
    }

    public function uploadAvatar(Request $request)
    {
        logger('uploadAvatar 被调用了', [
            'user_id'  => optional(auth()->user())->id,
            'has_file' => $request->hasFile('image'),
        ]);

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $request->file('image');
        $path = $image->store('avatars', 'public');

        $user = auth()->user();

        // 顺手删掉旧头像文件，避免垃圾文件堆积
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }
        $user->avatar = $path;
        $user->save();

        // 用 flash 把"可访问的 URL"带回去
        return back()->with('success', '头像已更新');
    }

    public function downloadAvatar(Request $request) 
    {
        $user = auth()->user();
        if (!$user->avatar) {
            return back()->with('error', '没有头像');
        }
        $path = Storage::disk('public')->path($user->avatar);
        return response()->download($path);
    }

    public function showPdf(Request $request)
    {
        return view('admin.pdf');
    }

    public function previewPdf($name)
    {
        $path = Storage::disk('public')->path('documents/' . $name);
        return response()->file($path);
    }

    public function downloadPdf($name)
    {
        $path = Storage::disk('public')->path('documents/' . $name);
        return response()->download($path);
    }
}
