<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSalesOrder extends Model
{
    use HasFactory;


    protected $fillable = [
        "quantity",
        "price",
        "product_id",
        "sales_order_id",
    ];



    function product(): BelongsTo
    {
        return $this->belongsTo(product::class, 'product_id');
    }
    function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }
}
