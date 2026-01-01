<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryAudit extends Model
{
    protected $table = "inventory_audits";

    // Important: ID is shared with CustodyAuditBase, so auto-increment is OFF
    public $incrementing = false;
    protected $primaryKey = "id";

    protected $fillable = [
        "id", // Manually set to match Base ID
        "store_id",
        "observed_quantity",
        "booked_quantity",
        "observed_state",
        "booked_state",
    ];

    public function base(): BelongsTo
    {
        return $this->belongsTo(CustodyAuditBase::class, "id");
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
