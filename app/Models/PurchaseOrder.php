<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;



    protected $fillable = [
        'user_id',
        'supplier_id',
        'total_amount',
        'status',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, "supplier_id");
    }
    public function productPurchaseOrders(): HasMany
    {
        return $this->hasMany(ProductPurchaseOrder::class, "purchase_order_id");
    }
}
