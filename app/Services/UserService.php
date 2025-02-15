<?php

namespace App\Services;

use App\Models\Tenant;
use App\Mail\TenantUserRegisteredMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

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

        $tenant = $user->tenant;

        // Gán vai trò cho user
        $role = ($user->job_position <= 2) ? 'pm' : 'staff';
        if (!$user->hasRole($role)) { 
            $user->syncRoles($role); // Chỉ cập nhật khi role thay đổi
        }

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
