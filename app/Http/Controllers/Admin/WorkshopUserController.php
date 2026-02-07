<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignWorkshopUserRequest;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class WorkshopUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of users assigned to a workshop.
     */
    public function index(Request $request, Workshop $workshop): Response
    {
        Gate::authorize('manageUsers', $workshop);

        $workshop->load(['company']);

        // Get available users for assignment (HQ users or unassigned users)
        $availableUsers = User::query()
            ->where(function ($query) use ($workshop) {
                // Users from the same company
                if ($workshop->company_id) {
                    $query->where('company_id', $workshop->company_id);
                }
            })
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'company_admin');
            })
            ->whereDoesntHave('assignedWorkshops', function ($query) use ($workshop) {
                $query->where('workshop_id', $workshop->id);
            })
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $assignedUsers = $workshop->assignedUsers()
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'company_admin');
            })
            ->get();

        // Get the current user's role at this site
        $user = $request->user();
        $siteRole = $workshop->getUserRole($user->id);

        // Global admins effectively have site_admin privileges
        if (!$siteRole && $user->hasRole('pentadbiran')) {
            $siteRole = 'site_admin';
        }

        return Inertia::render('Admin/Workshops/Users/Index', [
            'workshop' => $workshop,
            'assignedUsers' => $assignedUsers,
            'availableUsers' => $availableUsers,
            // Site context for dual sidebar
            'site' => $workshop,
            'siteRole' => $siteRole,
        ]);
    }

    /**
     * Store a newly assigned user to the workshop.
     */
    public function store(AssignWorkshopUserRequest $request, Workshop $workshop): RedirectResponse
    {
        $userId = $request->validated('user_id');

        if (!$userId) {
            $data = $request->validated('new_user');
            $actor = $request->user();
            $roleName = $data['role'] ?? 'penyelia';

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'active' => true,
                'company_id' => $workshop->company_id ?? $actor->company_id,
            ]);
            $user->assignRole($roleName);
            $userId = $user->id;
        }

        $workshop->assignUser($userId, $request->validated('role'));

        return back()->with('success', 'User assigned to workshop successfully.');
    }

    /**
     * Update the role of an assigned user.
     */
    public function update(Request $request, Workshop $workshop, User $user): RedirectResponse
    {
        Gate::authorize('manageUsers', $workshop);

        $validated = $request->validate([
            'role' => 'required|string|in:site_admin,supervisor,technician,staff',
        ]);

        $workshop->assignedUsers()->updateExistingPivot($user->id, [
            'role' => $validated['role'],
        ]);

        return back()->with('success', 'User role updated successfully.');
    }

    /**
     * Remove the specified user from the workshop.
     */
    public function destroy(Workshop $workshop, User $user): RedirectResponse
    {
        Gate::authorize('manageUsers', $workshop);

        $workshop->removeUser($user->id);

        return back()->with('success', 'User removed from workshop successfully.');
    }
}
