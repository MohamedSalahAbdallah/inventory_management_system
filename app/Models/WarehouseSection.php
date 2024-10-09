<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseSection extends Model
{
    use HasFactory, SoftDeletes;

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
}
