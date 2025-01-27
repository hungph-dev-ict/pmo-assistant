<?php

namespace App\Http\Controllers;

use App\Services\TenantService;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use App\Http\Requests\CreateTenantRequest;
use App\Http\Requests\UpdateTenantRequest;
use App\Models\Tenant;
use App\Models\Plan;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Cache;
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
        $tenants = Tenant::withTrashed()->with(['headUser:id,account,name,avatar,tenant_id', 'plan:id,name'])->paginate(5);
        // Đường dẫn ảnh mặc định
        $defaultTenantLogo = 'https://drive.google.com/uc?export=view&id=191jpGTBMy5o_ZyQLbNOBTKiOUYiPhG2X';
        $defaultHaAvatar = 'https://drive.google.com/uc?export=view&id=1lv0f70ekHE_5AH7o6NQPEF9PmCPgc6Mk';

        // Lấy base64 của ảnh mặc định từ cache hoặc mã hóa một lần
        $defaultTenantLogoBase64 = Cache::rememberForever('default_tenant_logo_base64', function () use ($defaultTenantLogo) {
            return ImageHelper::imageToBase64($defaultTenantLogo);
        });

        // Lấy base64 của ảnh mặc định từ cache hoặc mã hóa một lần
        $defaultHaAvatarBase64 = Cache::rememberForever('default_ha_avatar_base64', function () use ($defaultHaAvatar) {
            return ImageHelper::imageToBase64($defaultHaAvatar);
        });

        foreach ($tenants as $tenant) {
            // Đường dẫn ảnh cần xử lý (có thể là null)
            $tenantLogoLink = $tenant->logo; // Hoặc null nếu không có ảnh

            // Nếu không có ảnh, sử dụng ảnh mặc định
            if (!$tenantLogoLink) {
                $tenant->logo_base64 = $defaultTenantLogoBase64;
            } else {
                // Gọi helper để xử lý base64
                $tenant->logo_base64 = ImageHelper::imageToBase64($tenantLogoLink);
            }

            $haAvatarLink = $tenant->headUser->avatar; // Hoặc null nếu không có ảnh
            // Nếu không có ảnh, sử dụng ảnh mặc định
            if (!$haAvatarLink) {
                $tenant->ha_avatar_base64 = $defaultHaAvatarBase64;
            } else {
                // Gọi helper để xử lý base64
                $tenant->ha_avatar_base64 = ImageHelper::imageToBase64($haAvatarLink);
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

        return view('tenants.create', compact('plans'));
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
        $tenant = Tenant::with(['headUser:id,email,account,name,avatar,tenant_id', 'plan:id,name'])->findOrFail($id);

        // Đường dẫn ảnh cần xử lý (có thể là null)
        $tenantLogoLink = $tenant->logo; // Hoặc null nếu không có ảnh

        // Nếu không có ảnh, sử dụng ảnh mặc định
        if (!$tenantLogoLink) {
            $tenant->logo_base64 = '';
        } else {
            // Gọi helper để xử lý base64
            $tenant->logo_base64 = ImageHelper::imageToBase64($tenantLogoLink);
        }

        $haAvatarLink = $tenant->headUser->avatar; // Hoặc null nếu không có ảnh
        // Nếu không có ảnh, sử dụng ảnh mặc định
        if (!$haAvatarLink) {
            $tenant->ha_avatar_base64 = '';
        } else {
            // Gọi helper để xử lý base64
            $tenant->ha_avatar_base64 = ImageHelper::imageToBase64($haAvatarLink);
        }

        $plans = Plan::all(); // Lấy danh sách các kế hoạch (hoặc logic khác)

        return view('tenants.edit', compact('tenant', 'plans'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTenantRequest $request, string $idTenant)
    {
        // Dữ liệu đã được validate
        $updateTenantInfo = $request->validated();

        // Gọi service xử lý ảnh
        $logo_url = $this->imageUploadService->uploadToGoogleDrive('tenant_logo', $request->file('tenant_logo'));
        $ha_avatar = $this->imageUploadService->uploadToGoogleDrive('ha_avatar', $request->file('ha_avatar'));

        $updateNewTenant = $this->tenantService->updateTenant($idTenant, $updateTenantInfo, $logo_url, $ha_avatar);
        if ($updateNewTenant) {
            return redirect()->route('tenants.index')
                ->with('success', 'Tenant created successfully.');
        }

        return 500;
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
