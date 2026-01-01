<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreItemMapping extends Model
{
    protected $table = 'store_item_mappings';

    // Since this table uses a composite key, we disable auto-incrementing
    public $incrementing = false;

    // Define the primary key
    protected $primaryKey = ['store_id', 'item_id'];

    protected $fillable = [
        'store_id',
        'item_id',
        'category_id',
    ];

    // Relationships

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function category(): BelongsTo
    {
        // FIX: Explicitly specify 'id' as the owner key.
        // This prevents Laravel from trying to use the composite PK ['id', 'type'] which causes the crash.
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * Set the keys for a save update query.
     * Required for Eloquent to update records with composite keys correctly.
     */
    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName) {
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    /**
     * Get the primary key value for a save query.
     */
    protected function getKeyForSaveQuery($keyName = null)
    {
        if (is_null($keyName)) {
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }
}
