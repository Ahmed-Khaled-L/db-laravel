<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    // Ensure we are filling the correct fields
    protected $fillable = [
        "code",
        "name",
        "responsible_employee_id",
        "classification",
        "custody_type",
    ];

    // IMPORTANT: If you had 'getRouteKeyName' here before, REMOVE IT.
    // We want Laravel to use the default 'id' for finding records.

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, "responsible_employee_id");
    }

    public function itemMappings(): HasMany
    {
        return $this->hasMany(StoreItemMapping::class);
    }

    public function registerPages(): HasMany
    {
        return $this->hasMany(RegisterPage::class);
    }
}
