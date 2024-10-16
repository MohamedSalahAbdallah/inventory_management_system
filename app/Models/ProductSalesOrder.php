<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ProductSalesOrder extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, CascadeSoftDeletes;


    protected $fillable = [
        "quantity",
        "price",
        "product_id",
        "sales_order_id",
    ];

    // Implement the getActivitylogOptions method
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['quantity', 'price', 'product_id', 'sales_order_id'])
            ->logOnlyDirty()
            ->useLogName('ProductSalesOrder')
            ->setDescriptionForEvent(fn(string $eventName) => "ProductSalesOrder {$eventName}");
    }

    function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }
}
