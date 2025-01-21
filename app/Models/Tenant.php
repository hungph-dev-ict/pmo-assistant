<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Tenant extends Model
{
    protected $fillable = ['name', 'description', 'head_user_id', 'plan_id'];

    public function headUser()
    {
        return $this->belongsTo(User::class, 'head_user_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public static function createTenant($tenantData)
    {
        $newUser = User::create([
            'account' => $tenantData['ha_account'],
            'name' => $tenantData['ha_full_name'],
            'email' => $tenantData['ha_email'],
            'job_position' => '1',
            'status' => '1',
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('client@123')
        ]);

        $newTenant = self::create([
            'name' => $tenantData['tenant_name'],
            'description' => $tenantData['tenant_description'],
            'head_user_id' => $newUser->id,
            'plan_id' => $tenantData['tenant_plan']
        ]);
        
        $result = $newUser->update([
            'tenant_head_acc_id' => $newTenant->id
        ]);

        return $result;
    }
}
