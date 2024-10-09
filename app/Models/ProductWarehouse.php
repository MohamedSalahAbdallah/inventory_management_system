<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductWarehouse extends Model
{
    use HasFactory, SoftDeletes;

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

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
