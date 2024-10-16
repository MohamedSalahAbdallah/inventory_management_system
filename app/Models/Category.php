<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Category extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, CascadeSoftDeletes;

    protected $fillable = [
        'name'
    ];

    // Implement the getActivitylogOptions method
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name'])
            ->logOnlyDirty()
            ->useLogName('Category')
            ->setDescriptionForEvent(fn(string $eventName) => "Category {$eventName}");
    }
    protected $cascadeDeletes = ['products'];

    public function products()
    {
        return $this->hasMany(Product::class, "category_id");
    }
}
