<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Tenant extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'logo', 'plan_id', 'tenant_id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function headUser()
    {
        $query = $this->hasOne(User::class, 'tenant_id')
            ->where('head_account_flg', '1');

        return $this->hasOne(User::class, 'tenant_id')
            ->where('head_account_flg', true);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public static function createTenant($tenantData, $logo_path, $ha_avatar)
    {
        $newTenant = self::create([
            'name' => $tenantData['tenant_name'],
            'description' => $tenantData['tenant_description'],
            'logo' => $logo_path,
            'plan_id' => $tenantData['tenant_plan']
        ]);

        $newUser = User::create([
            'account' => $tenantData['ha_account'],
            'name' => $tenantData['ha_full_name'],
            'email' => $tenantData['ha_email'],
            'avatar' => $ha_avatar,
            'tenant_id' => $newTenant->id,
            'head_account_flg' => '1',
            'job_position' => '1',
            'status' => '1',
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt($tenantData['ha_password'])
        ]);

        $newUser->assignRole('client');

        return $newTenant;
    }

    public static function updateTenant($idTenant, $tenantData, $logo_path, $ha_avatar)
    {
        // Lấy thông tin tenant hiện tại
        $tenant = self::find($idTenant);
        if (!$tenant) {
            throw new \Exception("Tenant không tồn tại.");
        }

        // Cập nhật thông tin tenant
        $tenant->update([
            'name' => $tenantData['tenant_name'],
            'description' => $tenantData['tenant_description'],
            'logo' => $logo_path,
            'plan_id' => $tenantData['tenant_plan'],
        ]);

        // Lấy tài khoản head account của tenant
        $headAccount = User::where('tenant_id', $idTenant)->where('head_account_flg', '1')->first();
        if (!$headAccount) {
            throw new \Exception("Head Account không tồn tại.");
        }

        // Cập nhật thông tin tài khoản head account
        $headAccount->update([
            'account' => $tenantData['ha_account'],
            'name' => $tenantData['ha_full_name'],
            'avatar' => $ha_avatar,
        ]);


        return $tenant;
    }
}
