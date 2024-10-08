<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PurchaseOrder extends Model
{
    use HasFactory,LogsActivity;



    protected $fillable = [
        'user_id',
        'supplier_id',
        'total_amount',
        'status',
    ];

     // Implement the getActivitylogOptions method
     public function getActivitylogOptions(): LogOptions
     {
         return LogOptions::defaults()
             ->logOnly(['user_id','supplier_id','total_amount','status'])
             ->logOnlyDirty()               
             ->useLogName('PuchaseOrder')                
             ->setDescriptionForEvent(fn(string $eventName) => "PuchaseOrder {$eventName}");  
     }


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
