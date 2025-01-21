<?php

namespace App\Services;

use App\Models\Tenant;

class TenantService
{
    public function createTenant(array $data)
    {
        return Tenant::createTenant($data);
    }
}
