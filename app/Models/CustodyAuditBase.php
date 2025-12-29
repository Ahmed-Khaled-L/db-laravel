<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustodyAuditBase extends Model
{
    protected $table = 'custody_audit_base';
    protected $guarded = [];

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
        return $this->hasOne(PersonnelCustodyAudit::class, 'id');
    }
}
