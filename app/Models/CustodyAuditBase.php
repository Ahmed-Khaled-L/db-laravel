<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustodyAuditBase extends Model
{
    protected $table = "custody_audit_bases";

    protected $fillable = [
        "date",
        "unit_price",
        "register_id",
        "page_no",
        "item_id",
        "audit_type", // 'Inventory' or 'Personnel'
    ];
    // Relationships
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function register(): BelongsTo
    {
        return $this->belongsTo(Register::class);
    }

    // Link to the specific personnel details
    public function personnelDetail(): HasOne
    {
        return $this->hasOne(PersonnelCustodyAudit::class, "id");
    }

    // NEW: Relationship for Inventory Custody
    public function inventoryDetail(): HasOne
    {
        return $this->hasOne(InventoryAudit::class, "id");
    }
    // NEW: Relationship to fetch details for the modal
    public function itemDetails(): HasMany
    {
        return $this->hasMany(ItemDetails::class, 'custody_audit_id');
    }
}
