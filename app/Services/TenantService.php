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

    /**
     * Soft delete a tenant by its ID.
     */
    public function deleteTenantById(string $id): bool
    {
        $tenant = Tenant::findOrFail($id); // Tìm tenant theo ID, nếu không có sẽ trả về lỗi 404
        return $tenant->delete(); // Xóa mềm tenant
    }

    /**
     * Restore a soft-deleted tenant by its ID.
     */
    public function restoreTenantById(string $id): bool
    {
        $tenant = Tenant::withTrashed()->findOrFail($id); // Bao gồm cả tenant đã bị xóa mềm
        return $tenant->restore(); // Khôi phục tenant
    }
}
