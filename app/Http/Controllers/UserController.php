<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Kiểm tra quyền truy cập của admin
    public function __construct()
    {
        // $this->middleware('auth'); // Đảm bảo rằng chỉ người dùng đã đăng nhập mới có quyền truy cập
        // $this->middleware('admin'); // Middleware này sẽ kiểm tra xem người dùng có phải admin hay không (Nếu bạn đã định nghĩa)
    }

    // Hiển thị danh sách người dùng
    public function index()
    {
        // Lấy tất cả người dùng
        $users = User::with('jobPosition')->paginate(10);
        return view('users.index', compact('users')); // Trả về view với danh sách người dùng
    }

    public function getTenantUsers($tenant_id)
    {
        // Lấy tất cả người dùng
        $users = User::where('tenant_id', $tenant_id)->with('jobPosition')->paginate(10);
        return view('users.index', compact('users')); // Trả về view với danh sách người dùng
    }

    public function create()
    {
        return view('users.create');
    }

    public function createTenantUser($tenant_id)
    {
        return view('users.create');
    }
}
