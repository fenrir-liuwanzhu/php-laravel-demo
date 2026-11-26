<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
            'user_id' => optional(auth()->user())->id,
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
        if (! $user->avatar) {
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
        $path = Storage::disk('public')->path('documents/'.$name);

        return response()->file($path);
    }

    public function downloadPdf($name)
    {
        $path = Storage::disk('public')->path('documents/'.$name);

        return response()->download($path);
    }

    public function export()
    {
        return response()->streamDownload(function () {
            $output = fopen('php://output', 'w');
            fwrite($output, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($output, ['ID', 'Name', 'Email', 'Role', 'Created At', 'Updated At']);
            User::with('roles')->chunk(500, function ($users) use ($output) {
                foreach ($users as $user) {
                    fputcsv($output, [$user->id, $user->name, $user->email, $user->roles->pluck('name')->implode(','), $user->created_at->format('Y-m-d H:i:s'), $user->updated_at->format('Y-m-d H:i:s')]);
                }
            });
            fclose($output);
        }, 'users-'.date('Y-m-d-H-i-s').'.csv', ['Content-Type' => 'text/csv;charset=utf-8']);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $handle = fopen($request->file('csv')->getRealPath(), 'r');

        // 表头：去 BOM / NBSP、压缩空白、小写、去掉 Excel 尾部空列
        $header = array_values(array_filter(array_map(function ($col) {
            $col = preg_replace('/^\xEF\xBB\xBF/', '', (string) $col);
            $col = str_replace("\u{00A0}", ' ', $col);

            return strtolower(trim(preg_replace('/\s+/u', ' ', $col) ?? ''));
        }, fgetcsv($handle) ?: []), fn (string $col) => $col !== ''));

        // 须与 export 表头一致（小写后）；fetch 请求不能用 back()，否则前端拿不到 flash
        $expectedHeader = ['id', 'name', 'email', 'role', 'created at', 'updated at'];
        if ($header !== $expectedHeader) {
            fclose($handle);
            $message = '表头不匹配。期望：['.implode(', ', $expectedHeader).']，实际：['.implode(', ', $header).']';

            if ($request->wantsJson()) {
                return response()->json(['message' => $message, 'header' => $header], 422);
            }

            return back()->with('error', $message);
        }

        $success = 0;
        $errors = [];
        $line = 1;
        // name => id，循环内本地校验，避免每行查库
        $rolesByName = Role::query()->pluck('id', 'name');

        while (($row = fgetcsv($handle)) !== false) {
            $line++;

            // 与表头对齐：去掉 Excel 尾部空列，再截断/补齐到表头列数
            $row = array_map(fn ($v) => is_string($v) ? str_replace("\u{00A0}", ' ', $v) : $v, $row);
            while ($row !== [] && trim((string) end($row)) === '') {
                array_pop($row);
            }
            if (empty(array_filter($row, fn ($v) => trim((string) $v) !== ''))) {
                continue;
            }
            if (count($row) > count($header)) {
                $errors[] = "第 {$line} 行：列数不匹配";

                continue;
            }
            $row = array_pad($row, count($header), '');

            $rowData = array_combine($header, $row) ?: [];

            // 新建导入：ID 列必须为空
            if (trim((string) ($rowData['id'] ?? '')) !== '') {
                $errors[] = "第 {$line} 行：ID 列必须为空";

                continue;
            }

            $roleNames = array_values(array_filter(
                array_map('trim', explode(',', (string) ($rowData['role'] ?? ''))),
                fn (string $name) => $name !== ''
            ));

            $data = [
                'name' => trim((string) ($rowData['name'] ?? '')),
                'email' => trim((string) ($rowData['email'] ?? '')),
                'roles' => $roleNames,
            ];

            $validator = Validator::make($data, [
                'name' => 'required|min:3|max:50',
                // 默认 email/rfc 会放过 haha@11；filter/strict 要求更像真实邮箱
                'email' => 'required|email:filter|unique:users,email',
                'roles' => 'array',
                'roles.*' => ['required', 'string', Rule::in($rolesByName->keys())],
            ], [
                'email.required' => '邮箱不能为空',
                'email.email' => '邮箱格式不正确',
                'email.unique' => '邮箱已被注册',
                'name.required' => '姓名不能为空',
                'name.min' => '姓名不能少于3个字符',
                'name.max' => '姓名不能超过50个字符',
                'roles.*.in' => '角色不存在，请填写 roles.name（如 admin）',
            ]);

            if ($validator->fails()) {
                $errors[] = "第 {$line} 行：".implode('；', $validator->errors()->all());

                continue;
            }

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                // users.password 非空，导入时生成临时密码
                'password' => '123456',
            ]);

            if ($roleNames !== []) {
                $roleIds = collect($roleNames)->map(fn (string $name) => $rolesByName[$name])->all();
                $user->roles()->sync($roleIds);
            }

            $success++;
        }

        fclose($handle);

        $message = "成功导入 {$success} 条";

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
                'success' => $success,
                'errors' => $errors,
            ]);
        }

        return back()->with('success', $message)
            ->with('import_errors', $errors);
    }
}
