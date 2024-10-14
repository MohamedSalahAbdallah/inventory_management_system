<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductWarehouse extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'warehouse_section_id',
        'product_id',
        'quantity',
    ];

    protected $table = 'product_warehouse';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function WarehouseSection()
    {
        return $this->belongsTo(WarehouseSection::class);
    }


    /**
     * The log options for the `Activitylog`.
     *
     * @return \Spatie\Activitylog\LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'warehouse_section_id',
                'product_id',
                'quantity',
            ])
            ->logOnlyDirty()
            ->useLogName(logName: 'ProductWarehouse')
            ->setDescriptionForEvent(fn(string $eventName) => "ProductWarehouse {$eventName}");
    }
}
