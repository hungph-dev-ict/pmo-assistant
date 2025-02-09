<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Mail\TenantUserRegisteredMail;
use Illuminate\Support\Facades\Mail;

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
        // Lấy dữ liệu từ request và loại bỏ khoảng trắng
        $emails = array_map('trim', $request->input('emails'));
        $accounts = array_map('trim', $request->input('accounts'));
        $fullNames = array_map('trim', $request->input('full_names'));
        $passwords = array_map('trim', $request->input('passwords'));
        $jobPositions = array_map('trim', $request->input('job_positions'));

        // Định nghĩa mapping cho Job Position
        $jobPositionMapping = [
            "Manager" => 1,
            "Project Manager" => 2,
            "Bridge System Engineer" => 3,
            "Developer" => 4,
            "Tester" => 5,
            "Comtor" => 6,
            "Other" => 7,
        ];

        // Kiểm tra số dòng có khớp nhau không
        if (count($emails) !== count($accounts) || count($emails) !== count($fullNames) || count($emails) !== count($passwords) || count($emails) !== count($jobPositions)) {
            return response()->json(['error' => 'Số dòng trong các ô input phải bằng nhau.'], 400);
        }

        // Kiểm tra email đã tồn tại chưa
        $existingEmails = User::whereIn('email', $emails)->pluck('email')->toArray();
        if (!empty($existingEmails)) {
            return response()->json(['error' => 'Các email sau đã tồn tại: ' . implode(', ', $existingEmails)], 400);
        }

        // Lấy thông tin Tenant
        $tenant = Tenant::find($tenant_id);
        if (!$tenant) {
            return response()->json(['error' => 'Tenant không tồn tại.'], 404);
        }

        // Chuẩn bị dữ liệu để insert
        $users = [];
        foreach ($emails as $index => $email) {
            $jobPositionText = $jobPositions[$index] ?? 'Other';
            $jobPositionId = $jobPositionMapping[$jobPositionText] ?? 7; // Mặc định là Other (7)

            $users[] = [
                'email' => $email,
                'account' => $accounts[$index] ?? '',
                'name' => $fullNames[$index] ?? '',
                'password' => Hash::make($passwords[$index] ?? 'password123'),
                'job_position' => $jobPositionId,
                'tenant_id' => $tenant_id,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Thực hiện insert dữ liệu vào database
        DB::table('users')->insert($users);

        // Gửi email & gán role
        foreach ($emails as $index => $email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $jobPositionId = $jobPositionMapping[$jobPositions[$index]] ?? 7;
                $role = ($jobPositionId <= 2) ? 'pm' : 'staff'; // PM hoặc Staff
                $user->assignRole($role);

                // Gửi email thông báo đăng ký user
                Mail::to($user->email)->send(new TenantUserRegisteredMail(
                    $user,
                    $passwords[$index] ?? 'password123',
                    $tenant->name, // Tên tenant
                    ucfirst($role) // Viết hoa chữ cái đầu của role
                ));
            }
        }

        // Đếm số lượng user được tạo thành công
        $userCount = count($users);

        return response()->json([
            'success' => "✅ Đã thêm thành công {$userCount} user!",
            'user_count' => $userCount,
        ], 201);
    }
}
