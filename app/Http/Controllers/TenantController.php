<?php

namespace App\Http\Controllers;

use App\Services\TenantService;
use Illuminate\Http\Request;
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
        $tenants = Tenant::with(['headUser:id,account,name', 'plan:id,name'])->paginate(10);

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
    public function store(Request $request)
    {
        $newTenantInfo = $request->all();

        $createNewTenant = $this->tenantService->createTenant($newTenantInfo);

        if($createNewTenant) {
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
        //
    }
}
