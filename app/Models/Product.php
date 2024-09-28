<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'sku',
        'price',
        'quantity',
        'image',
        'category_id',
        'supplier_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, "category_id");
    }
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, "supplier_id");
    }
    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class, "product_id");
    }
    public function productSalesOrders(): HasMany
    {
        return $this->hasMany(ProductSalesOrder::class, "product_id");
    }

    public function productPurchaseOrders(): HasMany
    {
        return $this->hasMany(ProductPurchaseOrder::class, "product_id");
    }

    public function adjustments(): HasMany
    {
        return $this->hasMany(Adjustment::class, "product_id");
    }
}
