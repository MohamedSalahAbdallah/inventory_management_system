<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class WarehouseSection extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'warehouse_id',
        'section_name',
        'section_type',
        'capacity',
        'empty_slots',
        'reserved_slots'
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'warehouse_id',
                'section_name',
                'section_type',
                'capacity',
                'empty_slots',
                'reserved_slots'
            ])
            ->logOnlyDirty()
            ->useLogName(logName: 'Warehouse')
            ->setDescriptionForEvent(fn(string $eventName) => "Warehouse {$eventName}");
    }
}
