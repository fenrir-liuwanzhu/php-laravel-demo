<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        // with('roles') 预加载角色，避免 N+1 查询
        $users = User::with('roles')->get();
        return view('admin.users', compact('users'));
    }
}
