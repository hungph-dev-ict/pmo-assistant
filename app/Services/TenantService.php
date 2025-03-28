<?php

namespace App\Services;

use App\Models\Tenant;
use App\Mail\TenantRegisteredMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class TenantService
{
    public function createTenant(array $data, $logo_path, $ha_avatar)
    {
        $newTenant = Tenant::createTenant($data, $logo_path, $ha_avatar);
        $createdPassword = $data['ha_password'];

        // Gửi email cho head tenant
        Mail::to($newTenant->headUser->email)->send(new TenantRegisteredMail($newTenant, $createdPassword));

        return $newTenant;
    }

    public function updateTenant($idTenant, array $data, $logo_path, $ha_avatar)
    {
        $updateTenant = Tenant::updateTenant($idTenant, $data, $logo_path, $ha_avatar);

        return $updateTenant;
    }

    /**
     * Soft delete a tenant by its ID and all users belonging to the tenant.
     */
    public function deleteTenantById(string $id): bool
    {
        $tenant = Tenant::findOrFail($id); // Tìm tenant theo ID, nếu không có sẽ trả về lỗi 404

        // Xóa mềm tất cả các users thuộc về tenant này
        User::where('tenant_id', $id)->update(['deleted_at' => now()]);

        // Sau đó, xóa mềm tenant
        return $tenant->delete(); // Xóa mềm tenant
    }

    /**
     * Restore a soft-deleted tenant by its ID and restore all soft-deleted users associated with it.
     */
    public function restoreTenantById(string $id): bool
    {
        // Tìm tenant, bao gồm cả tenant bị xóa mềm
        $tenant = Tenant::withTrashed()->findOrFail($id);

        // Khôi phục tenant
        $tenant->restore();

        // Khôi phục tất cả các user bị xóa mềm có tenant_id tương ứng
        $users = User::onlyTrashed()->where('tenant_id', $id)->get();
        foreach ($users as $user) {
            $user->restore();
        }

        return true;
    }
}
