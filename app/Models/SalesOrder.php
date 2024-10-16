<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class SalesOrder extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, CascadeSoftDeletes;

    protected $fillable = [
        'user_id',
        'total_amount',
        'customer_id'
    ];

    // Implement the getActivitylogOptions method
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['user_id', 'total_amount', 'status'])
            ->logOnlyDirty()
            ->useLogName('SalesOrder')
            ->setDescriptionForEvent(fn(string $eventName) => "SalesOrder {$eventName}");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    protected $cascadeDeletes = [
        'productSalesOrders'
    ];
    public function productSalesOrders()
    {
        return $this->hasMany(ProductSalesOrder::class, "sales_order_id");
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, "customer_id");
    }
}
