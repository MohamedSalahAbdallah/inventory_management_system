<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, CascadeSoftDeletes;


    protected $fillable = [
        'name',
        'location',
        'total_capacity'
    ];

    public function sections()
    {
        return $this->hasMany(WarehouseSection::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'location',
                'total_capacity'
            ])
            ->logOnlyDirty()
            ->useLogName(logName: 'Warehouse')
            ->setDescriptionForEvent(fn(string $eventName) => "Warehouse {$eventName}");
    }
}
