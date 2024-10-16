<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Role extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, CascadeSoftDeletes;

    protected $fillable = [
        'name',
    ];

    // Implement the getActivitylogOptions method
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name'])
            ->logOnlyDirty()
            ->useLogName('Role')
            ->setDescriptionForEvent(fn(string $eventName) => "Role {$eventName}");
    }

    protected $cascadeDeletes = [
        'users'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, "role_id");
    }
}
