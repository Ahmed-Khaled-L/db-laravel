<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonnelCustodyAudit extends Model
{
    protected $table = "personnel_custody_audits";

    public $incrementing = false;
    protected $primaryKey = "id";

    protected $fillable = [
        "id",
        "employee_id",
        "quantity",
        "category_id",
        "category_type",
    ];
    // Relationships
    public function base(): BelongsTo
    {
        return $this->belongsTo(CustodyAuditBase::class, "id");
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getCategoryAttribute()
    {
        return Category::where('id', $this->category_id)
            ->where('type', $this->category_type)
            ->first();
    }
}
