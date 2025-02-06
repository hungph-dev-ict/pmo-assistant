<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    // Tiêm TenantService vào controller thông qua constructor
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
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

    public function storeTenantUser(Request $request, $tenant_id)
    {
        // dd($request->all());
        // Dữ liệu đã được validate
        $newUserInfo = $request->all();

        $createNewUser = $this->userService->createUser($newUserInfo);
        // if ($createNewUser) {
        //     return redirect()->route('client.users.list')
        //         ->with('success', 'Tenant created successfully.');
        // }

        // return 500;
    }
}
