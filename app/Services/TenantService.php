<?php

namespace App\Services;

use App\Models\Tenant;
use App\Mail\TenantRegisteredMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TenantService
{
    public function createTenant(array $data)
    {
        $newTenant = Tenant::createTenant($data);
        
        // Gửi email cho head tenant
        Mail::to($newTenant->headUser->email)->send(new TenantRegisteredMail($newTenant));

        return $newTenant;
    }
}
