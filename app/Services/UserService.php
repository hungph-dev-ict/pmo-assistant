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
}
