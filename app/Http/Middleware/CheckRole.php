<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();
        // 没登录：403（一般前面会先过 auth 中间件，这里兜底）
        if (! $user) {
            abort(403, '请先登录');
        }

        // 检查用户是否拥有指定角色
        $hasRole = $user->roles()->where('name', $role)->exists();
        if (! $hasRole) {
            abort(403, '无权访问该页面');
        }
        return $next($request);
    }
}
