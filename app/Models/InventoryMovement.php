<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'related_order_id',
        'movement_type',
    ];


    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, "product_id");
    }
    
}
