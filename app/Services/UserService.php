<?php

namespace App\Services;

use App\Models\Tenant;
use App\Mail\TenantUserRegisteredMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function createUser(array $data)
    {
        $newUser = User::createUser($data);
        // $createdPassword = $data['ha_password'];

        // // Gửi email cho head tenant
        // Mail::to($newTenant->headUser->email)->send(new TenantRegisteredMail($newTenant, $createdPassword));

        // return $newTenant;
    }

    public function createUserByForm(array $data)
    {

        $user = User::createUserByForm($data);

        $tenant = $user->tenant;

        // Gán vai trò cho user
        $role = ($user->job_position <= 2) ? 'pm' : 'staff';
        $user->assignRole($role);

        // Gửi email thông báo
        Mail::to($user->email)->send(new TenantUserRegisteredMail(
            $user,
            $data['user_password'],
            $tenant->name ?? 'Unknown Tenant', // Kiểm tra nếu không có tenant
            ucfirst($role)
        ));

        return $user;
    }
    public function updateUser($idUser, array $data)
    {

        $user = User::updateUser($idUser, $data);

        // Xác định role chính
        if (in_array($user->job_position, [1, 8, 9])) {
            $role = 'client';
        } elseif (in_array($user->job_position, [2, 3, 10, 11])) {
            $role = 'pm';
        } else {
            $role = 'staff';
        }

        // Xác định sub_role_1
        if (in_array($user->sub_role_1, [1, 8, 9])) {
            $sub_role_1 = 'client';
        } elseif (in_array($user->sub_role_1, [2, 3, 10, 11])) {
            $sub_role_1 = 'pm';
        } else {
            $sub_role_1 = 'staff';
        }

        // Xác định sub_role_2
        if (in_array($user->sub_role_2, [1, 8, 9])) {
            $sub_role_2 = 'client';
        } elseif (in_array($user->sub_role_2, [2, 3, 10, 11])) {
            $sub_role_2 = 'pm';
        } else {
            $sub_role_2 = 'staff';
        }

        // Tạo danh sách role cần gán
        $roles = [$role];

        if (!empty($user->sub_role_1) && !$user->hasRole($user->sub_role_1)) {
            $roles[] = $sub_role_1;
        }

        if (!empty($user->sub_role_2) && !$user->hasRole($user->sub_role_2)) {
            $roles[] = $sub_role_2;
        }

        // Cập nhật tất cả role một lần duy nhất
        $user->syncRoles($roles);

        return $user;
    }

    public function deleteUserById(string $id): bool
    {
        $user = User::findOrFail($id); // Tìm User theo ID, nếu không có sẽ trả về lỗi 404
        return $user->delete(); // Xóa mềm User
    }

    /**
     * Restore a soft-deleted User by its ID.
     */
    public function restoreUserById(string $id): bool
    {
        $user = User::withTrashed()->findOrFail($id); // Bao gồm cả User đã bị xóa mềm
        return $user->restore(); // Khôi phục User
    }
}
