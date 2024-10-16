<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, CascadeSoftDeletes;

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

    // Implement the getActivitylogOptions method
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'price', 'quantity', 'description', 'sku', 'image'])
            ->logOnlyDirty()
            ->useLogName('product')
            ->setDescriptionForEvent(fn(string $eventName) => "Product {$eventName}");
    }

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

    public function productWarehouse()
    {
        return $this->hasMany(ProductWarehouse::class, "product_id");
    }
}
