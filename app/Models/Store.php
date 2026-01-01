<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Store extends Model
{
    protected $fillable = [
        "code",
        "name",
        "responsible_employee_id",
        "classification",
        "custody_type",
    ];

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, "responsible_employee_id");
    }

    // Existing relationship (Requires the StoreItemMapping model created above)
    public function itemMappings(): HasMany
    {
        return $this->hasMany(StoreItemMapping::class);
    }

    // NEW: Easy access to Items directly
    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'store_item_mappings', 'store_id', 'item_id')
            ->withPivot('category_id')
            ->withTimestamps();
    }

    public function registerPages(): HasMany
    {
        return $this->hasMany(RegisterPage::class);
    }
}
