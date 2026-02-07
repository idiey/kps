<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\GovernmentDepartment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AssetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:pentadbiran');
    }

    /**
     * Display a listing of assets
     */
    public function index(Request $request)
    {
        $query = Asset::with(['governmentDepartment']);

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by asset type
        if ($request->filled('asset_type')) {
            $query->where('asset_type', $request->asset_type);
        }

        // Filter by condition
        if ($request->filled('condition')) {
            $query->where('current_condition', $request->condition);
        }

        // Filter by department
        if ($request->filled('department_id')) {
            $query->forDepartment($request->department_id);
        }

        $assets = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $departments = GovernmentDepartment::orderBy('name')->get();

        return Inertia::render('Admin/Assets/Index', [
            'assets' => $assets,
            'departments' => $departments,
            'filters' => $request->only(['search', 'asset_type', 'condition', 'department_id']),
        ]);
    }

    /**
     * Show the form for creating a new asset
     */
    public function create()
    {
        $departments = GovernmentDepartment::orderBy('name')->get();

        return Inertia::render('Admin/Assets/Create', [
            'departments' => $departments,
        ]);
    }

    /**
     * Store a newly created asset
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_tag' => ['required', 'string', 'max:255', 'unique:assets,asset_tag'],
            'asset_type' => ['required', 'string', 'max:255'],
            'asset_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'current_condition' => ['required', 'in:operational,maintenance_required,under_repair,decommissioned'],
            'last_maintenance_date' => ['nullable', 'date'],
            'government_department_id' => ['required', 'exists:government_departments,id'],
        ]);

        Asset::create($validated);

        return redirect()->route('admin.assets.index')
            ->with('success', 'Asset created successfully.');
    }

    /**
     * Display the specified asset
     */
    public function show(Asset $asset)
    {
        $asset->load(['governmentDepartment', 'workshopJobs']);

        return Inertia::render('Admin/Assets/Show', [
            'asset' => $asset,
        ]);
    }

    /**
     * Show the form for editing the specified asset
     */
    public function edit(Asset $asset)
    {
        $asset->load('governmentDepartment');
        $departments = GovernmentDepartment::orderBy('name')->get();

        return Inertia::render('Admin/Assets/Edit', [
            'asset' => $asset,
            'departments' => $departments,
        ]);
    }

    /**
     * Update the specified asset
     */
    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'asset_tag' => ['required', 'string', 'max:255', 'unique:assets,asset_tag,' . $asset->id],
            'asset_type' => ['required', 'string', 'max:255'],
            'asset_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'current_condition' => ['required', 'in:operational,maintenance_required,under_repair,decommissioned'],
            'last_maintenance_date' => ['nullable', 'date'],
            'government_department_id' => ['required', 'exists:government_departments,id'],
        ]);

        $asset->update($validated);

        return redirect()->route('admin.assets.index')
            ->with('success', 'Asset updated successfully.');
    }

    /**
     * Remove the specified asset
     */
    public function destroy(Asset $asset)
    {
        $asset->delete();

        return redirect()->route('admin.assets.index')
            ->with('success', 'Asset deleted successfully.');
    }
}
