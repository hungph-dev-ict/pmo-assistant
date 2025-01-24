<?php

namespace App\Http\Controllers;

use App\Services\TenantService;
use Illuminate\Http\Request;
use App\Http\Requests\CreateTenantRequest;
use App\Models\Tenant;
use App\Models\Plan;

class TenantController extends Controller
{
    protected $tenantService;

    // Tiêm TenantService vào controller thông qua constructor
    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = Tenant::withTrashed()->with(['headUser:id,account,name', 'plan:id,name'])->paginate(10);

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

        $createNewTenant = $this->tenantService->createTenant($newTenantInfo);

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
