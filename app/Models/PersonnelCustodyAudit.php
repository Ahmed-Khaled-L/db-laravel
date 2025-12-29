<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonnelCustodyAudit extends Model
{
    protected $table = 'personnel_custody_audits';
    public $incrementing = false; // ID is foreign key from base table
    protected $guarded = [];

    // Relationships
    public function base(): BelongsTo
    {
        return $this->belongsTo(CustodyAuditBase::class, 'id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    // Custom relationship for Composite Key Category
    // Note: Laravel standard relationships struggle with composite keys slightly, 
    // but we can query it manually or use a trait if needed. 
    // For now, we will handle the saving logic in the controller.
}
