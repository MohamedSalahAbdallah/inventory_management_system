<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Supplier extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, CascadeSoftDeletes;


    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'image'
    ];
    // Implement the getActivitylogOptions method
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'phone', 'address', 'image'])
            ->logOnlyDirty()
            ->useLogName('Supplier')
            ->setDescriptionForEvent(fn(string $eventName) => "Supplier {$eventName}");
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, "supplier_id");
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, "supplier_id");
    }
}
