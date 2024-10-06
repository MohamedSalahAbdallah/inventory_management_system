<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'quantity',
        'price',
        'product_id',
        'purchase_order_id',
    ];


    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, "product_id");
    }
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, "purchase_order_id");
    }
}
