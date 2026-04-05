<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:pentadbiran');
    }

    /**
     * Display a listing of roles.
     */
    public function index()
    {
        $roles = Role::withCount(['users', 'permissions'])
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/Roles/Index', [
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $permissions = Permission::orderBy('name')->get();

        return Inertia::render('Admin/Roles/Create', [
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:50',
            'metadata' => 'nullable|array',
            'is_active' => 'boolean',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // System roles cannot be created via UI
        $validated['is_system_role'] = false;

        $role = Role::create($validated);

        // Assign permissions
        if (!empty($validated['permissions'])) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role)
    {
        $role->load(['permissions', 'users']);

        return Inertia::render('Admin/Roles/Show', [
            'role' => $role,
        ]);
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        $role->load('permissions');

        $permissions = Permission::orderBy('name')->get();

        $rolePermissionIds = $role->permissions->pluck('id')->toArray();

        return Inertia::render('Admin/Roles/Edit', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissionIds' => $rolePermissionIds,
        ]);
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, Role $role)
    {
        // Prevent editing system roles
        if ($role->is_system_role) {
            return back()->withErrors([
                'error' => 'Cannot modify system roles. You can only manage their permissions.',
            ]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:50',
            'metadata' => 'nullable|array',
            'is_active' => 'boolean',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
        
        $role->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'color' => $validated['color'] ?? null,
            'metadata' => $validated['metadata'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);
        
        if (isset($validated['permissions']) && !empty($validated['permissions'])) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('admin.roles.edit', $role)
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Role $role)
    {
        // Prevent deleting system roles
        if ($role->is_system_role) {
            return back()->withErrors([
                'error' => 'Cannot delete system roles.',
            ]);
        }

        // Check if role has users
        if ($role->users()->count() > 0) {
            return back()->withErrors([
                'error' => 'Cannot delete role that has assigned users.',
            ]);
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    /**
     * Update role permissions.
     */
    public function updatePermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $permissions = Permission::whereIn('id', $validated['permissions'] ?? [])->get();
        $role->syncPermissions($permissions);

        return back()->with('success', 'Role permissions updated successfully.');
    }

    /**
     * Show permission matrix.
     */
    public function permissions()
    {
        $roles = Role::with('permissions')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $permissions = Permission::orderBy('name')->get();

        // Create matrix
        $matrix = [];
        foreach ($permissions as $permission) {
            $matrix[$permission->id] = [
                'permission' => $permission,
                'roles' => [],
            ];

            foreach ($roles as $role) {
                $matrix[$permission->id]['roles'][$role->id] = $role->hasPermissionTo($permission);
            }
        }

        return Inertia::render('Admin/Roles/Permissions', [
            'roles' => $roles,
            'permissions' => $permissions,
            'matrix' => $matrix,
        ]);
    }

    /**
     * Update permission matrix.
     */
    public function updatePermissionMatrix(Request $request)
    {
        $validated = $request->validate([
            'matrix' => 'required|array',
            'matrix.*' => 'array',
            'matrix.*.*' => 'boolean',
        ]);

        // matrix structure: [role_id => [permission_id => true/false]]
        foreach ($validated['matrix'] as $roleId => $permissions) {
            $role = Role::find($roleId);
            if (!$role) {
                continue;
            }

            $permissionIds = [];
            foreach ($permissions as $permissionId => $hasPermission) {
                if ($hasPermission) {
                    $permissionIds[] = $permissionId;
                }
            }

            $permissionsToSync = Permission::whereIn('id', $permissionIds)->get();
            $role->syncPermissions($permissionsToSync);
        }

        return back()->with('success', 'Permission matrix updated successfully.');
    }

    /**
     * Deactivate role.
     */
    public function deactivate(Role $role)
    {
        if ($role->is_system_role) {
            return back()->withErrors([
                'error' => 'Cannot deactivate system roles.',
            ]);
        }

        $role->update(['is_active' => false]);

        return back()->with('success', 'Role deactivated successfully.');
    }

    /**
     * Activate role.
     */
    public function activate(Role $role)
    {
        $role->update(['is_active' => true]);

        return back()->with('success', 'Role activated successfully.');
    }

    /**
     * Get users for a role (API endpoint).
     */
    public function getUsers(Role $role)
    {
        $users = $role->users()
            ->select('id', 'name', 'email')
            ->get();

        return response()->json([
            'users' => $users,
        ]);
    }

    /**
     * Assign users to role (API endpoint).
     */
    public function assignUsers(Request $request, Role $role)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $users = \App\Models\User::whereIn('id', $validated['user_ids'])->get();

        foreach ($users as $user) {
            $user->assignRole($role);
        }

        return back()->with('success', 'Users assigned to role successfully.');
    }

    /**
     * Remove users from role (API endpoint).
     */
    public function removeUsers(Request $request, Role $role)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $users = \App\Models\User::whereIn('id', $validated['user_ids'])->get();

        foreach ($users as $user) {
            $user->removeRole($role);
        }

        return back()->with('success', 'Users removed from role successfully.');
    }
}
