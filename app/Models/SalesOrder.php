<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
    public function productSalesOrders()
    {
        return $this->hasMany(ProductSalesOrder::class, "sales_order_id");
    }
}
