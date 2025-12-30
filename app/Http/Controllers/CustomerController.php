<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller for managing customers.
 */
class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Customer::class);

        $query = Customer::query();

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhere('government_entity', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('name')->paginate(15);

        return Inertia::render('Customers/Index', [
            'customers' => $customers,
            'filters' => $request->only('search'),
        ]);
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create(): Response
    {
        Gate::authorize('create', Customer::class);

        return Inertia::render('Customers/Create');
    }

    /**
     * Store a newly created customer.
     */
    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        $customer = Customer::create($request->validated());

        return redirect()->route('customers.show', $customer)
            ->with('success', __('customers.created_successfully'));
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer): Response
    {
        Gate::authorize('view', $customer);

        $customer->load(['jobs' => function ($query) {
            $query->with('assignedUser')
                ->orderBy('created_at', 'desc')
                ->limit(10);
        }]);

        return Inertia::render('Customers/Show', [
            'customer' => $customer,
            'recentJobs' => $customer->jobs,
        ]);
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer): Response
    {
        Gate::authorize('update', $customer);

        return Inertia::render('Customers/Edit', [
            'customer' => $customer,
        ]);
    }

    /**
     * Update the specified customer.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->validated());

        return redirect()->route('customers.show', $customer)
            ->with('success', __('customers.updated_successfully'));
    }

    /**
     * Remove the specified customer.
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        Gate::authorize('delete', $customer);

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', __('customers.deleted_successfully'));
    }

    /**
     * Search customers for autocomplete.
     */
    public function search(Request $request)
    {
        Gate::authorize('viewAny', Customer::class);

        $query = $request->get('q', '');

        $customers = Customer::where('name', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'phone', 'email', 'department', 'government_entity']);

        return response()->json($customers);
    }
}
