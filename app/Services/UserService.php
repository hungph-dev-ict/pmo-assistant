<?php

namespace App\Services;

use App\Models\Tenant;
use App\Mail\TenantRegisteredMail;
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
}
