<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'name',
        'location',
        'total_capacity'
    ];

    public function sections()
    {
        return $this->hasMany(WarehouseSection::class);
    }
}
