<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemDetails extends Model
{
    // Explicitly define the table name if it doesn't follow convention (optional here, but good practice)
    protected $table = 'item_details';

    // Since the primary key is 'serial_no' and not 'id'
    protected $primaryKey = 'serial_no';

    // Since the primary key is a string, not an integer
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'serial_no',
        'expiry_date',
        'custody_audit_id',
    ];

    // Relationship back to the audit base
    public function custodyAudit(): BelongsTo
    {
        return $this->belongsTo(CustodyAuditBase::class, 'custody_audit_id');
    }
}
