<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SalesOrder extends Model
{
    use HasFactory,LogsActivity;

    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
    ];

    // Implement the getActivitylogOptions method
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['user_id','total_amount','status'])
            ->logOnlyDirty()               
            ->useLogName('SalesOrder')                
            ->setDescriptionForEvent(fn(string $eventName) => "SalesOrder {$eventName}");  
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
    public function productSalesOrders()
    {
        return $this->hasMany(ProductSalesOrder::class, "sales_order_id");
    }
}
