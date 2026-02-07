<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Part extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_number',
        'name',
        'description',
        'category',
        'quantity_in_stock',
        'minimum_stock_level',
        'unit_price',
        'unit_of_measurement',
        'location',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'quantity_in_stock' => 'integer',
            'minimum_stock_level' => 'integer',
        ];
    }

    /**
     * Get all stock movements for this part
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Check if stock is below minimum level
     */
    public function isLowStock(): bool
    {
        return $this->quantity_in_stock <= $this->minimum_stock_level;
    }

    /**
     * Scope to filter by category
     */
    public function scopeOfCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to search parts
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('part_number', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Scope for low stock parts
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw('quantity_in_stock <= minimum_stock_level');
    }
}
