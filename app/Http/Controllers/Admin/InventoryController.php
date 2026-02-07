<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:pentadbiran');
    }

    /**
     * Display inventory listing
     */
    public function index(Request $request)
    {
        $query = Part::query();

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->ofCategory($request->category);
        }

        // Filter for low stock
        if ($request->boolean('low_stock')) {
            $query->lowStock();
        }

        $parts = $query->orderBy('name')->paginate(15)->withQueryString();

        return Inertia::render('Admin/Inventory/Index', [
            'parts' => $parts,
            'filters' => $request->only(['search', 'category', 'low_stock']),
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        return Inertia::render('Admin/Inventory/Create');
    }

    /**
     * Store new part
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'part_number' => ['required', 'string', 'max:255', 'unique:parts,part_number'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'string', 'max:255'],
            'quantity_in_stock' => ['required', 'integer', 'min:0'],
            'minimum_stock_level' => ['required', 'integer', 'min:0'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'unit_of_measurement' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $part = Part::create($validated);

            // Record initial stock movement if quantity > 0
            if ($validated['quantity_in_stock'] > 0) {
                StockMovement::create([
                    'part_id' => $part->id,
                    'type' => 'in',
                    'quantity' => $validated['quantity_in_stock'],
                    'balance_after' => $validated['quantity_in_stock'],
                    'reason' => 'Initial stock',
                    'user_id' => $request->user()->id,
                ]);
            }
        });

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Part added successfully.');
    }

    /**
     * Show part details
     */
    public function show(Part $inventory)
    {
        $inventory->load(['stockMovements.user']);

        return Inertia::render('Admin/Inventory/Show', [
            'part' => $inventory,
            'movements' => $inventory->stockMovements()
                ->with('user')
                ->latest()
                ->paginate(20),
        ]);
    }

    /**
     * Show edit form
     */
    public function edit(Part $inventory)
    {
        return Inertia::render('Admin/Inventory/Edit', [
            'part' => $inventory,
        ]);
    }

    /**
     * Update part details
     */
    public function update(Request $request, Part $inventory)
    {
        $validated = $request->validate([
            'part_number' => ['required', 'string', 'max:255', 'unique:parts,part_number,' . $inventory->id],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'string', 'max:255'],
            'minimum_stock_level' => ['required', 'integer', 'min:0'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'unit_of_measurement' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        // Don't allow direct editing of quantity_in_stock (use adjustStock instead)
        $inventory->update($validated);

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Part updated successfully.');
    }

    /**
     * Delete part
     */
    public function destroy(Part $inventory)
    {
        $inventory->delete();

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Part deleted successfully.');
    }

    /**
     * Adjust stock level
     */
    public function adjustStock(Request $request, Part $part)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:in,out,adjustment'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reason' => ['nullable', 'string'],
            'reference' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated, $part, $request) {
            // Calculate new balance
            $currentStock = $part->quantity_in_stock;
            $quantity = $validated['quantity'];

            if ($validated['type'] === 'in') {
                $newBalance = $currentStock + $quantity;
            } elseif ($validated['type'] === 'out') {
                if ($currentStock < $quantity) {
                    throw new \Exception('Insufficient stock. Available: ' . $currentStock);
                }
                $newBalance = $currentStock - $quantity;
            } else { // adjustment
                $newBalance = $quantity; // Direct set to new value
                $quantity = $newBalance - $currentStock; // Adjust quantity to reflect change
            }

            // Record movement
            StockMovement::create([
                'part_id' => $part->id,
                'type' => $validated['type'],
                'quantity' => abs($quantity),
                'balance_after' => $newBalance,
                'reason' => $validated['reason'] ?? null,
                'reference' => $validated['reference'] ?? null,
                'user_id' => $request->user()->id,
            ]);

            // Update part stock
            $part->update(['quantity_in_stock' => $newBalance]);
        });

        return back()->with('success',  'Stock adjusted successfully.');
    }
}
