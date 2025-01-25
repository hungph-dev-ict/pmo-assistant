<?php

namespace App\Http\Controllers;

use App\Services\TenantService;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use App\Http\Requests\CreateTenantRequest;
use App\Models\Tenant;
use App\Models\Plan;
use Log;

class TenantController extends Controller
{
    protected $tenantService;
    protected $imageUploadService;

    // Tiêm TenantService vào controller thông qua constructor
    public function __construct(TenantService $tenantService, ImageUploadService $imageUploadService)
    {
        $this->tenantService = $tenantService;
        $this->imageUploadService = $imageUploadService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lấy danh sách tenants
        $tenants = Tenant::withTrashed()->with(['headUser:id,account,name,avatar', 'plan:id,name'])->paginate(5);
        foreach ($tenants as $tenant) {
            // Kiểm tra nếu tenant chưa có logo đã mã hóa
            if (!$tenant->logo) {
                // Nếu không có logo, sử dụng URL mặc định
                $fileContent = file_get_contents('https://drive.google.com/uc?export=view&id=191jpGTBMy5o_ZyQLbNOBTKiOUYiPhG2X');
            } else {
                // Tải nội dung của logo từ URL
                $fileContent = file_get_contents($tenant->logo);
            }

            // Mã hóa ảnh thành Base64
            $tenant->logo_base64 = 'data:image/jpeg;base64,' . base64_encode($fileContent);
            Log::info('----');
            Log::info($tenant->headUser);
            if (!$tenant->headUser->avatar) {
                $tenant->ha_avatar_base64 = '';
            } else {
                // Tải nội dung của logo từ URL
                $fileContent = file_get_contents($tenant->headUser->avatar);
                // Mã hóa ảnh thành Base64
                $tenant->ha_avatar_base64 = 'data:image/jpeg;base64,' . base64_encode($fileContent);
            }
        }

        return view('tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $plans = Plan::all();
        return view('tenants.create', compact(var_name: 'plans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTenantRequest $request)
    {
        // Dữ liệu đã được validate
        $newTenantInfo = $request->validated();

        // Gọi service xử lý ảnh
        $logo_url = $this->imageUploadService->uploadToGoogleDrive('tenant_logo', $request->file('tenant_logo'));
        $ha_avatar = $this->imageUploadService->uploadToGoogleDrive('ha_avatar', $request->file('ha_avatar'));

            $createNewTenant = $this->tenantService->createTenant($newTenantInfo, $logo_url, $ha_avatar);
            if ($createNewTenant) {
                return redirect()->route('tenants.index')
                    ->with('success', 'Tenant created successfully.');
            }

        return 500;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Xóa tenant bằng TenantService
        $result = $this->tenantService->deleteTenantById($id);

        if ($result) {
            return redirect()->route('tenants.index')->with('success', 'Tenant deleted successfully.');
        }

        return redirect()->route('tenants.index')->with('error', 'Failed to delete tenant.');
    }

    /**
     * Restore the specified tenant from soft deletes.
     */
    public function restore(string $id)
    {
        // Khôi phục tenant bằng TenantService
        $result = $this->tenantService->restoreTenantById($id);

        if ($result) {
            return redirect()->route('tenants.index')->with('success', 'Tenant restored successfully.');
        }

        return redirect()->route('tenants.index')->with('error', 'Failed to restore tenant.');
    }
}
