<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignWorkshopUserRequest;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
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

        $workshop->load(['assignedUsers', 'company']);

        // Get available users for assignment (HQ users or unassigned users)
        $availableUsers = User::query()
            ->where(function ($query) use ($workshop) {
                // Users from the same company
                if ($workshop->company_id) {
                    $query->where('company_id', $workshop->company_id);
                }
            })
            ->whereDoesntHave('assignedWorkshops', function ($query) use ($workshop) {
                $query->where('workshop_id', $workshop->id);
            })
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role']);

        // Get the current user's role at this site
        $user = $request->user();
        $siteRole = $workshop->getUserRole($user->id);

        // Global admins effectively have site_admin privileges
        if (!$siteRole && $user->hasRole('pentadbiran')) {
            $siteRole = 'site_admin';
        }

        return Inertia::render('Admin/Workshops/Users/Index', [
            'workshop' => $workshop,
            'assignedUsers' => $workshop->assignedUsers,
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
        $workshop->assignUser(
            $request->validated('user_id'),
            $request->validated('role')
        );

        return back()->with('success', 'User assigned to workshop successfully.');
    }

    /**
     * Update the role of an assigned user.
     */
    public function update(Request $request, Workshop $workshop, User $user): RedirectResponse
    {
        Gate::authorize('manageUsers', $workshop);

        $validated = $request->validate([
            'role' => 'required|string|in:supervisor,technician,staff',
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
